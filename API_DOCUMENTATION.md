# API Documentation

## Authentication

### Login
```
POST /login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password"
}
```

### Register
```
POST /register
Content-Type: application/json

{
    "name": "User Name",
    "email": "user@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

## Personal Info API

### Create Personal Info
```
POST /personal-info
Content-Type: multipart/form-data

{
    "title": "นาย",
    "first_name": "ชื่อ",
    "last_name": "นามสกุล",
    "age": 25,
    "gender": "ชาย",
    "faculty": "คณะวิทยาศาสตร์",
    "major": "วิทยาการคอมพิวเตอร์",
    "photo": [file]
}
```

### Get Personal Info
```
GET /personal-info/{id}
```

### Update Personal Info (User)
```
PUT /personal-info/{id}/edit-my
Content-Type: multipart/form-data

{
    "title": "นาย",
    "first_name": "ชื่อใหม่",
    "last_name": "นามสกุลใหม่",
    "age": 26,
    "gender": "ชาย",
    "faculty": "คณะวิทยาศาสตร์",
    "major": "วิทยาการคอมพิวเตอร์",
    "photo": [file]
}
```

### Approve Personal Info (Admin)
```
POST /personal-info/{id}/approve
```

### Reject Personal Info (Admin)
```
POST /personal-info/{id}/reject
Content-Type: application/json

{
    "rejection_reason": "เหตุผลในการไม่อนุมัติ"
}
```

## Documents API

### Create Document
```
POST /documents
Content-Type: multipart/form-data

{
    "title": "ชื่อเอกสาร",
    "content": "เนื้อหาเอกสาร",
    "file": [file]
}
```

### Get Document
```
GET /documents/{id}
```

### Download Document
```
GET /documents/{id}/download
```

## Portfolio API

### Get All Portfolios
```
GET /portfolios
```

### Get Portfolios by Faculty
```
GET /portfolios/faculty/{faculty}
```

### Get Portfolios by Major
```
GET /portfolios/major/{major}
```

### Search Portfolios
```
GET /portfolios/search?q=keyword&faculty=คณะ&major=สาขา
```

## Error Responses

### 400 Bad Request
```json
{
    "message": "Validation failed",
    "errors": {
        "field": ["Error message"]
    }
}
```

### 401 Unauthorized
```json
{
    "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
    "message": "Access denied. Admin privileges required."
}
```

### 404 Not Found
```json
{
    "message": "Resource not found"
}
```

### 500 Internal Server Error
```json
{
    "message": "Internal server error"
}
```

## Rate Limiting

- **Authentication endpoints**: 5 requests per minute
- **API endpoints**: 60 requests per minute
- **File uploads**: 10 requests per minute

## Pagination

API responses that return lists support pagination:

```json
{
    "data": [...],
    "current_page": 1,
    "per_page": 15,
    "total": 100,
    "last_page": 7
}
```

## File Upload

### Supported Formats
- **Images**: JPEG, PNG, JPG, GIF (max 2MB)
- **Documents**: PDF, DOC, DOCX (max 10MB)

### Upload Response
```json
{
    "success": true,
    "file_path": "storage/personal-photos/filename.jpg",
    "file_url": "http://example.com/storage/personal-photos/filename.jpg"
}
```
