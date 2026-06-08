# API Configuration

## Base URL

### Development
```
http://localhost:8000/api
```

### Production
```
https://your-domain.railway.app/api
```

## Authentication

Semua API endpoints (kecuali login & register) memerlukan Bearer token:

```bash
Authorization: Bearer {token}
```

## Endpoints

### Auth Routes
```
POST /auth/login          - Login user
POST /auth/register       - Register user
```

### Admin Routes
```
GET    /admin/dashboard   - Get dashboard data
GET    /admin/antrean     - List all queues
POST   /admin/antrean     - Create queue
GET    /admin/antrean/{id}        - Get queue detail
PATCH  /admin/antrean/{id}        - Update queue
DELETE /admin/antrean/{id}        - Delete queue

GET    /admin/laporan     - List reports
POST   /admin/laporan     - Create report
GET    /admin/laporan/{id}        - Get report detail
PATCH  /admin/laporan/{id}        - Update report
POST   /admin/laporan/{id}/verifikasi - Verify report

GET    /admin/perusahaan  - List companies
POST   /admin/perusahaan  - Create company
GET    /admin/perusahaan/{id}     - Get company detail
PATCH  /admin/perusahaan/{id}     - Update company
DELETE /admin/perusahaan/{id}     - Delete company

GET    /admin/users       - List users
POST   /admin/users       - Create user
GET    /admin/users/{id}  - Get user detail
PATCH  /admin/users/{id}  - Update user
DELETE /admin/users/{id}  - Delete user
```

### Operator Routes
```
GET    /operator/dashboard  - Get operator dashboard
GET    /operator/antrean    - List operator queues
PATCH  /operator/antrean/{id} - Update queue status

GET    /operator/laporan    - List operator reports
POST   /operator/laporan    - Create report
```

### Public Routes
```
POST /supir/validasi-barcode     - Validate vehicle barcode
POST /supir/daftar-antrean       - Register new queue
GET  /supir/status-antrean/{code} - Get queue status
GET  /supir/daftar-perusahaan    - List companies
GET  /supir/jenis-kendaraan      - List vehicle types
```

## Response Format

### Success Response
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Data example",
    "created_at": "2026-06-04T10:30:00Z"
  },
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "success": false,
  "error": "Validation failed",
  "message": "The given data was invalid",
  "errors": {
    "email": ["The email field is required"]
  }
}
```

### Pagination Response
```json
{
  "success": true,
  "data": [
    { "id": 1, "name": "Item 1" },
    { "id": 2, "name": "Item 2" }
  ],
  "pagination": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7,
    "from": 1,
    "to": 15
  }
}
```

## HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Request berhasil |
| 201 | Created - Resource berhasil dibuat |
| 204 | No Content - Request berhasil, tidak ada content |
| 400 | Bad Request - Request tidak valid |
| 401 | Unauthorized - Authentication diperlukan |
| 403 | Forbidden - Tidak ada permissions |
| 404 | Not Found - Resource tidak ditemukan |
| 422 | Unprocessable Entity - Validation error |
| 500 | Internal Server Error - Server error |

## Rate Limiting

API memiliki rate limiting:
- 60 requests per minute per user
- 1000 requests per hour per user

## CORS

CORS headers diperlukan untuk cross-origin requests:
```
Access-Control-Allow-Origin: https://frontend-domain.com
Access-Control-Allow-Methods: GET, POST, PATCH, DELETE
Access-Control-Allow-Headers: Authorization, Content-Type
```

## Error Handling

Semua error responses mengikuti format yang konsisten:

```json
{
  "success": false,
  "error": "Error code",
  "message": "Error description"
}
```

## Example Requests

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'
```

### Get Queue List
```bash
curl -X GET http://localhost:8000/api/admin/antrean \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json"
```

### Create Queue
```bash
curl -X POST http://localhost:8000/api/admin/antrean \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "nomor_antrean": "Q001",
    "kendaraan_id": 1,
    "status": "menunggu"
  }'
```

---

**Last Updated**: June 4, 2026
