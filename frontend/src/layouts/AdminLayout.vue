<script setup>
import axios from 'axios'
import { useRouter } from 'vue-router'
import { ref, provide } from 'vue'

const router = useRouter()

const logout = async () => {
  const res = await axios.get(
    'http://localhost/bulls_fitness_gym/backend/api/logout.php',
    { withCredentials: true }
  )

  router.push('/login')
}
</script>

<template>
  <h1>Admin Layout</h1>

  <nav>
    <ul>
      <li><a href="#" @click.prevent="logout">Logout</a></li>
    </ul>
  </nav>

  <router-view />
</template>