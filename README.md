# Tasker

A lightweight task management web application built with vanilla PHP and MySQL. Organize your work with a clean, dark-themed interface — add tasks, edit them, mark them complete, and track your progress.

## Features

- **User Authentication** — Register, log in, and log out with PHP session-based auth and bcrypt password hashing
- **Task CRUD** — Create, edit, mark as complete, and delete tasks via POST forms
- **Progress Tracking** — Visual progress bar showing completion percentage with animated transitions
- **Two-Column Layout** — Pending and completed tasks displayed side by side for easy management
- **Confirmation Dialogs** — Native `<dialog>` elements for delete confirmation to prevent accidental removals
- **Responsive Design** — Works on desktop and mobile with a polished dark theme and custom scrollbar
- **Route Protection** — Auth middleware redirects guests from protected pages and logged-in users from auth pages
- **404 Handling** — Custom 404 page for unknown routes

## Tech Stack

| Layer       | Technology                          |
|-------------|-------------------------------------|
| Backend     | PHP 8+ (no framework)               |
| Database    | MySQL 5.7+ / MariaDB 10.3+          |
| Frontend    | HTML, CSS, vanilla JavaScript       |
| Styling     | Tailwind CSS v3 (CDN)               |
| Font        | DM Sans (Google Fonts)              |
| Server      | PHP built-in server or Apache       |
| Container   | Docker (php:8.4-apache)             |

## Project Structure

```
task_tracker/
├── index.php                  # Front controller — serves static files or routes to app
├── .env.example               # Environment variable template
├── .gitignore                 # Ignores .env
├── Dockerfile                 # Apache-based Docker image
├── database/
│   └── Database.php           # MySQLi connection wrapper (dev + production SSL)
├── models/
│   ├── User.php               # User model (create, findByEmail)
│   └── Task.php               # Task model (CRUD operations)
├── controllers/
│   ├── AuthController.php     # Login / register logic
│   └── TaskController.php     # Task CRUD controller
├── pages/
│   ├── layout.php             # Main layout with nav, footer, auth middleware, routing
│   ├── index.php              # Landing page (hero, features, CTA)
│   ├── auth/
│   │   ├── login/index.php    # Login form
│   │   ├── register/index.php # Registration form
│   │   └── logout/index.php   # Logout handler
│   ├── tasks/index.php        # Tasks dashboard (CRUD UI, dialogs, progress)
│   └── partials/
│       └── 404.php            # Custom 404 page
└── public/
    ├── index.php              # App entry point (loads layout)
    ├── .htaccess              # Apache rewrite rules
    ├── styles.css             # Custom CSS (theme vars, reset, scrollbar)
    └── favicon.ico            # Site icon
```

## Architecture

Tasker follows a lightweight MVC-like pattern:

- **Routing** — File-based routing in `pages/layout.php`. URLs map to directories under `pages/` (e.g., `/tasks` → `pages/tasks/index.php`). The front controller (`index.php`) serves static files from `public/` or delegates to the app.
- **Models** — `User` and `Task` classes handle database operations using prepared statements. Each model manages its own MySQLi connection via `Database`.
- **Controllers** — `AuthController` and `TaskController` sit between models and pages, orchestrating business logic.
- **Auth Middleware** — `layout.php` checks session state before rendering pages. Guests are redirected to `/auth/login` for protected routes; logged-in users are redirected to `/tasks` for auth pages.
- **Database** — `Database.php` supports both local development and production modes. Production mode enables MySQL SSL connections via `MYSQLI_CLIENT_SSL`.

## Prerequisites

- **PHP** 8.0 or higher (with `mysqli` extension)
- **MySQL** 5.7+ or **MariaDB** 10.3+
- **Composer** — not required (no dependencies)

## Installation

### Option 1: Local Development

#### 1. Clone the repository

```bash
git clone <repository-url> task_tracker
cd task_tracker
```

#### 2. Configure environment

Copy the example environment file:

