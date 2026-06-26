<script setup>
import axios from 'axios'
import { provide, ref } from 'vue'
import { useRouter } from 'vue-router'
import Toast from '../../components/Toast.vue'

const toastRef = ref(null)
const showToast = (message, type = 'success') => {
    if (message) {
        toastRef.value?.show(message, type)
    }
}
provide('toast', showToast)

const router = useRouter()
const email = ref('')
const password = ref('')
const loading = ref(false)

const login = async () => {
    if (loading.value) return
    loading.value = true

    try {
        const res = await axios.post(
            'http://localhost/bulls_fitness_gym/backend/api/login.php',
            {
                email: email.value.trim().toLowerCase(),
                password: password.value
            },
            { withCredentials: true }
        )

        const message = res.data.message
        const type = res.data.success ? 'success' : 'danger'
        showToast(message, type)

        if (res.data.success) {
            const user = res.data.user
            if (user?.role === 'admin') {
                router.push('/admin/dashboard')
            } else if (user?.role === 'frontdesk_staff') {
                router.push('/frontdesk_staff/dashboard')
            } else {
                router.push('/user/home')
            }
        }
    } catch (err) {
        console.error(err)
        const errorMessage = err.response?.data?.message
        if (errorMessage) {
            showToast(errorMessage, 'danger')
        }
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <Toast ref="toastRef" />

    <form @submit.prevent="login">
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" v-model="email" required />
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" v-model="password" required />
        </div>

        <a><router-link to="/auth/forgot-password">Forgot password?</router-link></a>

        <button type="submit" :disabled="loading">
            {{ loading ? 'Logging in...' : 'Login' }}
        </button>

        <p>Don't have an account?<a><router-link to="/auth/register"> Register here</router-link></a></p>
    </form>
</template>