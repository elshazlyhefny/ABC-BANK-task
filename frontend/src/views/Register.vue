<template>
	<div class="container">
		<h2>Register</h2>
		<form @submit.prevent="onSubmit">
			<div>
				<label>Name</label>
				<input v-model="name" type="text" required />
			</div>
			<div>
				<label>Email</label>
				<input v-model="email" type="email" required />
			</div>
			<div>
				<label>Password</label>
				<input v-model="password" type="password" required />
			</div>
			<div>
				<label>Confirm Password</label>
				<input v-model="password_confirmation" type="password" required />
			</div>
			<button type="submit">Create account</button>
			<p>
				Have an account? <router-link to="/login">Login</router-link>
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
const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')

async function onSubmit() {
	await auth.register({ name: name.value, email: email.value, password: password.value, password_confirmation: password_confirmation.value })
	router.push({ name: 'dashboard' })
}
</script>

<style scoped>
.container { max-width: 480px; margin: 2rem auto; display: grid; gap: 1rem; }
label { display: block; margin-bottom: .25rem; }
input { width: 100%; padding: .5rem; }
button { padding: .5rem 1rem; }
</style>


