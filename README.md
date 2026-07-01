# Native PHP + Vue 3 + Vite + Vue Router Setup

A simple setup guide for creating a full-stack project using:

- Backend: Native PHP + MySQL
- Frontend: Vue 3 + Vite
- Routing: Vue Router
- HTTP Client: Axios
- Email: PHPMailer
- Environment: PHP dotenv

---

# Project Structure

```
myproject/
│
├── backend/
│   ├── api/
│   │   └── users.php
│   │
│   ├── config/
│   │   └── database.php
│   │
│   ├── vendor/
│   │
│   ├── .env
│   └── index.php
│
├── frontend/
│   ├── src/
│   │   ├── layouts/
│   │   │   └── Layout.vue
│   │   │
│   │   ├── pages/
│   │   │   └── Welcome.vue
│   │   │
│   │   ├── router/
│   │   │   └── index.js
│   │   │
│   │   ├── App.vue
│   │   ├── main.js
│   │   └── style.css
│   │
│   └── index.html
│
└── README.md
```

---

# 1. Create Backend Directory

Create the backend folders:

```
backend/
├── config
└── api
```

---

# 2. Create Database

Create database:

```sql
CREATE DATABASE myproject;
```

Create users table:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lastname VARCHAR(100) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    middlename VARCHAR(100),
    phone VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    reset_token VARCHAR(255),
    reset_expire DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

# 3. Create Backend Entry File

Create:

```
backend/index.php
```

Code:

```php
<?php

header("Content-Type: application/json");

echo json_encode([
    "message" => "PHP Backend Running"
]);

?>
```

---

# 4. Install PHPMailer

Go inside backend:

```bash
cd backend
```

Install PHPMailer:

```bash
composer require phpmailer/phpmailer
```

---

# 5. Install PHP dotenv

Install dotenv:

```bash
composer require vlucas/phpdotenv
```

Create:

```
backend/.env
```

Add:

```env
DATABASE_HOSTNAME=localhost
DATABASE_USERNAME=root
DATABASE_PASSWORD=
DATABASE_NAME=myproject


SMTP_HOST=smtp.gmail.com
SMTP_USERNAME=test@gmail.com
SMTP_PASSWORD=apppassword123

SMTP_FROM=test@gmail.com
SMTP_TO=test@gmail.com

SMTP_TITLE="My Project"
```

---

# 6. Create Database Connection

Create:

```
backend/config/database.php
```

Code:

```php
<?php

require __DIR__ . "/../vendor/autoload.php";


$dotenv = Dotenv\Dotenv::createImmutable(
    __DIR__ . "/.."
);

$dotenv->load();


$conn = new mysqli(
    $_ENV['DATABASE_HOSTNAME'],
    $_ENV['DATABASE_USERNAME'],
    $_ENV['DATABASE_PASSWORD'],
    $_ENV['DATABASE_NAME']
);


if ($conn->connect_error) {

    die(
        "Database Connection Failed: "
        . $conn->connect_error
    );

}

?>
```

---

# 7. Create API Example

Create:

```
backend/api/users.php
```

Code:

```php
<?php

header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json");


require "../config/database.php";


$result = $conn->query(
    "SELECT * FROM users"
);


$data = [];


while ($row = $result->fetch_assoc()) {

    $data[] = $row;

}


echo json_encode($data);

?>
```

---

# 8. Create Vue Frontend

Go to project directory:

```bash
cd myproject
```

Create Vue project:

```bash
npm create vite@latest frontend
```

Select:

```
Framework:
Vue

Variant:
JavaScript
```

---

# 9. Install Frontend Dependencies

Go inside frontend:

```bash
cd frontend
```

Install packages:

```bash
npm install

npm install vue-router

npm install axios

npm install bootstrap
```

---

# 10. Configure Vue Router

Create:

```
src/router/index.js
```

Code:

```javascript
import { createRouter, createWebHistory } from 'vue-router'

import GuestLayout from '../layouts/Layout.vue'

import GuestWelcome from '../pages/Welcome.vue'


const routes = [

    {
        path: "/",

        component: GuestLayout,

        children: [

            {
                path: "",

                component: GuestWelcome

            }

        ]

    }

]


const router = createRouter({

    history: createWebHistory(),

    routes

})


export default router
```

---

# 11. Configure Frontend HTML

File:

```
frontend/index.html
```

Code:

```html
<!doctype html>

<html lang="en">

<head>

<meta charset="UTF-8" />

<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>My Project</title>

</head>


<body>

<div id="app"></div>


<script type="module" src="/src/main.js"></script>


</body>

</html>
```

---

# 12. Create Layout Component

Create:

```
src/layouts/Layout.vue
```

Code:

```vue
<template>

    <h1>
        Layout
    </h1>

    <router-view />

</template>
```

---

# 13. Create Page Component

Create:

```
src/pages/Welcome.vue
```

Code:

```vue
<template>

    <h1>
        Welcome Page
    </h1>

</template>
```

---

# 14. Connect Router

Edit:

```
src/main.js
```

Code:

```javascript
import { createApp } from 'vue'

import './style.css'

import App from './App.vue'

import router from './router'


createApp(App)

.use(router)

.mount('#app')
```

---

# 15. Run Project

## Start Backend

Open Terminal 1:

```bash
cd myproject

cd backend

php -S localhost:8000
```

Backend:

```
http://localhost:8000
```

---

## Start Frontend

Open Terminal 2:

```bash
cd myproject

cd frontend

npm run dev
```

Frontend:

```
http://localhost:5173
```

---

# Deployment

## Deployment Structure

```
deploy/

├── backend/
│   ├── api/
│   │   └── users.php
│   │
│   ├── config/
│   │   └── database.php
│   │
│   ├── vendor/
│   ├── .env
│   └── index.php
│
├── dist/
│   ├── index.html
│   └── assets/
│       ├── index-abc123.js
│       └── index-def456.css
│
├── index.php
└── .htaccess
```

---

# Step 1: Build Frontend

Go to frontend:

```bash
cd frontend
```

Install dependencies:

```bash
npm install
```

Build:

```bash
npm run build
```

---

# Step 2: Create Deployment Package

Go back:

```bash
cd ..
```

Create deploy folder:

```bash
mkdir deploy
```

Copy backend:

```bash
cp -r backend deploy/
```

Copy Vue build:

```bash
cp -r frontend/dist deploy/
```

---

# Create Deployment Router

Inside:

```
deploy/index.php
```

Add:

```php
<?php

$path = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);


// API requests
if (strpos($path, '/api/') === 0) {

    $file = __DIR__ . '/backend' . $path;


    if (file_exists($file)) {

        require_once $file;

        exit;

    }


    $file .= '.php';


    if (file_exists($file)) {

        require_once $file;

        exit;

    }

}


// Static files
$file = __DIR__ . '/dist' . $path;


if (file_exists($file) && !is_dir($file)) {

    readfile($file);

    exit;

}


// Vue Router fallback
require_once __DIR__ . '/dist/index.html';

?>
```

---

# Create .htaccess

Create:

```
deploy/.htaccess
```

Add:

```apache
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f

RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule ^ index.php [L]
```

---

# Step 3: Upload To Server

Upload the contents inside:

```
deploy/
```

to your hosting public directory.

Your application will run:

```
Frontend:
yourdomain.com

API:
yourdomain.com/api/users.php
```