# REST API Architecture & Endpoint Specifications

## 1. Public Inquiry Endpoints

### `POST /api/enquiries`
- **Access:** Public (Protected by CSRF Token & Rate Limiter)
- **Request Body (JSON):**
  ```json
  {
    "csrf_token": "a8f3b2c1...",
    "full_name": "Vikram Singhania",
    "email": "vikram@example.com",
    "phone": "+919820012345",
    "country": "India",
    "travel_dates": "15 Nov 2026 - 18 Nov 2026",
    "adults": 2,
    "children": 1,
    "interests": ["leopard", "photography", "rabari"],
    "accommodation": "luxury_camp",
    "notes": "Interested in dedicated private 4x4 with beanbag mounts."
  }
  ```
- **Responses:**
  - `201 Created`: `{"success": true, "enquiry_code": "COJ-2026-0814", "message": "Inquiry received. An Expedition Captain will reach out within 12 hours."}`
  - `400 Bad Request`: `{"success": false, "errors": {"phone": "Please provide a valid contact number."}}`
  - `429 Too Many Requests`: `{"success": false, "message": "Rate limit exceeded. Please wait before submitting again."}`

---

## 2. Protected Admin CRM Endpoints (Requires Session Auth)

### `GET /api/admin/enquiries`
- **Query Params:** `status`, `assigned_to`, `page`, `search`
- **Returns:** Paginated array of inquiry records.

### `PATCH /api/admin/enquiries/:id`
- **Body:** `{"status": "qualified", "follow_up_date": "2026-10-15", "assigned_user_id": 2}`

### `POST /api/admin/enquiries/:id/notes`
- **Body:** `{"note_content": "Spoke via WhatsApp. Guest prefers sunrise drives at Sena."}`
