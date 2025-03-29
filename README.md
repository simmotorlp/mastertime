# MasterTime API Development Setup Guide

This guide will help you set up your local development environment for working with the MasterTime API. It includes tools configuration, installation of required packages, and initial API testing.

## Architecture Overview

The MasterTime API follows an API-first architecture with the following components:

- **RESTful API** built with Laravel
- **Sanctum** for API authentication
- **PostgreSQL** for relational data with spatial features for location-based queries
- **Resource-based design** with proper use of HTTP methods
- **Versioning** via URL path prefixing (/api/v1/...)
- **Multi-language support** built into resources and database schema

## Prerequisites

- Docker and Docker Compose installed
- Git installed
- PhpStorm or your preferred IDE
- Postman or Insomnia for API testing

## Step 1: Clone the Repository and Start the Environment

```bash
# Clone the repository
git clone https://github.com/simmotorlp/mastertime.git
cd mastertime

# Start the Docker environment
make up

# Install backend dependencies
make backend-composer-install

# Run database migrations
make backend-migrate

# Seed the database with test data
make backend-seed
```

## Step 2: Install Laravel Telescope for API Debugging

Laravel Telescope provides a beautiful dashboard for monitoring your API requests, database queries, and more.

```bash
# Access the backend container shell
make backend

# Install Telescope
composer require laravel/telescope --dev

# Publish Telescope assets
php artisan telescope:install

# Create Telescope database tables
php artisan migrate
```

Copy the telescope configuration file from this document to `backend/config/telescope.php`.

## Step 3: Configure PhpStorm for API Development

### 1. Install the Laravel IDE Helper

```bash
# Access the backend container
make backend

# Install Laravel IDE Helper
composer require --dev barryvdh/laravel-ide-helper

# Generate IDE helper files
php artisan ide-helper:generate
php artisan ide-helper:models -N
php artisan ide-helper:meta
```

### 2. Configure PhpStorm HTTP Client

Create a new directory and file:

```
backend/.idea/httpRequests/http-client.env.json
```

Add the following content:

```json
{
  "dev": {
    "base_url": "http://localhost/api",
    "token": "Your-Token-After-Login"
  }
}
```

Now you can create HTTP request files (.http) in your project:

```
### Login
POST {{base_url}}/v1/auth/login
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "password123"
}

### Get current user
GET {{base_url}}/v1/auth/me
Accept: application/json
Authorization: Bearer {{token}}
```

## Step 4: Import Postman Collection

1. Open Postman
2. Click on "Import" button
3. Choose "Raw text" and paste the Postman collection JSON from this document
4. Click "Import"
5. Create a new environment with variable `base_url` set to `http://localhost/api`

### Postman Environment Setup

Create a Postman environment to store your variables:

1. Click the "Environments" tab in Postman
2. Create a new environment called "MasterTime Local"
3. Add the following variables:
    - `base_url`: `http://localhost/api`
    - `token`: Leave this empty (it will be auto-populated after login)
    - `lang`: `uk` (or `ru` for Russian)

4. Configure automatic token extraction:

In the Login request, add the following Test script:

```javascript
var jsonData = pm.response.json();
if (jsonData && jsonData.data && jsonData.data.token) {
    pm.environment.set('token', jsonData.data.token);
    console.log("Token saved to environment");
}
```

This will automatically extract and save the authentication token when you log in.

## Step 5: API Development Workflow

Follow this workflow for developing new API endpoints:

1. Create a migration for any new tables needed
   ```bash
   make backend-make-migration create_table_name_table
   ```

2. Create a model
   ```bash
   make backend-make-model ModelName
   ```

3. Create request validation classes
   ```bash
   make backend-make-request StoreModelNameRequest
   ```

4. Create API resources
   ```bash
   make backend-make-resource ModelNameResource
   ```

5. Create controller
   ```bash
   make backend-make-controller Api/V1/ModelNameController --api
   ```

6. Add routes in `backend/routes/api.php`

7. Test with Postman or PhpStorm HTTP Client

### PhpStorm API Development Tips

For optimal API development in PhpStorm:

1. **REST Client Generation**:
    - Right-click on a controller method
    - Select "Copy Reference"
    - Open an HTTP request file (.http)
    - Press Ctrl+V to paste the endpoint

