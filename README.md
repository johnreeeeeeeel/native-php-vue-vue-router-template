# INSTALL NATIVE PHP X VUE-VITE-ROUTER

## 1. Create Backend Directory
backend/
 ├── config
 └── api

## 2. Create Initial Database
CREATE DATABASE myproject

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

## 3. Create Backend index.php
backend/index.php

<?php

echo json_encode([
    "message" => "PHP Backend Running"
]);

?>

## 4. Initialize PHPMailer (If Applicable)
> cd backend
> composer require phpmailer/phpmailer

## 5. Initialize and Create .env
> cd backend
> composer require vlucas/phpdotenv

backend/.env

DATABASE_HOSTNAME=localhost
DATABASE_USERNAME=root
DATABASE_PASSWORD=
DATABASE_NAME=myproject

SMTP_HOST=smtp.gmail.com
SMTP_USERNAME=test@gmail.com
SMTP_PASSWORD=apppassword123
SMTP_FROM=test@gmail.com
SMTP_TO=test@gmail.com
SMTP_TITLE="Title"

## 6. Add Database Connection
backend/config/database.php

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
    die("Database Failed: ".$conn->connect_error);
}

?>

## 7. Create API (Example)
backend/api/users.php

<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require "../config/database.php";

$result = $conn->query(
    "SELECT * FROM users"
);

$data=[];

while($row=$result->fetch_assoc()){

    $data[]=$row;

}

echo json_encode($data);

?>

## 8. Create Frontend Folder
> cd myproject
> npm create vite@latest frontend

Framework:
Vue

Variant:
JavaScript

## 9. Install vue, vue router, axios and bootstrap
> cd frontend
> npm install
> npm install vue-router
> npm install axios
> npm install bootstrap

## 10. Configure Router
src/router/index.js

import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'

// Guest
import GuestLayout from '../layouts/Layout.vue'
import GuestWelcome from '../pages/Welcome.vue' 

const routes = [
  // Guest
  {
    path: '/',
    component: GuestLayout,
    children: [
      { path: '', component: GuestWelcome }
    ]
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router

## 11. Create Frontend index.php
frontend/index.html

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Title</title>
  </head>
  
  <body>
    <div id="app"></div>
    <script type="module" src="/src/main.js"></script>
  </body>
</html>

## 12. Create Layout (Example)
src/layout/Layout.vue

<template>
    <h1>Layout</h1>

    <router-view />
</template>

## 13. Create Page (Example)
src/pages/Welcome.vue

<template>
    <h1>Welcome Page</h1>
</template>

## 14. Connect Router
src/main.js

import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

import router from "./router"

createApp(App).use(router).mount('#app')

## 15. Run Project

Terminal 1
> cd my-project
> cd backend
> php -S localhost:8000

Terminal 2
> cd my-project
> cd frontend
> npm run dev

