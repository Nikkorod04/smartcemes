# SmartCEMES Extension Programs - Architecture & Component Overview

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    SmartCEMES System                         │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │             Client Applications                       │   │
│  ├──────────────────────────────────────────────────────┤   │
│  │  • Web Dashboard    • Mobile App    • Third-party    │   │
│  └──────┬───────────────────────────────────────────────┘   │
│         │                                                    │
│  ┌──────┴───────────────────────────────────────────────┐   │
│  │        Extension Programs Module                      │   │
│  ├──────────────────────────────────────────────────────┤   │
│  │                                                       │   │
│  │  ┌────────────────┐      ┌────────────────┐         │   │
│  │  │  Web Routes    │      │  API Routes    │         │   │
│  │  │  (/admin/...)  │      │  (/api/v1/..)  │         │   │
│  │  └────────┬───────┘      └────────┬───────┘         │   │
│  │           │                       │                  │   │
│  │  ┌────────▼────────────────────────▼──────┐         │   │
│  │  │    Controller Layer                    │         │   │
│  │  ├────────────────────────────────────────┤         │   │
│  │  │ • ManageExtensionPrograms (Livewire) │         │   │
│  │  │ • ExtensionProgramController (API)   │         │   │
│  │  │ • CommunityController (API)          │         │   │
│  │  │ • UserController (API)               │         │   │
│  │  │ • AuthController (API)               │         │   │
│  │  └────────┬─────────────────────────────┘         │   │
│  │           │                                        │   │
│  │  ┌────────▼─────────────────────────────┐         │   │
│  │  │    Business Logic Layer               │         │   │
│  │  ├───────────────────────────────────────┤         │   │
│  │  │ • ExtensionProgram Model             │         │   │
│  │  │ • Community Model                    │         │   │
│  │  │ • User Model                         │         │   │
│  │  │ • Validation Rules                   │         │   │
│  │  └────────┬───────────────────────────┘         │   │
│  │           │                                     │   │
│  │  ┌────────▼──────────────────────────┐         │   │
│  │  │    Data Access Layer                │         │   │
│  │  ├─────────────────────────────────────┤         │   │
│  │  │    Database (MySQL)                 │         │   │
│  │  │    • extension_programs table       │         │   │
│  │  │    • communities table              │         │   │
│  │  │    • users table                    │         │   │
│  │  └─────────────────────────────────────┘         │   │
│  │                                                  │   │
│  └──────────────────────────────────────────────────┘   │
│                                                         │
│  ┌──────────────────────────────────────────────────┐   │
│  │     Supporting Services                          │   │
│  ├──────────────────────────────────────────────────┤   │
│  │ • Authentication (Laravel Sanctum)               │   │
│  │ • File Storage (Local/S3)                        │   │
│  │ • Caching (Redis)                                │   │
│  │ • Logging (Laravel Log)                          │   │
│  └──────────────────────────────────────────────────┘   │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

## Data Flow Architecture

### Web Application Flow
```
User Browser
    │
    ├─→ Authentication Route ─→ Login Controller ─→ Session Created
    │
    ├─→ GET /admin/extension-programs
         │
         ├─→ Route Handler
         ├─→ Admin Middleware Check
         ├─→ View Rendering
         └─→ Livewire Component Mount
             │
             ├─→ Query Database
             ├─→ Format Data
             └─→ Return to Browser
    │
    ├─→ Livewire Events (AJAX)
         │
         ├─→ Search/Filter
         ├─→ Pagination
         ├─→ Create Program
         ├─→ Update Program
         └─→ Delete Program
             │
             └─→ Database Operations
```

### API Flow
```
Client Application
    │
    ├─→ POST /api/v1/auth/login
         │
         ├─→ AuthController
         ├─→ Validate Credentials
         ├─→ Generate Token
         └─→ Return Token + User Data
    │
    ├─→ API Requests (with Bearer Token)
         │
         ├─→ Sanctum Middleware (Verify Token)
         ├─→ Route Matching
         ├─→ Controller Action
         ├─→ Business Logic
         ├─→ Database Query
         └─→ JSON Response
    │
    └─→ Response Processing
         │
         ├─→ Status Code
         ├─→ JSON Data
         ├─→ Pagination Metadata
         └─→ Error Messages
```

## Component Relationships