```bash
cp .env.example .env
```

Edit `.env` with your database credentials:

```env
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_password
DB_NAME=task_tracker
DB_PORT=3306
APP_ENV=development
```

> **Note:** PHP's `getenv()` reads environment variables set in the server environment. If using PHP's built-in server, you may need to set these variables directly in `database/Database.php` or use a `.env` loader.

#### 3. Create the database

```sql
CREATE DATABASE task_tracker;
```

#### 4. Run the schema

```sql
USE task_tracker;

CREATE TABLE users (
    id          VARCHAR(32)     PRIMARY KEY,
    name        VARCHAR(100)    NOT NULL,
    email       VARCHAR(150)    NOT NULL UNIQUE,
    password    VARCHAR(255)    NOT NULL,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
    id            VARCHAR(32)   PRIMARY KEY,
    user_id       VARCHAR(32)   NOT NULL,
    title         VARCHAR(255)  NOT NULL,
    description   TEXT,
    status        VARCHAR(20)   DEFAULT 'pending',
    created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    completed_at  TIMESTAMP     NULL,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### 5. Start the server

```bash
php -S localhost:8000 index.php
```

Open **http://localhost:8000** in your browser.

### Option 2: Docker

#### 1. Build the image

```bash
docker build -t tasker .
```

#### 2. Run the container

```bash
docker run -d \
  --name tasker \
  -p 8000:80 \
  -e DB_HOST=host.docker.internal \
  -e DB_USER=root \
  -e DB_PASSWORD=your_password \
  -e DB_NAME=task_tracker \
  -e APP_ENV=development \
  tasker
```

> **Note:** `host.docker.internal` resolves to the host machine on Docker Desktop. For Linux, use your host's IP address or run MySQL in a separate container.

Open **http://localhost:8000** in your browser.

## Configuration

### Environment Variables

| Variable      | Description              | Default         |
|---------------|--------------------------|-----------------|
| `DB_HOST`     | Database host            | `localhost`     |
| `DB_USER`     | Database username        | `root`          |
| `DB_PASSWORD` | Database password        | *(empty)*       |
| `DB_NAME`     | Database name            | `task_tracker`  |
| `DB_PORT`     | Database port            | `3306`          |
| `APP_ENV`     | Application environment  | `development`   |

Set `APP_ENV=production` to enable SSL MySQL connections.

### Routing

The app uses file-based routing. URLs map to directories under `pages/`:

| URL                  | Page                | Auth Required |
|----------------------|---------------------|---------------|
| `/`                  | Landing page        | No            |
| `/auth/login`        | Login form          | No (guests)   |
| `/auth/register`     | Registration form   | No (guests)   |
| `/auth/logout`       | Logout handler      | Yes           |
| `/tasks`             | Task dashboard      | Yes           |
| `/public/*`          | Static assets       | No            |
| *(anything else)*    | 404 page            | No            |

## Usage

1. **Register** — Create an account with your name, email, and password (min. 8 characters)
2. **Log in** — Sign in with your email and password
3. **Add tasks** — Click "Add Task" on the dashboard, enter a title and optional description
4. **Edit tasks** — Click the edit icon on any task card to update its details
5. **Complete tasks** — Click the checkmark circle to mark a task as done
6. **Delete tasks** — Click the trash icon; a confirmation dialog appears before deletion
7. **Track progress** — The progress bar at the top of the dashboard shows your completion rate

## Security

- **Password Hashing** — Passwords are hashed with `password_hash()` using `PASSWORD_DEFAULT` (bcrypt)
- **Prepared Statements** — All database queries use MySQLi prepared statements to prevent SQL injection
- **Session-Based Auth** — User sessions are managed via PHP's native `$_SESSION` with `session_start()`
- **User Isolation** — Task operations are scoped to `user_id`, preventing cross-user data access
- **XSS Prevention** — User input is escaped with `htmlspecialchars()` before rendering

## License

This project is open source and available under the [MIT License](LICENSE).
