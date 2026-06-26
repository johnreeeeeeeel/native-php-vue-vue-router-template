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
const password = ref('')
const confirmPassword = ref('')
const loading = ref(false)
const token = ref(new URLSearchParams(window.location.search).get('token'))

const resetPassword = async () => {
    if (loading.value) return
    loading.value = true

    try {
        const res = await axios.post(
            'http://localhost/bulls_fitness_gym/backend/api/reset-password.php',
            {
                token: token.value,
                password: password.value,
                confirmPassword: confirmPassword.value
            }
        )

        const message = res.data.message
        const type = res.data.success ? 'success' : 'danger'
        showToast(message, type)

        if (res.data.success) {
            password.value = ''
            confirmPassword.value = ''

            setTimeout(() => {
                router.push('/login')
            }, 1800)
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
    
    <form @submit.prevent="resetPassword">
        <div>
            <label for="password">New Password</label>
            <input type="password" id="password" v-model="password" required />
        </div>

        <div>
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" v-model="confirmPassword" required />
        </div>

        <button type="submit" :disabled="loading">
            {{ loading ? 'Resetting...' : 'Reset Password' }}
        </button>
    </form>
</template>