2. **Database Tool Integration**:
    - Set up a connection to your PostgreSQL database via Database tool
    - Create automatic completion for SQL queries
    - Generate database diagrams for entity relationships

3. **Live Templates for API Controllers**:
   Create a Live Template for controller methods:

   ```php
   /**
    * $DESCRIPTION$
    *
    * @group $GROUP$
    * @$HTTP_METHOD$ $ENDPOINT$
    * @$PARAM_TYPE$ $PARAM_NAME$ $PARAM_TYPE$ $PARAM_DESCRIPTION$ Example: $EXAMPLE$
    */
   public function $METHOD_NAME$(Request $request$OTHER_PARAMS$)
   {
       $END$
   }
   ```

4. **Method Completion**:
   For quicker development of API controllers, set up method completion templates:

   ```php
   // Basic index method with pagination and filters
   public function index(Request $request)
   {
       $query = Model::query();
       
       // Apply filters
       if ($request->has('search')) {
           $query->where('name', 'like', "%{$request->search}%");
       }
       
       $items = $query->paginate($request->per_page ?: 15);
       
       return $this->respondSuccess(
           ModelResource::collection($items)
       );
   }
   ```

## Step 6: API Testing Best Practices

1. Use Laravel's built-in testing tools for API testing
   ```bash
   make backend-make-test Api/ModelNameTest
   ```

2. Create test data with factories
   ```bash
   make backend-make-factory ModelNameFactory
   ```

3. Run tests
   ```bash
   make backend-test
   ```

## API Documentation

For API documentation, you can install Laravel Scribe:

```bash
# Access the backend container
make backend

# Install Laravel Scribe
composer require --dev knuckleswtf/scribe

# Publish configuration
php artisan vendor:publish --provider="Knuckleswtf\Scribe\ScribeServiceProvider" --tag="config"

# Generate documentation
php artisan scribe:generate
```

Then access the documentation at `http://localhost/docs`.

### Documenting API Endpoints

Add these annotations to your controller methods to generate comprehensive API documentation:

```php
/**
 * List all active salons
 * 
 * Returns a paginated list of active salons with optional filtering by location, services, etc.
 *
 * @group Salons
 * @queryParam search string Search by salon name or description. Example: beauty
 * @queryParam city string Filter by city. Example: Kyiv
 * @queryParam latitude float Latitude coordinate for location-based search. Example: 50.4501
 * @queryParam longitude float Longitude coordinate for location-based search. Example: 30.5234
 * @queryParam radius integer Radius in kilometers for location-based search. Example: 5
 * @queryParam category_id integer Filter by service category ID. Example: 1
 * @queryParam with string Comma-separated list of relationships to include (services,specialists,reviews). Example: services,specialists
 * @queryParam sort string Field to sort by (name,rating,reviews,newest,popular). Example: rating
 * @queryParam per_page integer Number of results per page. Example: 15
 * @queryParam page integer Page number. Example: 1
 * @response {
 *   "status": "success",
 *   "message": "Operation successful",
 *   "data": [
 *     {
 *       "id": 1,
 *       "name": "Beauty Salon",
 *       "slug": "beauty-salon",
 *       "description": "A beautiful salon for all your beauty needs",
 *       // ... other salon fields
 *     }
 *   ],
 *   "meta": {
 *     "total": 50,
 *     "per_page": 15,
 *     "current_page": 1,
 *     "last_page": 4
 *   }
 * }
 */
public function index(Request $request)
{
    // Implementation
}
```

## Advanced API Concepts

### 1. API Response Caching

For performance optimization, implement caching for your API responses:

```php
// In a controller method
public function index(Request $request)
{
    $cacheKey = 'salons:' . md5($request->fullUrl());
    
    return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($request) {
        // Your query logic here
        return $this->respondSuccess(
            SalonResource::collection($items)
        );
    });
}
```

### 2. API Resource Optimization

When dealing with large collections, optimize your resources:

```php
// In your AppServiceProvider
public function boot()
{
    JsonResource::withoutWrapping();
}

// For collection pagination efficiency
SalonResource::collection($salons)->additional([
    'meta' => [
        'total' => $salons->total(),
        'per_page' => $salons->perPage(),
        'current_page' => $salons->currentPage(),
        'last_page' => $salons->lastPage()
    ]
]);
```

