# Gateway Loss Records API Documentation

## Base URL
\\\
http://127.0.0.1:8000/api
\\\

## Endpoints

### 1. GET All Records
\\\http
GET /api/gateway-loss-records
GET /api/gateway-loss-records?provider=OpenWeather
\\\

**Response:**
\\\json
[
  {
    "id": 1,
    "provider": "OpenWeather",
    "endpoint": "/data/2.5/weather",
    "response_status": 200,
    "request_payload": {"city": "Manila"},
    "error_message": null,
    "created_at": "2026-05-09 10:00:00",
    "updated_at": "2026-05-09 10:00:00"
  }
]
\\\

### 2. POST Create Record
\\\http
POST /api/gateway-loss-records
Content-Type: application/json

{
  "provider": "OpenWeather",
  "endpoint": "/data/2.5/weather",
  "response_status": 200,
  "request_payload": {"city": "Manila"},
  "error_message": null
}
\\\

**Response:**
\\\json
{
  "success": true,
  "id": 1,
  "message": "Record added successfully"
}
\\\

### 3. GET Single Record
\\\http
GET /api/gateway-loss-records/{id}
\\\

### 4. PUT Update Record
\\\http
PUT /api/gateway-loss-records/{id}
Content-Type: application/json

{
  "provider": "Updated Provider",
  "response_status": 404
}
\\\

### 5. DELETE Record
\\\http
DELETE /api/gateway-loss-records/{id}
\\\

## Error Responses

| Status | Response |
|--------|----------|
| 404 | {"error": "Record not found"} |
| 422 | {"error": "Provider is required"} |
| 500 | {"error": "Server error message"} |

## Testing with cURL

\\\ash
# GET all records
curl http://127.0.0.1:8000/api/gateway-loss-records

# POST new record
curl -X POST http://127.0.0.1:8000/api/gateway-loss-records \\
  -H "Content-Type: application/json" \\
  -d '{"provider":"Test","endpoint":"/test"}'

# GET single record
curl http://127.0.0.1:8000/api/gateway-loss-records/1

# UPDATE record
curl -X PUT http://127.0.0.1:8000/api/gateway-loss-records/1 \\
  -H "Content-Type: application/json" \\
  -d '{"provider":"Updated"}'

# DELETE record
curl -X DELETE http://127.0.0.1:8000/api/gateway-loss-records/1
\\\

---
**Documentation Version:** 1.0
**Last Updated:** 2026-05-12
**Maintainer:** Arwin Ambag
