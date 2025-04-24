<p align="center"><a href="https://buzzvel.com" target="_blank"><img src="https://buzzvel.com/storage/images/logo-light.svg" width="200" alt="Buzzvel Logo"></a></p>

# 🚀 Buzzvel Backend Developer Process

## 📋 Overview

This project is a Laravel 12 application with Ollama local LLM integration via Docker. The application provides authenticated users with access to an AI chat service powered by the Llama3.2:1b model.

## ✨ Features

- 🔐 Complete authentication system
- 🤖 Integration with Ollama LLM
- 👤 Full user CRUD operations
- 🔄 Scheduled deletion of user accounts
- ⏱️ 24-hour job for updating main chat messages
- 🧪 Comprehensive test suite

## 🛠️ Tech Stack

- [Laravel 12](https://laravel.com/docs/12.x)
- [Laravel Sail](https://laravel.com/docs/12.x/sail) (Docker development environment)
- [PHP 8.2+](https://www.php.net/releases/8.2/en.php)
- [Docker](https://docs.docker.com/get-started/)
- [Ollama](https://hub.docker.com/r/ollama/ollama)
- [MySQL](https://dev.mysql.com/doc/)

## 🚦 Prerequisites

- Docker & Docker Compose
- Git

## 📥 Installation

### 1. Clone the repository

```bash
git clone [repository-url]
cd [repository-name]
```

### 2. Set up the environment and run composer install

```bash
cp .env.example .env
composer install
```

Update your `.env` file with your database and other necessary configurations.

### 3. Start Laravel Sail

```bash
./vendor/bin/sail up -d
```

### 4. Generate application key

```bash
./vendor/bin/sail artisan key:generate
```

### 5. Run migrations

```bash
./vendor/bin/sail artisan migrate
```

### 6. Set up the Ollama LLM

```bash
./vendor/bin/sail exec ollama ollama run llama3.2:1b
```

### 7. Interacting with Ollama LLM

Ollama runs on port 11434 by default. After setting up the container and running the model, you can test it using CURL:

```bash
curl -X POST http://localhost:11434/api/generate -d '{
  "model": "llama3.2:1b",
  "prompt": "What is the capital of Portugal?"
}'
```

Example response:
```json
{
  "model": "llama3.2:1b",
  "created_at": "2023-06-15T12:34:56.789Z",
  "response": "The capital of Portugal is Lisbon.",
  "done": true
}
```

For more information about Ollama API, visit the [official Docker image page](https://hub.docker.com/r/ollama/ollama) and [Ollama documentation](https://github.com/ollama/ollama).

### 8. (Optional) Installing Ollama-Laravel Package

You can use the [Ollama-Laravel package](https://github.com/cloudstudio/ollama-laravel) to simplify interaction with the Ollama API:

```bash
./vendor/bin/sail composer require cloudstudio/ollama-laravel
```

## 🧩 Project Requirements

### 1. Authentication System

Implement a complete authentication flow with:
- Registration
- Login
- Logout
- Password reset
- Email verification

You can use [Laravel Fortify](https://laravel.com/docs/12.x/fortify), [Laravel Breeze](https://laravel.com/docs/12.x/starter-kits#laravel-breeze), or any other suitable package.

### 2. User Management

Create a comprehensive CRUD system for user information:
- Create: User registration
- Read: Fetch user details
- Update: Edit user information
- Delete: Schedule account deletion (soft delete)

### 3. Ollama LLM Integration

Integrate with the Ollama LLM service to:
- Connect authenticated users to the chat API
- Process and store chat histories
- Ensure secure communication between the frontend and the LLM

### 4. Scheduled Jobs

Implement scheduled tasks:
- Process scheduled account deletions

```php
// Example scheduler configuration
protected function schedule(Schedule $schedule)
{
    $schedule->command('chat:update-main-message')->daily();
    $schedule->command('users:process-deletions')->daily();
}
```

### 5. Testing

Write comprehensive tests using either PHPUnit or PEST:
- Unit tests
- Feature tests
- Integration tests

## 🔍 Evaluation Criteria

Your solution will be evaluated based on:
- Code quality and organization
- Use of Laravel features and best practices
- Test coverage and quality
- Implementation of required features
- Documentation

## 📤 Submission

When you have completed the assignment, please submit your work via:

- [ClickUp Form](https://forms.clickup.com/6647387/f/6avjv-18455/PLUYAZ40HA3XTQOEFW)

You can also apply for the Mid-Level Back-End Developer position at:

- [Buzzvel Careers Page](https://buzzvel.com/careers/mid-level-back-end-developer)

## 🏢 About Buzzvel

Buzzvel is a Portuguese company that works from Lisbon to the world, with digital solutions always thinking of you. We have been specializing in innovative technologies for over 10 years, creating great web applications, sites, and shops for clients in sectors such as Education, Retail, Telecommunications, Finance, Software & High Tech, and Blockchain.

To learn more about Buzzvel, visit [our About page](https://buzzvel.com/about).

## 📝 License

This project is licensed under the MIT License.