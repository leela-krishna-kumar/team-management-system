# Task Management System

## Overview
This Task Management System is a role-based access control (RBAC) application built using Laravel. It allows admins, managers, and members to manage teams, tasks, and assignments with features such as inline editing, dynamic AJAX functionality, and user-friendly modals for interaction.

## Features

### Role-Based Access Control (RBAC)
- **Admin**: Full control over teams, tasks, and user roles.
- **Manager**: Manage tasks and assign users within their teams.
- **Member**: View and update tasks assigned to them.

### Team Management
- List, create, edit, and delete teams dynamically using AJAX.
- View tasks associated with a selected team.

### Task Management
- CRUD operations for tasks with dynamic AJAX handling.
- Assign/unassign multiple users to tasks using a pivot table.
- Inline editing for task attributes like title, status, and due date.
- View assigned users in real time.

### User Management
- Assign roles to users dynamically.
- Edit and update user roles via modals.

## Installation

### Prerequisites
- PHP 8.1+
- Composer
- Laravel 11
- MySQL or PostgreSQL

### Steps
1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd task-management-system
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install && npm run dev
   ```

3. Configure `.env`:
   ```env
   APP_NAME=TaskManagementSystem
   APP_ENV=local
   APP_KEY=base64:randomGeneratedKey
   APP_DEBUG=true
   APP_URL=http://localhost

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_management
   DB_USERNAME=root
   DB_PASSWORD=yourpassword
   ```

4. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

5. Start the development server:
   ```bash
   php artisan serve
   npm run dev
   ```

6. Access the application at [http://localhost:8000].

## Database Schema

### Tables
- **Teams**: `id`, `name`, `description`, `created_at`, `updated_at`
- **Tasks**: `id`, `team_id`, `title`, `description`, `status`, `due_date`, `created_at`, `updated_at`
- **Users**: Laravel's default `users` table
- **Roles**: `id`, `name`, `guard_name`, `created_at`, `updated_at`
- **Permissions**: `id`, `name`, `guard_name`, `created_at`, `updated_at`
- **Model Has Roles**: Pivot table for users and roles
- **Model Has Permissions**: Pivot table for roles and permissions
- **Task_User**: `id`, `task_id`, `user_id`, `assigned_at`

### Relationships
- `Team` has many `Tasks`.
- `Task` belongs to a `Team`.
- `Task` has many `Users` through the `Task_User` pivot table.

## Key Endpoints

### Team Management
- `GET /teams`: Fetch all teams.
- `POST /teams`: Create a new team.
- `PUT /teams/{id}`: Update a team.
- `DELETE /teams/{id}`: Delete a team.

### Task Management
- `GET /tasks/{team}`: Fetch tasks for a specific team.
- `POST /tasks`: Create a new task.
- `PUT /tasks/{id}`: Update a task.
- `DELETE /tasks/{id}`: Delete a task.
- `POST /tasks/{id}/assign-users`: Assign users to a task.

### Role & Permission Management
- `GET /roles-permissions`: List users with roles.
- `POST /roles-permissions/assign-role`: Assign a role to a user.
- `POST /roles-permissions/assign-permissions`: Assign permissions to a role.

## Key Technologies
- **Laravel**: Backend framework.
- **Spatie Permissions**: Role and permission management.
- **AJAX**: Dynamic interaction for frontend.
- **Bootstrap**: Modal and responsive UI components.
- **MySQL**: Relational database.

## Usage Guide

### Setting Up Roles and Permissions
Roles and permissions are seeded during installation. If needed, you can modify or add new roles and permissions in the `RoleSeeder` or `PermissionSeeder` files.

To assign roles programmatically:
```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$role = Role::create(['name' => 'Admin']);
$permission = Permission::create(['name' => 'manage tasks']);
$role->givePermissionTo($permission);
```

### Managing Users
To assign roles to users:
1. Navigate to the **Roles & Permissions** page.
2. View the list of users and their roles.
3. Click "Edit Roles" for a user to assign or update roles.

To assign permissions to roles:
1. Go to the **Roles & Permissions** page.
2. Select a role and edit its permissions using the modal.
3. Save the changes.

### Task Assignment
1. Navigate to a team’s task list.
2. Click "Assign Users" for a task.
3. Select users from the dropdown and save.

### Inline Task Editing
1. Double-click on a task field (e.g., title, status, due date).
2. Edit the value and press Enter.
3. Changes are saved dynamically.

## Known Issues
- Inline editing requires stable internet for real-time updates.
- Roles and permissions must be manually configured for additional features.
- Ensure correct role assignment during user creation to avoid permission conflicts.

## Contributing
1. Fork the repository.
2. Create a feature branch (`git checkout -b feature-name`).
3. Commit changes (`git commit -m "Added feature"`).
4. Push to the branch (`git push origin feature-name`).
5. Open a pull request.

## License
This project is open-source and available under the [MIT License](LICENSE).

---

Happy coding! If you encounter any issues, please open a GitHub issue or contact the maintainer.