### 3. Location-Based Queries

For salon searching by proximity:

```php
// In your SalonController
public function nearby(Request $request)
{
    $lat = $request->latitude;
    $lng = $request->longitude;
    $radius = $request->radius ?: 5; // Default 5km radius
    
    $salons = Salon::select('*')
        ->selectRaw("
            earth_distance(
                ll_to_earth($lat, $lng),
                ll_to_earth(latitude, longitude)
            ) as distance
        ")
        ->whereRaw("
            earth_box(
                ll_to_earth($lat, $lng),
                $radius * 1000
            ) @> ll_to_earth(latitude, longitude)
        ")
        ->whereRaw("
            earth_distance(
                ll_to_earth($lat, $lng),
                ll_to_earth(latitude, longitude)
            ) < $radius * 1000
        ")
        ->orderBy('distance')
        ->paginate($request->per_page ?: 15);
        
    return $this->respondSuccess(
        SalonResource::collection($salons)
    );
}
```

## Troubleshooting

### API Routes Not Working

- Check if your route is registered in `backend/routes/api.php`
- Run `php artisan route:list` to verify the route exists
- Ensure you're using the correct HTTP method (GET, POST, PUT, DELETE)
- Check if middleware is blocking the request (auth, throttle, etc.)
- Verify the route parameters match (check for typos in URLs)
- Check if your route is in the correct order (more specific routes should come before catch-all routes)

### Database Connection Issues

- Verify PostgreSQL container is running: `docker ps`
- Check database credentials in `.env`
- Run `php artisan db:monitor` to check the connection
- Verify PostgreSQL extensions are installed:
  ```sql
  SELECT * FROM pg_extension;
  ```
- Ensure the database user has proper permissions:
  ```sql
  GRANT ALL PRIVILEGES ON DATABASE mastertime TO mastertime_user;
  ```

### CORS Issues When Testing from Frontend

- Ensure proper CORS headers are enabled in `cors.php` configuration
- Check if the correct domains are whitelisted
- Set Access-Control-Allow-Origin correctly:
  ```php
  'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:5173')],
  ```
- Add all required methods to the allowed methods:
  ```php
  'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
  ```

### File Upload Issues

- Check the `upload_max_filesize` and `post_max_size` in `php.ini`
- Verify the storage directory is writable:
  ```bash
  chmod -R 775 storage bootstrap/cache
  chown -R www-data:www-data storage bootstrap/cache
  ```
- Make sure you've set up a symbolic link for storage:
  ```bash
  php artisan storage:link
  ```

### Authentication Problems

- Check token expiration (default is 24 hours)
- Verify the token is being sent correctly in the Authorization header
- Make sure CORS is properly configured for preflight requests

## Multi-language Support

The MasterTime API is designed to support both Ukrainian and Russian languages. Here's how to implement the language support:

### Database Schema

The database schema includes language-specific fields with suffixes:
- `name_ru` - Russian name (with default `name` being Ukrainian)
- `description_ru` - Russian description
- `bio_ru` - Russian bio for specialists
- etc.

### API Responses

The API checks the user's preferred language setting and returns the appropriate content:

```php
// In API Resources
public function toArray(Request $request): array
{
    // Determine the user's preferred language
    $user = Auth::user();
    $useRussian = $user && $user->language === 'ru';
    
    return [
        'name' => $this->name,
        'description' => $useRussian && $this->description_ru ? $this->description_ru : $this->description,
        // Other fields
    ];
}
```

### Language Selection

Clients can update their language preference through the user profile endpoint:

```
PUT /api/v1/users/profile
{
    "language": "ru"  // or "uk" for Ukrainian
}
```

You can also add an `Accept-Language` header to your API requests to override the user's stored preference temporarily.

## API Security Best Practices

When developing the MasterTime API, follow these security best practices:

1. **Input Validation**: Use Laravel's form request validation for all inputs
2. **Rate Limiting**: Apply rate limiting to prevent abuse:
   ```php
   Route::middleware('throttle:60,1')->group(function () {
       // API routes
   });
   ```
