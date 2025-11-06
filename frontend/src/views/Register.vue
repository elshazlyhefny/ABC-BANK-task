<template>
	<article>
		<h2>Register</h2>
		<form @submit.prevent="onSubmit">
			<label>
				Name
				<input v-model.trim="name" type="text" :class="{invalid: errors.name}" />
				<small v-if="errors.name">{{ errors.name }}</small>
			</label>
			<label>
				Email
				<input v-model.trim="email" type="email" :class="{invalid: errors.email}" />
				<small v-if="errors.email">{{ errors.email }}</small>
			</label>
			<label>
				Password
				<input v-model.trim="password" type="password" :class="{invalid: errors.password}" />
				<small v-if="errors.password">{{ errors.password }}</small>
			</label>
			<label>
				Confirm Password
				<input v-model.trim="password_confirmation" type="password" :class="{invalid: errors.password_confirmation}" />
				<small v-if="errors.password_confirmation">{{ errors.password_confirmation }}</small>
			</label>
			<button type="submit" :disabled="!isValid || loading">{{ loading ? 'Creating…' : 'Create account' }}</button>
			<p>Have an account? <router-link to="/login">Login</router-link></p>
			<p v-if="serverError" class="contrast">{{ serverError }}</p>
		</form>
	</article>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watchEffect } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const router = useRouter()
const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const loading = ref(false)
const serverError = ref('')
const errors = reactive<{ name?: string; email?: string; password?: string; password_confirmation?: string }>({})

const isValid = computed(() => !errors.name && !errors.email && !errors.password && !errors.password_confirmation && name.value && email.value && password.value && password_confirmation.value)

watchEffect(() => {
	errors.name = name.value.length >= 2 ? '' : 'Enter your name'
	errors.email = /.+@.+\..+/.test(email.value) ? '' : 'Enter a valid email'
	errors.password = password.value.length >= 8 ? '' : 'Minimum 8 characters'
	errors.password_confirmation = password_confirmation.value === password.value ? '' : 'Passwords must match'
})

async function onSubmit() {
	serverError.value = ''
	if (!isValid.value) return
	loading.value = true
	try {
		await auth.register({ name: name.value, email: email.value, password: password.value, password_confirmation: password_confirmation.value })
		router.push({ name: 'dashboard' })
	} catch (e: any) {
		serverError.value = e?.response?.data?.error || 'Registration failed'
	} finally {
		loading.value = false
	}
}
</script>

<style scoped>
article { max-width: 560px; margin: 2rem auto; }
.invalid { border-color: #d33; }
small { color: #d33; }
</style>


