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
const loading = ref(false)
const firstname = ref('')
const middlename = ref('')
const lastname = ref('')
const email = ref('')
const phone = ref('')
const password = ref('')
const confirmPassword = ref('')
const agreeTerms = ref(false)

const register = async () => {
    if (loading.value) return
    loading.value = true

    try {
        const res = await axios.post('http://localhost/bulls_fitness_gym/backend/api/register.php', {
            firstname: firstname.value.trim(),
            middlename: middlename.value.trim(),
            lastname: lastname.value.trim(),
            email: email.value.trim().toLowerCase(),
            phone: phone.value.trim(),
            password: password.value,
            confirmPassword: confirmPassword.value,
            agreeTerms: agreeTerms.value
        })

        const message = res.data.message
        const type = res.data.success ? 'success' : 'danger'
        showToast(message, type)

        if (res.data.success) {
            firstname.value = ''
            middlename.value = ''
            lastname.value = ''
            email.value = ''
            phone.value = ''
            password.value = ''
            confirmPassword.value = ''
            agreeTerms.value = false

            setTimeout(() => {
                router.push('/auth/login')
            }, 1500)
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

    <form @submit.prevent="register">
        <div>
            <label for="firstname">First Name</label>
            <input type="text" id="firstname" v-model="firstname" required />
        </div>

        <div>
            <label for="middlename">Middle Name</label>
            <input type="text" id="middlename" v-model="middlename" />
        </div>

        <div>
            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" v-model="lastname" required />
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" v-model="email" required />
        </div>

        <div>
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" v-model="phone" required />
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" v-model="password" required />
        </div>

        <div>
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" v-model="confirmPassword" required />
        </div>

        <div>
            <label>
                <input type="checkbox" v-model="agreeTerms" />
                I agree to the Terms & Conditions
            </label>
        </div>

        <button type="submit" :disabled="loading || !agreeTerms">
            {{ loading ? 'Creating Account...' : 'Create Account' }}
        </button>

        <p>Already have an account?<a><router-link to="/auth/login">Login here</router-link></a></p>
    </form>
</template>