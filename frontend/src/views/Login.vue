<template>
	<article>
		<h2>Login</h2>
		<form @submit.prevent="onSubmit">
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
			<button type="submit" :disabled="!isValid || loading">{{ loading ? 'Signing in…' : 'Login' }}</button>
			<p>
				No account? <router-link to="/register">Register</router-link>
			</p>
			<p v-if="serverError" class="contrast">{{ serverError }}</p>
		</form>
	</article>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { computed, reactive, ref, watchEffect } from 'vue'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()
const email = ref('')
const password = ref('')
const loading = ref(false)
const serverError = ref('')
const errors = reactive<{ email?: string; password?: string }>({})

const isValid = computed(() => !errors.email && !errors.password && email.value && password.value)

watchEffect(() => {
	errors.email = /.+@.+\..+/.test(email.value) ? '' : 'Enter a valid email'
	errors.password = password.value.length >= 8 ? '' : 'Minimum 8 characters'
})

async function onSubmit() {
	serverError.value = ''
	if (!isValid.value) return
	loading.value = true
	try {
		await auth.login({ email: email.value, password: password.value })
		router.push({ name: 'dashboard' })
	} catch (e: any) {
		serverError.value = e?.response?.data?.error || 'Login failed'
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
