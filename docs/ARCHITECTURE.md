# System Architecture

## Design Patterns

### Repository Pattern
Data access logic is isolated in repositories (`app/Repositories`). This decouples the business logic from Eloquent ORM.
- **Interfaces**: Define the contract (e.g., `StudentRepositoryInterface`).
- **Implementations**: Eloquent implementations (e.g., `EloquentStudentRepository`).
- **Binding**: Bound in `RepositoryServiceProvider`.

### Service Pattern
Business logic resides in Services (`app/Services`). Controllers remain thin and only handle HTTP requests and responses.
- `StudentService`: Handles complex student validation, delegating to `MatriculeService` and `AcademicEmailService`.

## Database Schema
- `users`: Core identity table.
- `roles`: RBAC definition (admin, teacher, student).
- `students`, `teachers`: Role-specific profile data linked to `users`.
- `filieres`, `classes`, `modules`: Academic hierarchy.
- `notes`, `absences`, `payments`: Transactional records.

## Request Flow
1. **Route** (`routes/api.php`)
2. **Global Middleware**: Security Headers, Input Sanitization.
3. **Route Middleware**: Sanctum Auth, Throttle, Role Middleware.
4. **Form Request**: Validates incoming data (e.g., `StoreStudentRequest`).
5. **Controller**: Calls appropriate Service.
6. **Service**: Contains business logic, queries via Repository.
7. **Repository**: Executes Eloquent query.
8. **Controller**: Formats output using `ApiResponse` helper.
