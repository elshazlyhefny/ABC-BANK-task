<template>
	<div class="container">
		<h2>Login</h2>
		<form @submit.prevent="onSubmit">
			<div>
				<label>Email</label>
				<input v-model="email" type="email" required />
			</div>
			<div>
				<label>Password</label>
				<input v-model="password" type="password" required />
			</div>
			<button type="submit">Login</button>
			<p>
				No account? <router-link to="/register">Register</router-link>
			</p>
		</form>
	</div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const router = useRouter()
const email = ref('')
const password = ref('')

async function onSubmit() {
	await auth.login({ email: email.value, password: password.value })
	router.push({ name: 'dashboard' })
}
</script>

<style scoped>
.container { max-width: 480px; margin: 2rem auto; display: grid; gap: 1rem; }
label { display: block; margin-bottom: .25rem; }
input { width: 100%; padding: .5rem; }
button { padding: .5rem 1rem; }
</style>