3. **CORS Configuration**: Configure CORS to only allow specific origins
4. **Secure Headers**: Use secure headers (HTTPS, Content-Security-Policy)
5. **Token Management**: Implement token expiration and refresh mechanisms
6. **Authorization Checks**: Always verify permissions before performing actions

## Additional Resources

- Laravel Sanctum Documentation: https://laravel.com/docs/10.x/sanctum
- Laravel API Resources: https://laravel.com/docs/10.x/eloquent-resources
- Postman Learning Center: https://learning.postman.com/docs/getting-started/introduction/
- PhpStorm HTTP Client: https://www.jetbrains.com/help/phpstorm/http-client-in-product-code-editor.html
- PostgreSQL PostGIS: https://postgis.net/documentation/
- Laravel Filament (for Admin Panel): https://filamentphp.com/docs

# MasterTime API Patterns and Best Practices

This document outlines the key patterns and best practices to follow when developing the MasterTime API. Following these guidelines will ensure consistency, maintainability, and scalability.

## API Resource Naming

Use resource-oriented naming conventions for your API endpoints:

| Action | HTTP Method | URL Pattern | Description |
|--------|-------------|-------------|-------------|
| List | GET | `/api/v1/resources` | Get a list of resources |
| Create | POST | `/api/v1/resources` | Create a new resource |
| Read | GET | `/api/v1/resources/{id}` | Get a specific resource |
| Update | PUT | `/api/v1/resources/{id}` | Update a specific resource |
| Delete | DELETE | `/api/v1/resources/{id}` | Delete a specific resource |

For nested resources, use:

| Action | HTTP Method | URL Pattern | Description |
|--------|-------------|-------------|-------------|
| List Nested | GET | `/api/v1/resources/{id}/sub-resources` | Get sub-resources for a resource |
| Create Nested | POST | `/api/v1/resources/{id}/sub-resources` | Create a sub-resource for a resource |

## Standard Response Format

All API endpoints should return responses in a consistent format:

```json
{
  "status": "success",
  "message": "Operation successful",
  "data": { ... }
}
```

For errors:

```json
{
  "status": "error",
  "message": "Error message",
  "errors": { ... }
}
```

## Status Codes

Use appropriate HTTP status codes:

| Code | Description | Usage |
|------|-------------|-------|
| 200 | OK | Successful GET, PUT/PATCH |
| 201 | Created | Successful POST that creates a resource |
| 204 | No Content | Successful DELETE |
| 400 | Bad Request | Invalid input, validation errors |
| 401 | Unauthorized | Missing or invalid authentication |
| 403 | Forbidden | Authentication succeeded but insufficient permissions |
| 404 | Not Found | Resource not found |
| 422 | Unprocessable Entity | Validation errors |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Server Error | Server error |

## Input Validation

Use Form Request classes for validation:

```php
class StoreSalonRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // More rules...
        ];
    }
}
```

Benefits:
- Keeps controllers clean
- Centralizes validation logic
- Simplifies error handling

## Filtering, Sorting, and Pagination

Implement consistent query parameters for filtering, sorting, and pagination:

| Parameter | Description | Example |
|-----------|-------------|---------|
| `filter[field]` | Filter by field | `filter[city]=Kyiv` |
| `sort` | Sort by field (prefix with `-` for descending) | `sort=-created_at` |
| `per_page` | Results per page | `per_page=15` |
| `page` | Page number | `page=2` |

Example implementation:

```php
public function index(Request $request)
{
    $query = Model::query();
    
    // Apply filters
    if ($request->has('filter')) {
        foreach ($request->filter as $field => $value) {
            if (in_array($field, $this->allowedFilters)) {
                $query->where($field, $value);
            }
        }
    }
    
    // Apply sorting
    if ($request->has('sort')) {
        $sortField = $request->sort;
        $direction = 'asc';
        
        if (strpos($sortField, '-') === 0) {
            $direction = 'desc';
            $sortField = substr($sortField, 1);
        }
        
        if (in_array($sortField, $this->allowedSorts)) {
            $query->orderBy($sortField, $direction);
        }
    }
    
    // Apply pagination
    $perPage = $request->per_page ?: 15;
    $results = $query->paginate($perPage);
    
    return $this->respondSuccess(
        ResourceClass::collection($results)
    );
}
```

