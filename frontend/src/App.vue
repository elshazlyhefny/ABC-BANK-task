<template>
	<nav class="container-fluid">
		<ul>
			<li><strong>ABC Bank</strong></li>
		</ul>
		<ul>
			<li><router-link to="/dashboard">Dashboard</router-link></li>
			<li><router-link to="/deposit">Deposit</router-link></li>
			<li><router-link to="/transfer">Transfer</router-link></li>
			<li v-if="!isAuthed"><router-link to="/login">Login</router-link></li>
			<li v-if="!isAuthed"><router-link to="/register">Register</router-link></li>
			<li v-else><button @click="onLogout" class="danger">Logout</button></li>
		</ul>
	</nav>
	<main class="container">
		<router-view />
	</main>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { computed } from 'vue'

const auth = useAuthStore()
const isAuthed = computed(() => !!auth.token)
async function onLogout() {
	try { await auth.logout() } catch {}
}
</script>

<style>
html, body, #app { height: 100%; }
body { margin: 0; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; }
nav { margin-bottom: 1rem; }
</style>


