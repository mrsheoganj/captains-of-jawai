# Security Audit Logging & Compliance

- Every administrative action is immutably logged into `audit_logs` table:
  - `user_id` & `username`
  - `action_type` (e.g., `LEAD_STATUS_UPDATE`, `USER_LOGIN`, `PASSWORD_RESET`, `POST_PUBLISH`)
  - `record_id` & `table_name`
  - `ip_address` & `user_agent`
  - `timestamp`
  - `diff_payload` (JSON representation of old vs. new values).
