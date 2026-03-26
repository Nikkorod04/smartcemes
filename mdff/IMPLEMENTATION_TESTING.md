# SmartCEMES Implementation Testing Guide

## Table of Contents
1. [Testing Overview](#testing-overview)
2. [Unit Tests](#unit-tests)
3. [Feature Tests](#feature-tests)
4. [API Tests](#api-tests)
5. [Livewire Component Tests](#livewire-component-tests)
6. [Manual Testing Checklist](#manual-testing-checklist)
7. [Performance Testing](#performance-testing)

## Testing Overview

This guide provides comprehensive testing procedures for the SmartCEMES system. All tests should be run before deploying to production.

### Prerequisites
- PHPUnit installed
- Laravel Sail or Docker
- Postman or similar API testing tool
- Test database configured

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Unit/Models/ExtensionProgramTest.php

# Run with coverage
php artisan test --coverage
```

## Unit Tests

### 1. Extension Program Model Tests

**File:** `tests/Unit/Models/ExtensionProgramTest.php`

```php
<?php

namespace Tests\Unit\Models;

use App\Models\ExtensionProgram;
use App\Models\User;
use App\Models\Community;
use Tests\TestCase;

class ExtensionProgramTest extends TestCase
{
    protected ExtensionProgram $program;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->program = ExtensionProgram::factory()->create([
            'created_by' => $this->user->id,
        ]);
    }

    public function test_extension_program_can_be_created()
    {
        $this->assertNotNull($this->program->id);
        $this->assertEquals('active', $this->program->status);
    }

    public function test_extension_program_has_creator()
    {
        $this->assertNotNull($this->program->creator);
        $this->assertEquals($this->user->id, $this->program->creator->id);
    }

    public function test_extension_program_can_have_related_communities()
    {
        $communities = Community::factory()->count(3)->create();
        $this->program->update([
            'related_communities' => $communities->pluck('id')->toArray(),
        ]);

        $this->assertCount(3, $this->program->related_communities);
    }

    public function test_extension_program_scope_active()
    {
        ExtensionProgram::factory()->create(['status' => 'inactive']);
        
        $active = ExtensionProgram::active()->get();
        
        foreach ($active as $program) {
            $this->assertEquals('active', $program->status);
        }
    }

    public function test_extension_program_scope_by_status()
    {
        ExtensionProgram::factory()->create(['status' => 'planning']);
        
        $planning = ExtensionProgram::byStatus('planning')->get();
        
        foreach ($planning as $program) {
            $this->assertEquals('planning', $program->status);
        }
    }

    public function test_get_cover_image_url()
    {
        $this->program->update(['cover_image' => 'programs/covers/test.jpg']);
        
        $url = $this->program->getCoverImageUrl();
        
        $this->assertStringContainsString('storage/programs/covers/test.jpg', $url);
    }
}
```

### 2. User Model Tests

**File:** `tests/Unit/Models/UserTest.php`

```php
<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_can_be_created()
    {
        $user = User::factory()->create();
        
        $this->assertNotNull($user->id);
        $this->assertIn($user->role, ['admin', 'secretary', 'viewer']);
    }

    public function test_user_password_is_hashed()
    {
        $password = 'test-password-123';
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        $this->assertTrue(Hash::check($password, $user->password));
    }

    public function test_user_can_generate_api_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $this->assertNotNull($token);
    }
}
```

## Feature Tests

### 1. Extension Program Livewire Component Tests

**File:** `tests/Feature/ManageExtensionProgramsTest.php`

```php
<?php

namespace Tests\Feature;

use App\Livewire\ManageExtensionPrograms;
use App\Models\ExtensionProgram;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class ManageExtensionProgramsTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_component_can_render()
    {
        $this->actingAs($this->admin);

        Livewire::test(ManageExtensionPrograms::class)
            ->assertStatus(200);
    }

    public function test_can_list_extension_programs()
    {
        $this->actingAs($this->admin);
        
        ExtensionProgram::factory()->count(5)->create();

        Livewire::test(ManageExtensionPrograms::class)
            ->assertSee('Extension Programs')
            ->assertViewHas('programs');
    }

    public function test_can_create_extension_program()
    {
        $this->actingAs($this->admin);

        Livewire::test(ManageExtensionPrograms::class)
            ->set('title', 'New Program')
            ->set('description', 'Program description')
            ->set('status', 'active')
            ->call('createProgram')
            ->assertDispatchedTo(ManageExtensionPrograms::class, 'refreshComponent');

        $this->assertDatabaseHas('extension_programs', [
            'title' => 'New Program',
        ]);
    }

    public function test_can_edit_extension_program()
    {
        $this->actingAs($this->admin);
        
        $program = ExtensionProgram::factory()->create();

        Livewire::test(ManageExtensionPrograms::class)
            ->call('editProgram', $program->id)
            ->assertSet('editingId', $program->id)
            ->set('title', 'Updated Title')
            ->call('updateProgram')
            ->assertDispatched('refreshComponent');

        $this->assertDatabaseHas('extension_programs', [
            'id' => $program->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_can_delete_extension_program()
    {
        $this->actingAs($this->admin);
        
        $program = ExtensionProgram::factory()->create();

        Livewire::test(ManageExtensionPrograms::class)
            ->call('confirmDelete', $program->id)
            ->call('deleteProgram')
            ->assertDispatched('refreshComponent');

        $this->assertSoftDeleted('extension_programs', [
            'id' => $program->id,
        ]);
    }

    public function test_can_filter_programs_by_search()
    {
        $this->actingAs($this->admin);
        
        ExtensionProgram::factory()->create(['title' => 'Agriculture Program']);
        ExtensionProgram::factory()->create(['title' => 'Health Program']);

        Livewire::test(ManageExtensionPrograms::class)
            ->set('searchTerm', 'Agriculture')
            ->assertSee('Agriculture Program')
            ->assertDontSee('Health Program');
    }

    public function test_can_filter_programs_by_status()
    {
        $this->actingAs($this->admin);
        
        ExtensionProgram::factory()->create(['status' => 'active']);
        ExtensionProgram::factory()->create(['status' => 'inactive']);

        Livewire::test(ManageExtensionPrograms::class)
            ->set('filterStatus', 'active')
            ->assertSee('Active');
    }
}
```

## API Tests

### 1. Extension Programs API Tests

**File:** `tests/Feature/Api/ExtensionProgramApiTest.php`

```php
<?php

namespace Tests\Feature\Api;

use App\Models\ExtensionProgram;
use App\Models\User;
use Tests\TestCase;

class ExtensionProgramApiTest extends TestCase
{
    protected User $user;
    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->admin()->create();
        $token = $this->user->createToken('test-token')->plainTextToken;
        $this->headers = [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ];
    }

    public function test_can_list_extension_programs()
    {
        ExtensionProgram::factory()->count(5)->create();

        $response = $this->withHeaders($this->headers)->get('/api/v1/extension-programs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'status',
                        ],
                    ],
                    'pagination',
                ],
            ]);
    }

    public function test_can_get_single_extension_program()
    {
        $program = ExtensionProgram::factory()->create();

        $response = $this->withHeaders($this->headers)->get("/api/v1/extension-programs/{$program->id}");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.id', $program->id);
    }

    public function test_can_create_extension_program()
    {
        $data = [
            'title' => 'Test Program',
            'description' => 'Test description',
            'status' => 'active',
        ];

        $response = $this->withHeaders($this->headers)->post('/api/v1/extension-programs', $data);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.title', 'Test Program');

        $this->assertDatabaseHas('extension_programs', [
            'title' => 'Test Program',
        ]);
    }

    public function test_can_update_extension_program()
    {
        $program = ExtensionProgram::factory()->create();

        $data = [
            'title' => 'Updated Title',
            'status' => 'completed',
        ];

        $response = $this->withHeaders($this->headers)
            ->put("/api/v1/extension-programs/{$program->id}", $data);

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Updated Title');

        $this->assertDatabaseHas('extension_programs', [
            'id' => $program->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_can_delete_extension_program()
    {
        $program = ExtensionProgram::factory()->create();

        $response = $this->withHeaders($this->headers)
            ->delete("/api/v1/extension-programs/{$program->id}");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertSoftDeleted('extension_programs', [
            'id' => $program->id,
        ]);
    }

    public function test_can_bulk_update_status()
    {
        $programs = ExtensionProgram::factory()->count(3)->create(['status' => 'active']);

        $data = [
            'ids' => $programs->pluck('id')->toArray(),
            'status' => 'completed',
        ];

        $response = $this->withHeaders($this->headers)
            ->patch('/api/v1/extension-programs/bulk-status', $data);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        foreach ($programs as $program) {
            $this->assertDatabaseHas('extension_programs', [
                'id' => $program->id,
                'status' => 'completed',
            ]);
        }
    }

    public function test_api_requires_authentication()
    {
        $response = $this->get('/api/v1/extension-programs');

        $response->assertStatus(401);
    }

    public function test_validation_fails_for_missing_required_fields()
    {
        $data = [
            'title' => 'Test Program',
            // Missing required 'description'
        ];

        $response = $this->withHeaders($this->headers)->post('/api/v1/extension-programs', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('description');
    }
}
```

### 2. Authentication API Tests

**File:** `tests/Feature/Api/AuthApiTest.php`

```php
<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    public function test_can_login_and_get_token()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['token', 'user'],
            ])
            ->assertJsonPath('status', 'success');
    }

    public function test_login_fails_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');
    }
}
```

## Livewire Component Tests

### Component Interactivity Tests

```php
public function test_component_shows_notification_on_success()
{
    $this->actingAs($this->admin);

    Livewire::test(ManageExtensionPrograms::class)
        ->call('openForm')
        ->set('title', 'Test Program')
        ->set('description', 'Test Description')
        ->set('status', 'active')
        ->call('createProgram')
        ->assertSet('notification', 'Extension Program created successfully!')
        ->assertSet('notificationType', 'success');
}

