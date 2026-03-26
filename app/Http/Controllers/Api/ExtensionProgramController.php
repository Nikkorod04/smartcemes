<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExtensionProgram;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ExtensionProgramController extends Controller
{
    public function __construct()
    {
        // Only allow admin and secretary roles
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || !in_array($user->role, ['admin', 'secretary'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access',
                ], 403);
            }
            return $next($request);
        });
    }

    /**
     * List all extension programs with pagination
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
            'status' => 'string|in:active,inactive,planning,completed',
            'search' => 'string|max:255',
        ]);

        $query = ExtensionProgram::query();

        // Apply status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 20);
        $programs = $query->latest('created_at')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $programs->items(),
                'pagination' => [
                    'total' => $programs->total(),
                    'per_page' => $programs->perPage(),
                    'current_page' => $programs->currentPage(),
                    'last_page' => $programs->lastPage(),
                ],
            ],
        ]);
    }

    /**
     * Get a single extension program
     */
    public function show($id)
    {
        $program = ExtensionProgram::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $this->formatProgram($program),
        ]);
    }

    /**
     * Create a new extension program
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:extension_programs',
            'description' => 'required|string|max:2000',
            'goals' => 'nullable|string|max:2000',
            'objectives' => 'nullable|string|max:2000',
            'status' => 'required|in:active,inactive,planning,completed',
            'related_communities' => 'nullable|array',
            'related_communities.*' => 'integer|exists:communities,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['created_by'] = Auth::id();

        $program = ExtensionProgram::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Extension program created successfully',
            'data' => $this->formatProgram($program),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update an extension program
     */
    public function update(Request $request, $id)
    {
        $program = ExtensionProgram::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255|unique:extension_programs,title,' . $id,
            'description' => 'sometimes|required|string|max:2000',
            'goals' => 'nullable|string|max:2000',
            'objectives' => 'nullable|string|max:2000',
            'status' => 'sometimes|required|in:active,inactive,planning,completed',
            'related_communities' => 'nullable|array',
            'related_communities.*' => 'integer|exists:communities,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['updated_by'] = Auth::id();

        $program->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Extension program updated successfully',
            'data' => $this->formatProgram($program),
        ]);
    }

    /**
     * Delete an extension program
     */
    public function destroy($id)
    {
        $program = ExtensionProgram::findOrFail($id);
        $program->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Extension program deleted successfully',
        ]);
    }

    /**
     * Upload cover image
     */
    public function uploadCoverImage(Request $request, $id)
    {
        $program = ExtensionProgram::findOrFail($id);

        $validated = $request->validate([
            'cover_image' => 'required|image|max:5120',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('programs/covers', 'public');
            $program->update(['cover_image' => $path]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Cover image uploaded successfully',
            'data' => [
                'url' => asset('storage/' . $program->cover_image),
            ],
        ]);
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:extension_programs,id',
            'status' => 'required|in:active,inactive,planning,completed',
        ]);

        ExtensionProgram::whereIn('id', $validated['ids'])->update([
            'status' => $validated['status'],
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Programs updated successfully',
        ]);
    }

    /**
     * Format program data for response
     */
    private function formatProgram(ExtensionProgram $program)
    {
        return [
            'id' => $program->id,
            'title' => $program->title,
            'description' => $program->description,
            'goals' => $program->goals,
            'objectives' => $program->objectives,
            'status' => $program->status,
            'cover_image_url' => $program->getCoverImageUrl(),
            'gallery_images' => $program->gallery_images ?? [],
            'activities' => $program->activities ? explode(',', $program->activities) : [],
            'related_communities' => $program->related_communities ?? [],
            'attachments' => $program->attachments ?? [],
            'notes' => $program->notes,
            'created_by' => $program->creator ? [
                'id' => $program->creator->id,
                'name' => $program->creator->name,
            ] : null,
            'updated_by' => $program->updater ? [
                'id' => $program->updater->id,
                'name' => $program->updater->name,
            ] : null,
            'created_at' => $program->created_at?->toIso8601String(),
            'updated_at' => $program->updated_at?->toIso8601String(),
        ];
    }
}
