<template>
	<div class="container">
		<h2>Dashboard</h2>
		<div v-if="account">
			<p><strong>Account #</strong>: {{ account.account_number }}</p>
			<p><strong>Balance</strong>: {{ account.balance }}</p>
			<div class="actions">
				<router-link to="/deposit">Deposit</router-link>
				<router-link to="/transfer">Transfer</router-link>
			</div>
		</div>
		<div v-else>Loading...</div>
	</div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAccountStore } from '@/stores/account'

const accountStore = useAccountStore()
const account = accountStore.$state.account

onMounted(async () => {
	await accountStore.fetchAccount()
})
</script>

<style scoped>
.container { max-width: 600px; margin: 2rem auto; display: grid; gap: 1rem; }
.actions { display: flex; gap: 1rem; }
</style>


