# Laravel Simple Blog Structure

[![Live Demo](https://img.shields.io/badge/Telegram-Start_Bot-2CA5E0?style=for-the-badge&logo=telegram&logoColor=white)](https://t.me/hire_jafar_bot)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Connect-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/jafar-marouf/)
[![GitHub](https://img.shields.io/badge/GitHub-Profile-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/JafarKMarouf)
[![GitLab](https://img.shields.io/badge/GitLab-Profile-FC6D26?style=for-the-badge&logo=gitlab&logoColor=white)](https://gitlab.com/JafarKMarouf)

This project implements a fundamental backend database structure for a blogging platform using Laravel Eloquent Models and Migrations. It focuses on the relationships between **Authors**, **Categories**, **Posts**, and **Comments**.

## 📂 Database Schema

The database consists of four primary tables with the following fields:

### 1. Categories
- `id` (Primary Key)
- `name` (String)

### 2. Authors
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

## 🚀 Installation & Setup

### 1. Generate Files
Run the following commands to create the necessary migration and model files:

```bash
# Create Models and Migrations simultaneously
php artisan make:model Category -m
php artisan make:model Author -m
php artisan make:model Post -m
php artisan make:model Comment -m
```
### 2. Update Migrations
Open the files in `database/migrations` and add the schema definitions provided in the project documentation (refer to the Model/Migration setup guide).

### 3. Run Migrations
Execute the migrations to build the database tables:

```bash

php artisan migrate
```
### 💻 Usage Examples
You can test these relationships using php artisan tinker or within your controllers.

### Creating Data
``` PHP
use App\Models\Author;
use App\Models\Category;
use App\Models\Post;

// 1. Create dependencies
$author = Author::create(['name' => 'John Doe', 'email' => 'john@example.com']);
$category = Category::create(['name' => 'Tech News']);

// 2. Create a Post linked to Author and Category
$post = Post::create([
    'title' => 'Laravel Relationships Explained',
    'content' => 'Introduction to hasMany and belongsTo...',
    'author_id' => $author->id,
    'category_id' => $category->id,
    'published_at' => now(),
]);

// 3. Add a comment to the post
$post->comments()->create([
    'content' => 'Great article, thanks for sharing!'
]);
```
### Retrieving Data
Get a Post's Author and Category:

``` PHP
$post = Post::with(['author', 'category'])->first();

echo $post->title;          // Output: "Laravel Relationships Explained"
echo $post->author->name;   // Output: "John Doe"
echo $post->category->name; // Output: "Tech News"
```
### Get all Comments for a Post:

``` PHP
foreach ($post->comments as $comment) {
    echo $comment->content;
}
```

### Get all Posts by a specific Author:

``` PHP
$author = Author::where('email', 'john@example.com')->first();
$posts = $author->posts; // Returns a collection of Post models
```
---