```
┌───────────────────────────────────────────────────────────────┐
│                      Extension Programs                        │
├───────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │            User (Creator/Updater)                       │  │
│  │            ↓ (created_by, updated_by)                   │  │
│  │                                                         │  │
│  │  ┌────────────────────────────────────────────────┐    │  │
│  │  │        ExtensionProgram Model                  │    │  │
│  │  ├────────────────────────────────────────────────┤    │  │
│  │  │ Properties:                                    │    │  │
│  │  │ • id (PK)                                      │    │  │
│  │  │ • title                                        │    │  │
│  │  │ • description                                  │    │  │
│  │  │ • goals                                        │    │  │
│  │  │ • objectives                                   │    │  │
│  │  │ • cover_image                                  │    │  │
│  │  │ • gallery_images (JSON)                        │    │  │
│  │  │ • activities                                   │    │  │
│  │  │ • related_communities (JSON array of IDs)      │    │  │
│  │  │ • attachments (JSON)                           │    │  │
│  │  │ • status (enum)                                │    │  │
│  │  │ • notes                                        │    │  │
│  │  │ • created_by (FK → users)                      │    │  │
│  │  │ • updated_by (FK → users)                      │    │  │
│  │  │ • timestamps (created_at, updated_at)          │    │  │
│  │  │ • deleted_at (soft delete)                     │    │  │
│  │  │                                                │    │  │
│  │  │ Methods:                                       │    │  │
│  │  │ • creator() - Relationship to User            │    │  │
│  │  │ • updater() - Relationship to User            │    │  │
│  │  │ • communities() - Get Related Communities     │    │  │
│  │  │ • scopeActive() - Filter active programs      │    │  │
│  │  │ • scopeByStatus(status) - Filter by status   │    │  │
│  │  │ • getCoverImageUrl() - Get image URL          │    │  │
│  │  └────────────────────────────────────────────────┘    │  │
│  │                     ↓ (related_communities)            │  │
│  │  ┌────────────────────────────────────────────────┐    │  │
│  │  │        Community Model                         │    │  │
│  │  ├────────────────────────────────────────────────┤    │  │
│  │  │ Properties:                                    │    │  │
│  │  │ • id (PK)                                      │    │  │
│  │  │ • name                                         │    │  │
│  │  │ • code                                         │    │  │
│  │  │ • location                                     │    │  │
│  │  │ • status                                       │    │  │
│  │  └────────────────────────────────────────────────┘    │  │
│  └────────────────────────────────────────────────────────┘  │
└───────────────────────────────────────────────────────────────┘
```

## Request/Response Flow

### Web Request Flow
```
1. Client makes request
   GET /admin/extension-programs
        ↓
2. Web Route matches
   route('admin.extension-programs')
        ↓
3. Middleware processing
   Auth Check → Admin Role Check
        ↓
4. View rendered
   resources/views/admin/extension-programs.blade.php
        ↓
5. Livewire component loaded
   ManageExtensionPrograms::class
        ↓
6. Component mount() executes
   Load communities, set up defaults
        ↓
7. render() executes
   Build query, apply filters, paginate
        ↓
8. View rendered
   manage-extension-programs.blade.php
        ↓
9. HTML + JavaScript sent to browser
   Livewire JavaScript loaded
        ↓
10. User interacts (click, submit, etc)
    Livewire AJAX request sent
        ↓
11. Component method executes
    (e.g., createProgram(), deleteProgram())
        ↓
12. Database operation
    Insert/Update/Delete
        ↓
13. Component state updated
    Notification set, form closed
        ↓
14. View re-rendered
    HTML pushed to browser
        ↓
15. DOM updated
    User sees changes
```

### API Request Flow
```
1. Client makes request with Bearer token
   GET /api/v1/extension-programs
   Authorization: Bearer {token}
        ↓
2. API routes match
   routes/api.php
        ↓
3. Middleware processing
   Sanctum token validation
   User authenticated?
        ↓
4. Controller action called
   ExtensionProgramController@index()
        ↓
5. Request validation
   $request->validate([...])
        ↓
6. Business logic executes
   Build query with filters
   Apply pagination
   Execute query
        ↓
7. Data formatting
   formatProgram($program)
        ↓
8. Response building
   response()->json([...])
        ↓
9. JSON response sent
   Status code included
   Headers included
        ↓
10. Client receives response
    Parses JSON
    Handles data
```

## Database Schema

