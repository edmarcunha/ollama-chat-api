# 🧾 API Documentation

> This is a technical challenge developed by **Edmar Cunha**.

> This API is designed to manage user accounts and chat conversations powered by an AI model. It includes user authentication, account CRUD operations, and the ability to start, view, and manage chat sessions with message exchanges.

---

## 🚀 How to Run This Project Locally

### 🔧 Requirements

Make sure you have the following installed:

- [Docker](https://www.docker.com/)
- [Laravel Sail](https://laravel.com/docs/sail)
- [Git](https://git-scm.com/)

---

### 📥 Clone the Repository

```bash
git clone https://github.com/edmarcunha/ollama-chat-api.git
cd ollama-chat-api
```

---

### ⚙️ Copy the Environment File

```bash
cp .env.example .env
```

---

### 🐳 Start Docker Containers with Sail

```bash
./vendor/bin/sail up -d
```

> If this is your first time running the project:

```bash
composer install
./vendor/bin/sail up -d
```

---

### 🔑 Generate Application Key

```bash
./vendor/bin/sail artisan key:generate
```

---

### 🧬 Run Migrations and Seeders

```bash
./vendor/bin/sail artisan migrate
```

---

### ✅ Run Tests

```bash
./vendor/bin/sail artisan test
```

---

### 🧪 Test the API Locally

Use tools like [Postman](https://www.postman.com/) or [Insomnia](https://insomnia.rest/) to make requests:

```
http://localhost
```

---

## 📁 Authentication Endpoints

### 🧾 POST /api/register

**Description:** Registers a new user.

**Request Body:**

```json
{
  "name": "New User",
  "email": "new.user@example.com",
  "password": "secret",
  "password_confirmation": "secret"
}
```

**Response:**

```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "New User",
    "email": "new.user@example.com"
  }
}
```

**Status Codes:**

- 201 Created
- 422 Validation error

---

### 🔐 POST /api/login

**Description:** Authenticates a user.

**Request Body:**

```json
{
  "email": "new.user@example.com",
  "password": "secret"
}
```

**Response:**

```json
{
  "token": "...",
  "user": {
    "id": 1,
    "name": "New User",
    "email": "new.user@example.com",
    "..."
  }
}
```

**Status Codes:**

- 200 OK
- 401 Unauthorized

---

### 🔓 POST /api/logout

**Description:** Logs out the current user.

**Response:**

```json
{
  "message": "Logged out successfully"
}
```

**Status Codes:**

- 200 OK
- 401 Unauthorized

---

## 👤 User Endpoints

### 📄 GET /api/users

**Description:** Returns all users.

**Response:**

```json
[
  {
    "id": 1,
    "name": "Edmar",
    "email": "user@example.com"
  }
]
```

**Status Codes:**

- 200 OK

---


### 🔍 GET /api/users/{user}

**Description:** Gets a specific user by ID.

**Response:**

```json
{
  "id": 1,
  "name": "New User",
  "email": "new.user@example.com"
}
```

**Status Codes:**

- 200 OK
- 404 Not Found

---

### ✏️ PUT /api/users/{user}

**Description:** Updates a specific user.

**Request Body:**

```json
{
  "name": "New Name",
  "email": "new@email.com"
}
```

**Response:**

```json
{
  "message": "User updated successfully",
  "user": {
    "id": 1,
    "name": "New Name",
    "email": "new@email.com"
  }
}
```

**Status Codes:**

- 200 OK
- 422 Validation error

---

### ❌ DELETE /api/users/{user}

**Description:** Soft deletes a user and schedules permanent deletion.

**Response:**

```json
{
  "message": "User scheduled for deletion in 7 days.",
  "scheduled_for": "2025-07-20 12:00:00"
}
```

**Status Codes:**

- 200 OK

---

## 💬 Chat Endpoints

### ➕ POST /api/chats

**Description:** Creates a new chat with the user's initial message.

**Request Body:**

```json
{
  "message": "What is the capital of France?"
}
```

**Response:**

```json
{
  "chat_id": 1,
  "response": "The capital of France is Paris."
}
```

**Status Codes:**

- 200 OK
- 422 Validation error

---

### 📄 GET /api/chats

**Description:** Lists the authenticated user's chats.

**Response:**

```json
[
  {
    "id": 1,
    "title": "Trip to Mars",
    "messages": [...]
  }
]
```

**Status Codes:**

- 200 OK

---

### 📨 POST /api/chats/{chat}/message

**Description:** Sends a message in an existing chat.

**Request Body:**

```json
{
  "message": "And what currency do they use?"
}
```

**Response:**

```json
{
  "chat_id": 1,
  "response": "They use the Euro."
}
```

**Status Codes:**

- 200 OK
- 403 Unauthorized

---

### 🔍 GET /api/chats/{chat}

**Description:** Retrieves a single chat including dialogue messages.

**Response:**

```json
{
  "chat_id": 1,
  "title": "Trip to Mars",
  "dialogue": [
    {
      "id": 1,
      "role": "user",
      "content": "What is the capital of Portugal?",
      "timestamp": "2025-07-13 10:00:00"
    },
    {
      "id": 2,
      "role": "assistant",
      "content": "The capital of Portugal is Lisboa.",
      "timestamp": "2025-07-13 10:00:01"
    }
  ]
}
```

**Status Codes:**

- 200 OK
- 403 Unauthorized

---

### ❌ DELETE /api/chats/{chat}

**Description:** Deletes a chat permanently (only by the owner).

**Response:**

```json
{
  "message": "Chat deleted successfully."
}
```

**Status Codes:**

- 204 No Content
- 403 Unauthorized

