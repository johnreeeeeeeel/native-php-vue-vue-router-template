# Native PHP + Vue 3 + Vite + Vue Router Setup

A simple setup guide for creating a project using:

- Backend: Native PHP + MySQL
- Frontend: Vue 3 + Vite
- Routing: Vue Router
- HTTP Client: Axios
- Email: PHPMailer
- Environment: PHP dotenv

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
└── frontend/
    ├── src/
    │   ├── layouts/
    │   │   └── Layout.vue
    │   │
    │   ├── pages/
    │   │   └── Welcome.vue
    │   │
    │   ├── router/
    │   │   └── index.js
    │   │
    │   ├── App.vue
    │   ├── main.js
    │   └── style.css
    │
    └── index.html
```

# 1. Create Backend Directory

Create the backend folder:

```
backend/
├── config
└── api
```

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
    reset_token VARCHAR(255) NULL,
    reset_expire DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

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

# 4. Install PHPMailer

Go to backend:

```bash
cd backend
```

Install PHPMailer:

```bash
composer require phpmailer/phpmailer
```

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

# 6. Create Database Connection

Create:

```
backend/config/database.php
```

Code:

```php
<?php

require __DIR__ . "/../vendor/autoload.php";


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");

$dotenv->load();


$conn = new mysqli(
    $_ENV['DATABASE_HOSTNAME'],
    $_ENV['DATABASE_USERNAME'],
    $_ENV['DATABASE_PASSWORD'],
    $_ENV['DATABASE_NAME']
);


if($conn->connect_error){

    die(
        "Database Connection Failed: "
        .$conn->connect_error
    );

}

?>
```

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


while($row = $result->fetch_assoc()){

    $data[] = $row;

}


echo json_encode($data);

?>
```

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


import router from "./router"


createApp(App)

.use(router)

.mount('#app')
```

# 15. Run Project

## Start Backend

Terminal 1:

```bash
cd myproject

cd backend

php -S localhost:8000
```

Backend:

```
http://localhost:8000
```


## Start Frontend

Terminal 2:

```bash
cd myproject

cd frontend

npm run dev
```

Frontend:

```
http://localhost:5173
```