public function test_component_validates_required_fields()
{
    $this->actingAs($this->admin);

    Livewire::test(ManageExtensionPrograms::class)
        ->call('createProgram')
        ->assertHasErrors(['title', 'description', 'status']);
}

public function test_component_pagination_works()
{
    $this->actingAs($this->admin);
    
    ExtensionProgram::factory()->count(25)->create();

    Livewire::test(ManageExtensionPrograms::class)
        ->assertSee('Next')
        ->assertSee('Previous');
}
```

## Manual Testing Checklist

### 1. User Authentication
- [ ] Admin can login with correct credentials
- [ ] Login fails with incorrect password
- [ ] Email validation works
- [ ] Logout works properly
- [ ] Session expires after configured time
- [ ] API token authentication works

### 2. Extension Programs Management
- [ ] Admin can view all programs
- [ ] Admin can create new program
- [ ] Program creation validates all fields
- [ ] Admin can edit existing programs
- [ ] Program edit maintains previous data
- [ ] Admin can delete programs
- [ ] Deleted programs are soft deleted
- [ ] Search filter works correctly
- [ ] Status filter works correctly
- [ ] Pagination works correctly

### 3. Cover Image Upload
- [ ] Image upload validates file type
- [ ] Image upload validates file size
- [ ] Uploaded images display correctly
- [ ] Image URL is generated correctly
- [ ] Previous image is replaced on re-upload

### 4. Communities Link
- [ ] Can select multiple communities
- [ ] Selected communities are saved
- [ ] Community names display correctly
- [ ] Can unselect communities

### 5. API Endpoints
- [ ] GET /api/v1/extension-programs returns list
- [ ] GET /api/v1/extension-programs/{id} returns single program
- [ ] POST /api/v1/extension-programs creates program
- [ ] PUT /api/v1/extension-programs/{id} updates program
- [ ] DELETE /api/v1/extension-programs/{id} deletes program
- [ ] Authentication endpoints work correctly

### 6. Data Validation
- [ ] Duplicate titles are rejected
- [ ] Invalid email addresses are rejected
- [ ] Empty descriptions are rejected
- [ ] Invalid status values are rejected

### 7. Response Format
- [ ] All responses include status field
- [ ] Error responses include error descriptions
- [ ] Pagination includes correct metadata
- [ ] Timestamps are in ISO 8601 format

## Performance Testing

### Load Testing

Use Apache JMeter or similar tool:

1. **Test Configuration:**
   - 100 concurrent users
   - 5-minute ramp-up
   - 30-minute test duration

2. **Endpoints to Test:**
   - GET /api/v1/extension-programs (with pagination)
   - GET /api/v1/extension-programs/{id}
   - POST /api/v1/extension-programs
   - PUT /api/v1/extension-programs/{id}

3. **Expected Results:**
   - Response time < 500ms (p95)
   - Error rate < 1%
   - Database connection pool utilization < 80%

### Database Performance

```sql
-- Check slow queries
SELECT * FROM mysql.slow_log;

-- Analyze query performance
EXPLAIN SELECT * FROM extension_programs 
WHERE status = 'active' 
ORDER BY created_at DESC 
LIMIT 20;

-- Check index usage
SHOW INDEX FROM extension_programs;
```

### Cache Performance

1. **Redis Cache Test:**
   ```bash
   redis-benchmark -h localhost -p 6379 -c 100 -n 100000
   ```

2. **Query Caching:**
   - Cache program lists for 5 minutes
   - Invalidate on create/update/delete

---

## Continuous Integration

### GitHub Actions Workflow

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
      
      - name: Install dependencies
        run: composer install
      
      - name: Run tests
        run: php artisan test --coverage
```

---

**Last Updated:** March 2025
**Version:** 1.0.0
