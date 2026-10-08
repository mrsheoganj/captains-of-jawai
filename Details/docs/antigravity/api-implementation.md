# API Routing & Response Architecture

All API endpoints reside under `/api/`:
- Requests must include `Content-Type: application/json`.
- All mutation requests (`POST`, `PATCH`, `DELETE`) require a valid `X-CSRF-Token` header.
- Responses strictly return JSON with standard payload envelope:
  ```json
  {
    "success": true,
    "data": {},
    "message": "Operation completed successfully."
  }
  ```
- Errors return structured error objects:
  ```json
  {
    "success": false,
    "error_code": "VALIDATION_FAILED",
    "errors": {
      "email": "A valid email address is required."
    }
  }
  ```
