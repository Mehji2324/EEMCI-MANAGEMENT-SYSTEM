# EEMCI Platform API Reference

## Authentication
All endpoints (except login/register) require a Bearer token.
`Authorization: Bearer <token>`

### POST `/api/auth/login`
**Description**: Authenticate and retrieve token.
**Body**:
```json
{
  "email": "admin@eemci.edu",
  "password": "password123"
}
```

## Students

### GET `/api/students`
**Role**: Admin
**Query Params**: `status`, `filiere_id`, `classe_id`, `per_page`
**Description**: List all students with pagination.

### POST `/api/students/{id}/validate`
**Role**: Admin
**Description**: Validate a pending student registration. Generates matricule and academic email.

## Notes & Absences

### POST `/api/notes`
**Role**: Admin, Teacher
**Description**: Submit CC and Exam notes for a student in a module.

### POST `/api/absences`
**Role**: Admin, Teacher
**Description**: Record student absence.

## Payments

### POST `/api/payments`
**Role**: Admin
**Description**: Record a new payment.

### GET `/api/payments`
**Role**: Admin, Student (own only)
**Description**: List payments.
