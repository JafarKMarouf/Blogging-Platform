# Laravel Blog API - Training Project

[![Live Demo](https://img.shields.io/badge/Telegram-Start_Bot-2CA5E0?style=for-the-badge&logo=telegram&logoColor=white)](https://t.me/hire_jafar_bot)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Connect-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/jafar-marouf/)
[![GitHub](https://img.shields.io/badge/GitHub-Profile-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/JafarKMarouf)
[![GitLab](https://img.shields.io/badge/GitLab-Profile-FC6D26?style=for-the-badge&logo=gitlab&logoColor=white)](https://gitlab.com/JafarKMarouf)

## 📖 Overview

The Laravel Blog API is designed as an API-first application that exclusively
serves JSON responses. It is ideal for consumption by modern web frontends or
mobile applications.

### Key Features

* **Authentication System:** Registration, login, logout, and token refresh via
  **Laravel Sanctum**.
* **Blog Management**: Full CRUD operations for posts, categories, and comments
* **User Roles**: Author-based content management
* **API Resources:** Structured JSON responses with pagination support
* **Service Layer Architecture:** Clean separation of business logic
* **Request Validation:** Form request validation for all endpoints
* **Testing:** PHPUnit and Pest testing setup composer.json:24-25
* **Standardized Error Handling:** Global exception handling for 404, 401,
  409, and 500 errors via `bootstrap/app.php`.

---

## ⚙️ Installation & Setup

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL
- Node.js & NPM

### Quick Start

``` Clone the repository
git clone https://github.com/JafarKMarouf/Blogging-Platform.git  
cd Blogging-Platform
```
### Manual Setup
```
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env
# Then run migrations
php artisan migrate

# Install and build frontend assets
npm install  
npm run build
```
### Development Server
```
# Start development environment with hot reload
composer dev
```
This command installs dependencies, copies .env, generates the app key, and
runs migrations.
---

## 📂 Database Schema

The database consists of four primary tables with the following fields:

### 1. Categories

- `id` (Primary Key)
- `name` (String)

### 2. Users

- `id` (Primary Key)
- `name` (String)
- `email` (String, Unique)

### 3. Posts

- `id` (Primary Key)
- `title` (String)
- `content` (Text)
- `published_at` (DateTime, Nullable)
- `category_id` (Foreign Key referencing `categories`)
- `author_id` (Foreign Key referencing `authors`)

### 4. Comments

- `id` (Primary Key)
- `content` (Text)
- `post_id` (Foreign Key referencing `posts`)
- `author_id` (Foreign Key referencing `users`)

---

## 🔗 Relationships

The models are connected via the following Eloquent relationships:

| Model        | Type       | Related Model | Method Name  | Description                               |
|:-------------|:-----------|:--------------|:-------------|:------------------------------------------|
| **Category** | Has Many   | `Post`        | `posts()`    | A category contains multiple blog posts.  |
| **Author**   | Has Many   | `Post`        | `posts()`    | An author can write multiple blog posts.  |
| **Post**     | Belongs To | `Category`    | `category()` | A post belongs to one specific category.  |
| **Post**     | Belongs To | `Author`      | `author()`   | A post is written by one specific author. |
| **Post**     | Has Many   | `Comment`     | `comments()` | A post can have many user comments.       |
| **Comment**  | Belongs To | `Post`        | `post()`     | A comment belongs to a single post.       |

---

## 🛠️ Technology Stack

- **Framework:** Laravel 12.x
- **PHP:** 8.2+
- **Database:** MySQL (configurable)
- **Authentication:** Laravel Sanctum 4.2
- **Testing:** Pest 3.8
- **Architecture:** Service Layer, Form Requests, and API Resources.

---
## 🏗️ Architectural Patterns

The codebase follows strict design patterns to ensure maintainability:

**Service Layer:** Business logic is encapsulated in dedicated Service
classes (e.g., PostService, AuthService).

**Form Request Validation:** Input validation is extracted from controllers
into specific Request classes.

**API Resources:** Standardizes JSON output across all endpoints.

**ForceJsonResponse:** Middleware ensures all responses are returned as
application/json regardless of the request headers.

## 📡 API Reference

**Response Format**

All responses follow a consistent structure:

**Success:**

``` JSON
{
    "success": true,
    "message": "Operation completed successfully",
    "data": { ... }
}
```

**Error:**

``` JSON
{
    "success": false,
    "message": "Error description",
    "errors": { ... }
}
```

### Main Endpoints

| Group    | Endpoint           | Method | Auth Required |
|:---------|:-------------------|:-------|:--------------|
| Auth     | `/api/auth/login`  | POST   | No            |
| Auth     | `/api/auth/logout` | POST   | Yes           |
| Posts    | `/api/posts`       | GET    | No            |
| Posts    | `/api/posts`       | POST   | Yes           |
| Comments | `/api/comments`    | POST   | Yes           |

## 🧪 Testing

We use **Pest** for our test suite. To run tests:

```Bash

# Run tests
composer test 
  
# Or directly
php artisan test
```

## 📜 License

This project is open-sourced software licensed under the MIT license.

---
