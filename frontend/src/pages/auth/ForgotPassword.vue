<script setup>
import axios from 'axios'
import { provide, ref } from 'vue'
import Toast from '../../components/Toast.vue'

const toastRef = ref(null)
const showToast = (message, type = 'success') => {
    if (message) {
        toastRef.value?.show(message, type)
    }
}
provide('toast', showToast)

const email = ref('')
const loading = ref(false)

const sendReset = async () => {
    if (loading.value) return
    loading.value = true

    try {
        const res = await axios.post(
            'http://localhost/bulls_fitness_gym/backend/api/forgot-password.php',
            { email: email.value.trim().toLowerCase() }
        )

        const message = res.data.message
        const type = res.data.success ? 'success' : 'danger'
        showToast(message, type)

        if (res.data.success) {
            email.value = ''
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

    <form @submit.prevent="sendReset">
        <div>
            <label for="email">Email Address</label>
            <input type="email" id="email" v-model="email" required />
        </div>

        <button type="submit" :disabled="loading">
            {{ loading ? 'Sending...' : 'Send Reset Link' }}
        </button>

        <a><router-link to="/auth/login">I remember my password</router-link></a>
    </form>
</template>