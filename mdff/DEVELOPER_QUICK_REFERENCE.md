# SmartCEMES Extension Programs - Developer Quick Reference

## Quick Start

### 1. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed
```

### 2. Start Development Server
```bash
# Artisan server
php artisan serve

# Or with Sail
./vendor/bin/sail up -d
```

### 3. Access the Application
- Dashboard: `http://localhost:8000/admin/extension-programs`
- API Docs: `http://localhost:8000/api/docs`
- Tinker: `php artisan tinker`

## Common Commands

### User Management
```bash
# Create admin user
php artisan tinker
User::create(['name' => 'Admin', 'email' => 'admin@test.local', 'password' => Hash::make('password'), 'role' => 'admin'])

# Create secretary user
User::create(['name' => 'Secretary', 'email' => 'secretary@test.local', 'password' => Hash::make('password'), 'role' => 'secretary'])
```

### Database Operations
```bash
# Fresh migration
php artisan migrate:fresh

# Rollback
php artisan migrate:rollback

# Refresh with seeding
php artisan migrate:fresh --seed
```

### Cache Management
```bash
# Clear all caches
php artisan cache:clear

# Clear specific cache
php artisan cache:forget extension_programs_list

# Cache configuration
php artisan config:cache
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test tests/Feature/ManageExtensionProgramsTest.php

# Run with coverage
php artisan test --coverage

# Run single test method
php artisan test --filter test_can_create_extension_program
```

## API Quick Reference

### Authentication
```bash
# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@test.local","password":"password"}'

# Get token
TOKEN=$(curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@test.local","password":"password"}' | jq -r '.data.token')

# Logout
curl -X POST http://localhost:8000/api/v1/auth/logout \
  -H "Authorization: Bearer $TOKEN"
```

### Extension Programs
```bash
# List programs
curl -X GET 'http://localhost:8000/api/v1/extension-programs?page=1&per_page=20' \
  -H "Authorization: Bearer $TOKEN"

# Get single program
curl -X GET http://localhost:8000/api/v1/extension-programs/1 \
  -H "Authorization: Bearer $TOKEN"

# Create program
curl -X POST http://localhost:8000/api/v1/extension-programs \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title":"New Program",
    "description":"Program description",
    "status":"active",
    "related_communities":[1,2]
  }'

# Update program
curl -X PUT http://localhost:8000/api/v1/extension-programs/1 \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"status":"completed"}'

# Delete program
curl -X DELETE http://localhost:8000/api/v1/extension-programs/1 \
  -H "Authorization: Bearer $TOKEN"

# Upload cover image
curl -X POST http://localhost:8000/api/v1/extension-programs/1/cover-image \
  -H "Authorization: Bearer $TOKEN" \
  -F "cover_image=@path/to/image.jpg"

# Bulk update status
curl -X PATCH http://localhost:8000/api/v1/extension-programs/bulk-status \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "ids":[1,2,3],
    "status":"completed"
  }'
```

### Communities
```bash
# List communities
curl -X GET http://localhost:8000/api/v1/communities \
  -H "Authorization: Bearer $TOKEN"

# Create community
curl -X POST http://localhost:8000/api/v1/communities \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name":"Community Name",
    "code":"COM001",
    "location":"Location",
    "status":"active"
  }'
```

### Users
```bash
# List users
curl -X GET http://localhost:8000/api/v1/users \
  -H "Authorization: Bearer $TOKEN"

# Get current user
curl -X GET http://localhost:8000/api/v1/users/me \
  -H "Authorization: Bearer $TOKEN"

# Create user
curl -X POST http://localhost:8000/api/v1/users \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name":"John Doe",
    "email":"john@test.local",
    "password":"password",
    "role":"secretary"
  }'
```

## File Locations

| Component | Path |
|-----------|------|
| Livewire Component | `app/Livewire/ManageExtensionPrograms.php` |
| Component View | `resources/views/livewire/manage-extension-programs.blade.php` |
| Admin Dashboard View | `resources/views/admin/extension-programs.blade.php` |
| API Controllers | `app/Http/Controllers/Api/*.php` |
| Models | `app/Models/ExtensionProgram.php` |
| Routes (API) | `routes/api.php` |
| Routes (Web) | `routes/web.php` |
| Migration | `database/migrations/*_create_extension_programs_table.php` |
| Documentation | `API_DOCUMENTATION.md` |
| Tests | `tests/Feature/` and `tests/Unit/` |

