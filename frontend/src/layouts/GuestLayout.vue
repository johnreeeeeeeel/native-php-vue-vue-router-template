<template>
    <h1>Guest Layout</h1>

    <nav>
        <ul>
            <li><router-link to="/auth/login">Login</router-link></li>
            <li><router-link to="/auth/register">Register</router-link></li>
        </ul>
    </nav>

    <router-view />
</template>