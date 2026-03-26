# SmartCEMES API Reference Documentation

## Table of Contents
1. [Overview](#overview)
2. [Authentication](#authentication)
3. [Extension Programs API](#extension-programs-api)
4. [Communities API](#communities-api)
5. [Users API](#users-api)
6. [Response Format](#response-format)
7. [Error Handling](#error-handling)
8. [Rate Limiting](#rate-limiting)

## Overview

The SmartCEMES API provides RESTful endpoints for managing community extension programs, communities, and user data. All endpoints require authentication and return JSON responses.

### Base URL
```
http://smartcemes.local/api/v1
```

### API Version
Current version: **1.0.0**

## Authentication

All API requests must include an authentication token in the Authorization header.

### Bearer Token Authentication
```http
Authorization: Bearer {token}
```

### Obtaining a Token
```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "admin"
    }
  }
}
```

## Extension Programs API

### Endpoints

#### List All Programs
```http
GET /api/v1/extension-programs?page=1&per_page=20&status=active&search=query
```

**Query Parameters:**
- `page` (integer, default: 1): Page number
- `per_page` (integer, default: 20): Results per page
- `status` (string): Filter by status (active, inactive, planning, completed)
- `search` (string): Search by title or description

**Response:**
```json
{
  "status": "success",
  "data": {
    "items": [
      {
        "id": 1,
        "title": "Sustainable Agriculture Program",
        "description": "Teaching sustainable farming practices...",
        "goals": "Increase crop yields sustainably",
        "objectives": ["Educate farmers", "Reduce pesticide use"],
        "status": "active",
        "cover_image_url": "http://smartcemes.local/storage/programs/covers/...",
        "related_communities": [1, 2, 3],
        "created_at": "2025-01-15T10:30:00Z",
        "updated_at": "2025-03-10T14:20:00Z"
      }
    ],
    "pagination": {
      "total": 50,
      "per_page": 20,
      "current_page": 1,
      "last_page": 3
    }
  }
}
```

#### Get Program Details
```http
GET /api/v1/extension-programs/{id}
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "title": "Sustainable Agriculture Program",
    "description": "Teaching sustainable farming practices...",
    "goals": "Increase crop yields sustainably",
    "objectives": ["Educate farmers", "Reduce pesticide use"],
    "status": "active",
    "cover_image_url": "http://smartcemes.local/storage/programs/covers/...",
    "gallery_images": [
      "http://smartcemes.local/storage/programs/gallery/..."
    ],
    "related_communities": [
      {
        "id": 1,
        "name": "San Juan Community",
        "code": "SJC001"
      }
    ],
    "activities": ["Training session", "Demonstration farming"],
    "attachments": [
      {
        "name": "guidelines.pdf",
        "url": "http://smartcemes.local/storage/programs/attachments/..."
      }
    ],
    "notes": "High priority program",
    "created_by": {
      "id": 1,
      "name": "Admin User"
    },
    "updated_by": {
      "id": 1,
      "name": "Admin User"
    },
    "created_at": "2025-01-15T10:30:00Z",
    "updated_at": "2025-03-10T14:20:00Z"
  }
}
```

#### Create Program
```http
POST /api/v1/extension-programs
Content-Type: application/json

{
  "title": "Sustainable Agriculture Program",
  "description": "Teaching sustainable farming practices...",
  "goals": "Increase crop yields sustainably",
  "objectives": ["Educate farmers", "Reduce pesticide use"],
  "status": "active",
  "related_communities": [1, 2, 3],
  "notes": "High priority program"
}
```

**Response:** (201 Created)
```json
{
  "status": "success",
  "message": "Extension program created successfully",
  "data": {
    "id": 1,
    "title": "Sustainable Agriculture Program",
    ...
  }
}
```

#### Update Program
```http
PUT /api/v1/extension-programs/{id}
Content-Type: application/json

{
  "title": "Sustainable Agriculture Program (Updated)",
  "description": "Teaching sustainable farming practices...",
  "status": "active"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Extension program updated successfully",
  "data": { ... }
}
```

#### Delete Program
```http
DELETE /api/v1/extension-programs/{id}
```

**Response:**
```json
{
  "status": "success",
  "message": "Extension program deleted successfully"
}
```

#### Upload Cover Image
```http
POST /api/v1/extension-programs/{id}/cover-image
Content-Type: multipart/form-data

{
  "cover_image": <file>
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Cover image uploaded successfully",
  "data": {
    "url": "http://smartcemes.local/storage/programs/covers/..."
  }
}
```

#### Bulk Update Status
```http
PATCH /api/v1/extension-programs/bulk-status
Content-Type: application/json

{
  "ids": [1, 2, 3],
  "status": "active"
}
```

## Communities API

### Endpoints

#### List All Communities
```http
GET /api/v1/communities?page=1&per_page=20&status=active
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "items": [
      {
        "id": 1,
        "name": "San Juan Community",
        "code": "SJC001",
        "location": "Metro Manila",
        "status": "active",
        "created_at": "2025-01-10T08:00:00Z"
      }
    ],
    "pagination": { ... }
  }
}
```

#### Get Community Details
```http
GET /api/v1/communities/{id}
```

#### Create Community
```http
POST /api/v1/communities
Content-Type: application/json

{
  "name": "San Juan Community",
  "code": "SJC001",
  "location": "Metro Manila",
  "status": "active"
}
```

#### Update Community
```http
PUT /api/v1/communities/{id}
Content-Type: application/json

{
  "name": "San Juan Community Updated",
  "status": "active"
}
```

#### Delete Community
```http
DELETE /api/v1/communities/{id}
```

## Users API

### Endpoints

#### List All Users
```http
GET /api/v1/users?page=1&per_page=20&role=admin
```

**Query Parameters:**
- `role` (string): Filter by role (admin, secretary, viewer)
- `status` (string): Filter by status (active, inactive)

**Response:**
```json
{
  "status": "success",
  "data": {
    "items": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "admin",
        "status": "active",
        "created_at": "2025-01-01T00:00:00Z"
      }
    ],
    "pagination": { ... }
  }
}
```

#### Get Current User
```http
GET /api/v1/users/me
```

#### Create User
```http
POST /api/v1/users
Content-Type: application/json

{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "securepassword123",
  "role": "secretary",
  "status": "active"
}
```

#### Update User
```http
PUT /api/v1/users/{id}
Content-Type: application/json

{
  "name": "Jane Doe Updated",
  "role": "secretary"
}
```

#### Delete User
```http
DELETE /api/v1/users/{id}
```

## Response Format

### Success Response
```json
{
  "status": "success",
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "status": "error",
  "message": "Error message",
  "errors": {
    "field_name": ["Error description"]
  }
}
```

### Common Status Codes
- `200 OK`: Request successful
- `201 Created`: Resource created successfully
- `204 No Content`: Request successful, no content to return
- `400 Bad Request`: Invalid request parameters
- `401 Unauthorized`: Authentication failed or token expired
- `403 Forbidden`: Access denied
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation failed
- `500 Internal Server Error`: Server error

## Error Handling

### Validation Errors
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required"],
    "description": ["The description must be at least 10 characters"]
  }
}
```

### Authentication Errors
```json
{
  "status": "error",
  "message": "Unauthorized",
  "errors": {
    "token": ["Invalid or expired token"]
  }
}
```

### Not Found Error
```json
{
  "status": "error",
  "message": "Resource not found",
  "errors": {
    "resource": ["Extension program with ID 999 not found"]
  }
}
```

## Rate Limiting

API requests are rate limited to prevent abuse:
- **Per Minute**: 60 requests per minute per IP
- **Per Hour**: 1000 requests per hour per IP

Rate limit headers are included in responses:
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1234567890
```

### Rate Limit Exceeded
```json
{
  "status": "error",
  "message": "Rate limit exceeded",
  "retry_after": 60
}
```

---

## Usage Examples

### Example 1: Create an Extension Program
```bash
curl -X POST http://smartcemes.local/api/v1/extension-programs \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Community Health Program",
    "description": "Promoting health awareness in communities",
    "status": "active",
    "related_communities": [1, 2],
    "goals": "Improve health outcomes"
  }'
```

### Example 2: Update Program Status
```bash
curl -X PUT http://smartcemes.local/api/v1/extension-programs/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "completed"
  }'
```

### Example 3: Search Programs
```bash
curl -X GET "http://smartcemes.local/api/v1/extension-programs?search=agriculture&status=active" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Support and Contact

For API support and questions, contact the development team at:
- Email: api-support@smartcemes.local
- Documentation: http://smartcemes.local/api/docs