## Common Modifications

### Add New Status
1. Update migration enum in `create_extension_programs_table.php`
2. Update model factory if needed
3. Update API validation in controllers
4. Add badge styling in Blade view
5. Run migration: `php artisan migrate`

### Change Max File Size
- Update validation in `ManageExtensionPrograms.php`: `max:5120` (in KB)
- Update API controller: `max:5120`
- Ensure server `php.ini` allows it: `post_max_size`

### Modify Pagination Size
Replace in `ManageExtensionPrograms.php`:
```php
// Old
->paginate((int)$this->pageSize);

// New: Change default
->paginate(50); // or keep $this->pageSize and update default
```

### Add New Database Field
1. Create migration: `php artisan make:migration add_field_to_extension_programs_table`
2. Add column to migration
3. Update `$fillable` array in model
4. Update form in Livewire component
5. Run: `php artisan migrate`

## Debugging

### View Generated SQL
```bash
php artisan tinker
DB::enableQueryLog();
// Run your query
DB::getQueryLog();
```

### Inspect Livewire Data
```php
// In component
$this->dispatch('notify', 'Data: ' . json_encode($this->data));

// Or use dd()
dd($this->programs, $this->related_communities);
```

### Check API Response
```bash
# With verbose info
curl -v -X GET http://localhost:8000/api/v1/extension-programs \
  -H "Authorization: Bearer $TOKEN"

# Pretty print with jq
curl -X GET http://localhost:8000/api/v1/extension-programs \
  -H "Authorization: Bearer $TOKEN" | jq '.'
```

### Database Logs
```bash
# View logs
tail -f storage/logs/laravel.log

# Clear logs
php artisan logs:clear
```

## Performance Tips

### 1. Enable Query Caching
```php
// In controller
$programs = Cache::remember('programs_list', 300, function () {
    return ExtensionProgram::active()->paginate(20);
});
```

### 2. Use Eager Loading
```php
// Bad
ExtensionProgram::all();

// Good
ExtensionProgram::with('creator', 'updater')->get();
```

### 3. Index Important Columns
Already done in migration, but review:
```sql
SHOW INDEX FROM extension_programs;
```

### 4. Monitor Database
```bash
# Check slow query log
tail -f /var/log/mysql/slow.log

# Or in Laravel
php artisan tinker
DB::listen(fn($query) => var_dump($query->time, $query->sql));
```

## Useful Artisan Commands

```bash
# Make new model with migration
php artisan make:model ModelName -m

# Make controller with model binding
php artisan make:controller ControllerName --model=ModelName --api

# Make Livewire component
php artisan make:livewire ComponentName

# List routes
php artisan route:list | grep extension-programs

# Clear everything
php artisan optimize:clear

# Generate API documentation
php artisan scribe:generate
```

## Environment Variables

```env
# Database
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=smartcemes
DB_USERNAME=root
DB_PASSWORD=

# API
API_PREFIX=/api/v1

# Mail (for notifications)
MAIL_FROM_ADDRESS=noreply@smartcemes.local
MAIL_FROM_NAME="SmartCEMES"

# Cache
CACHE_DRIVER=redis
REDIS_HOST=localhost
REDIS_PORT=6379
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| 404 on API endpoints | Check route cache: `php artisan route:clear` |
| Livewire component not loading | Check wire:key attribute in view |
| File upload fails | Check `storage/app/public` permissions |
| Database connection error | Verify `DB_*` in `.env` |
| Authentication error | Check Sanctum token: `php artisan tinker -> DB::table('personal_access_tokens')->get()` |
| Validation fails | Check model `$fillable` and request validation rules |
| Image not showing | Ensure symlink: `php artisan storage:link` |

## Resources

- **Laravel Documentation:** https://laravel.com/docs
- **Livewire Documentation:** https://livewire.laravel.com
- **Laravel Sanctum:** https://laravel.com/docs/sanctum
- **API_DOCUMENTATION.md:** See API reference
- **IMPLEMENTATION_TESTING.md:** See test procedures
- **IMPLEMENTATION_SUMMARY.md:** See detailed implementation

---

**Last Updated:** March 2025
**Version:** 1.0.0
