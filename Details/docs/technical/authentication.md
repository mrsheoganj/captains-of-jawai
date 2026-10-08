# Admin Authentication & Session Management

## 1. Security Protocol
- Passwords hashed using modern `password_hash($password, PASSWORD_ARGON2ID)`.
- Session tokens stored in encrypted server-side files; session cookies configured with:
  `Secure = true`, `HttpOnly = true`, `SameSite = Strict`.
- Brute-force protection: IP-based lockout after 5 consecutive failed login attempts within 15 minutes.
- Automatic session timeout after 30 minutes of administrative inactivity.