```
extension_programs
│
├─ id (Primary Key)
├─ title (Unique, Full-Text Index)
├─ description (Full-Text Index)
├─ goals (Full-Text Index)
├─ objectives (Full-Text Index)
│
├─ cover_image (File path)
├─ gallery_images (JSON array)
├─ activities (Text)
├─ related_communities (JSON array of IDs)
├─ attachments (JSON array)
│
├─ status (Enum: active, inactive, planning, completed)
│  └─ [Index] for filtering
│
├─ notes (Nullable)
│
├─ created_by (Foreign Key → users.id)
│  └─ [Index] for auditing
├─ updated_by (Foreign Key → users.id)
│
├─ created_at (Timestamp)
├─ updated_at (Timestamp)
└─ deleted_at (Soft Delete Timestamp)
```

## Authentication & Authorization

```
┌────────────────────────────────────┐
│     Authentication System          │
├────────────────────────────────────┤
│                                    │
│  1. User submits credentials       │
│     POST /api/v1/auth/login        │
│     or Web login form              │
│                                    │
│  2. Verify credentials             │
│     Check email & password         │
│                                    │
│  3. Create token (API)             │
│     Laravel Sanctum                │
│     Token stored in DB             │
│                                    │
│  4. Return token                   │
│     To client for future requests  │
│                                    │
│  5. Token validation               │
│     Each API request includes      │
│     Authorization: Bearer {token}  │
│                                    │
│  6. Sanctum middleware             │
│     Verifies token in DB           │
│     Loads user from token          │
│                                    │
│  7. Request processed              │
│     With user context              │
│                                    │
│  8. Logout                         │
│     Token deleted from DB          │
│                                    │
└────────────────────────────────────┘

Authorization Levels:
  Admin    → Full access to all operations
  Secretary→ Can manage assigned programs
  Viewer   → Read-only access
```

## File Upload Flow

```
User selects image
       ↓
Form submitted with file
       ↓
Livewire receives file
       ↓
Validation:
  ├─ Is file an image? ✓
  ├─ Size < 5MB? ✓
       ↓
File stored to:
  storage/app/public/programs/covers/
       ↓
Path saved to database:
  programs/covers/filename.jpg
       ↓
Storage symlink creates URL:
  /storage/programs/covers/filename.jpg
       ↓
URL returned in response
       ↓
Image displayed in browser
```

## Pagination Flow

```
Client requests:
  GET /api/v1/extension-programs?page=2&per_page=20
       ↓
Query builder:
  select * from extension_programs
  where status = 'active'
  order by created_at desc
  limit 20 offset 20
       ↓
Results returned with metadata:
  {
    items: [...20 results...],
    pagination: {
      total: 150,
      per_page: 20,
      current_page: 2,
      last_page: 8
    }
  }
       ↓
Client displays:
  Results with pagination links
  Next/Previous buttons
  Total count
```

## Search & Filter Flow

```
User enters search term: "agriculture"
       ↓
Livewire searches:
  ->where('title', 'like', '%agriculture%')
   ->orWhere('description', 'like', '%agriculture%')
       ↓
User selects status filter: "active"
       ↓
Query adds condition:
  ->where('status', 'active')
       ↓
Results fetched and paginated
       ↓
View updates in real-time
  Programs matching criteria shown
  Others hidden
  Total count updated
       ↓
User clears filters
       ↓
Query reset to default
  All programs shown again
```

## Error Handling Flow

```
Operation requested
       ↓
Input validation
  ├─ Required fields present?
  ├─ Data types correct?
  ├─ Length constraints met?
  └─ Business rules satisfied?
       ↓
Validation fails?
  ├─ Error object created
  ├─ Error messages collected
  ├─ Response with 422 status
  └─ Client shows error messages
       ↓
Database operation
  ├─ Query fails?
  ├─ Exception caught
  ├─ Log error
  ├─ Return 500 error
  └─ User sees generic error
       ↓
Success
  ├─ Operation completes
  ├─ Notification shown
  ├─ UI updates
  └─ New state rendered
```

## Module Integration Points

### With User System
- Uses existing User model
- Tracks created_by and updated_by
- Respects user roles

### With Community System
- Links to existing communities
- Stores community IDs as JSON array
- Supports multiple communities per program

### With Authentication
- Uses Laravel Sanctum for API
- Uses web guard for web routes
- Supports both token and session auth

### With Database
- Uses Laravel migrations
- Follows naming conventions
- Uses existing database connection

## Caching Strategy

```
Program Lists
  ├─ Cache key: extension_programs_list_{page}_{status}
  ├─ TTL: 5 minutes
  ├─ Invalidate on: Create/Update/Delete
       
Communities
  ├─ Cache key: communities_active
  ├─ TTL: 1 hour
  ├─ Invalidate on: Community change
```

---

Created: March 2025 | Version: 1.0.0 | Status: Complete
