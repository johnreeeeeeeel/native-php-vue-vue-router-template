import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'

// Guest
import GuestLayout from '../layouts/GuestLayout.vue'
import GuestWelcome from '../pages/guest/Welcome.vue' 

// Auth
import AuthLayout from '../layouts/AuthLayout.vue'
import AuthRegister from '../pages/auth/Register.vue' 
import AuthLogin from '../pages/auth/Login.vue' 
import AuthForgotPassword from '../pages/auth/ForgotPassword.vue' 
import AuthResetPassword from '../pages/auth/ResetPassword.vue' 

// Admin
import AdminLayout from '../layouts/AdminLayout.vue'
import AdminDashboard from '../pages/admin/Dashboard.vue' 

// Admin
import UserLayout from '../layouts/UserLayout.vue'
import UserHome from '../pages/user/Home.vue' 

const routes = [
  // Guest
  {
    path: '/',
    component: GuestLayout,
    children: [
      { path: '', component: GuestWelcome }
    ]
  },
  
  // Auth
  {
    path: '/auth/register',
    component: AuthLayout,
    children: [
      { path: '', component: AuthRegister }
    ]
  },
  
  {
    path: '/auth/login',
    component: AuthLayout,
    children: [
      { path: '', component: AuthLogin }
    ]
  },

  {
    path: '/auth/forgot-password',
    component: AuthLayout,
    children: [
      { path: '', component: AuthForgotPassword }
    ]
  },

  {
    path: '/auth/reset-password',
    component: AuthLayout,
    children: [
      { path: '', component: AuthResetPassword }
    ]
  },

  // Admin
  {
    path: '/admin/dashboard',
    component: AdminLayout,
    children: [
      { path: '', component: AdminDashboard }
    ]
  },

  // User
  {
    path: '/user/home',
    component: UserLayout,
    children: [
      { path: '', component: UserHome }
    ]
  },

]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Guest Pages
router.beforeEach(async (to, from) => {
  const publicPages = [
    '/',
    '/auth/register',
    '/auth/login',
    '/auth/forgot-password',
    '/auth/reset-password',
  ]

  if (publicPages.includes(to.path)) {
    return true
  }

  try {
    const res = await axios.get(
      'http://localhost/bulls_fitness_gym/backend/api/me.php',
      { withCredentials: true }
    )

    const user = res.data

    if (!user) {
      return '/auth/login'
    }

    if (to.path.startsWith('/admin') && user.role !== 'admin') {
      return '/auth/login'
    }

    if (to.path.startsWith('/frontdesk_staff') && user.role !== 'frontdesk_staff') {
      return '/auth/login'
    }

    if (to.path.startsWith('/user') && user.role !== 'user') {
      return '/auth/login'
    }

    return true

  } catch (err) {
    return '/auth/login'
  }
})

export default router