## API Versioning

Use URL-based versioning (e.g., `/api/v1/`, `/api/v2/`):

```php
// In routes/api.php
Route::prefix('v1')->group(function () {
    // V1 routes
});

Route::prefix('v2')->group(function () {
    // V2 routes
});
```

Benefits:
- Explicit and easy to understand
- Allows for multiple active versions
- Simple to implement

## API Rate Limiting

Implement rate limiting to prevent abuse:

```php
// In RouteServiceProvider
Route::middleware(['api', 'throttle:60,1'])->prefix('api')->group(function () {
    require base_path('routes/api.php');
});

// Or for specific routes
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
});
```

## API Documentation with Annotations

Use detailed annotations for API documentation:

```php
/**
 * Create a new salon
 *
 * @group Salons
 * @bodyParam name string required The name of the salon. Example: Beauty Salon
 * @bodyParam description string The salon description. Example: A beautiful salon for all your beauty needs
 * @bodyParam phone string required The salon phone number. Example: +380987654321
 * @bodyParam address string required The salon address. Example: 123 Main St
 * @bodyParam city string required The salon city. Example: Kyiv
 * @bodyParam latitude numeric The latitude coordinate. Example: 50.4501
 * @bodyParam longitude numeric The longitude coordinate. Example: 30.5234
 * @responseFile status=201 responses/salon/created.json
 * @responseFile status=422 responses/validation_error.json
 */
public function store(StoreSalonRequest $request)
{
    // Implementation...
}
```

## Resource Inclusions (Relationships)

Allow API clients to specify which relationships to include:

```php
public function show(Request $request, $id)
{
    $salon = Salon::findOrFail($id);
    
    if ($request->has('with')) {
        $allowedIncludes = ['services', 'specialists', 'reviews'];
        $requestedIncludes = explode(',', $request->with);
        
        foreach ($requestedIncludes as $include) {
            if (in_array($include, $allowedIncludes)) {
                $salon->load($include);
            }
        }
    }
    
    return $this->respondSuccess(new SalonResource($salon));
}
```

## Field Selection

Allow clients to request only specific fields:

```php
public function index(Request $request)
{
    $query = Model::query();
    
    // Apply field selection if requested
    if ($request->has('fields')) {
        $fields = explode(',', $request->fields);
        $allowedFields = ['id', 'name', 'created_at']; // Define allowed fields
        
        $selectedFields = array_intersect($fields, $allowedFields);
        if (!empty($selectedFields)) {
            $query->select($selectedFields);
        }
    }
    
    $results = $query->paginate();
    
    return $this->respondSuccess(
        ResourceClass::collection($results)
    );
}
```

## Soft Deletes and Resource States

Use soft deletes for entities instead of permanent deletion:

```php
// In your model
use SoftDeletes;

// In your controller
public function destroy($id)
{
    $model = Model::findOrFail($id);
    $model->delete();
    
    return $this->respondSuccess(null, 'Resource deleted successfully');
}
```

For entities like salons, use an `is_active` flag to control visibility without deleting the record.

## Testing API Endpoints

Create comprehensive tests for each endpoint:

```php
class SalonApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_active_salons()
    {
        // Create test salons
        $activeSalon = Salon::factory()->create(['is_active' => true]);
        $inactiveSalon = Salon::factory()->create(['is_active' => false]);
        
        // Test the endpoint
        $response = $this->getJson('/api/v1/salons');
        
        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.id', $activeSalon->id);
    }
    
    /** @test */
    public function it_can_create_a_salon()
    {
        $user = User::factory()->create(['role' => 'salon_owner']);
        
        Sanctum::actingAs($user);
        
        $data = [
            'name' => 'Test Salon',
            'phone' => '+380123456789',
            'address' => 'Test Address',
            'city' => 'Kyiv'
        ];
        
        $response = $this->postJson('/api/v1/salons', $data);
        
        $response->assertStatus(201)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonPath('data.name', 'Test Salon');
                 
        $this->assertDatabaseHas('salons', [
            'name' => 'Test Salon',
            'owner_id' => $user->id
        ]);
    }
}
```