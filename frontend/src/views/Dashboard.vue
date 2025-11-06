<template>
	<article>
		<h2>Dashboard</h2>
		<div v-if="accountStore.account">
			<p><strong>Account #</strong>: {{ accountStore.account.account_number }}</p>
			<p><strong>Balance</strong>: {{ accountStore.account.balance }}</p>
			<div class="grid">
				<router-link to="/deposit" class="contrast">Deposit</router-link>
				<router-link to="/transfer" class="secondary">Transfer</router-link>
			</div>
		</div>
		<div v-else>Loading...</div>
	</article>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAccountStore } from '@/stores/account'

const accountStore = useAccountStore()

onMounted(async () => {
	await accountStore.fetchAccount()
})
</script>

<style scoped>
article { max-width: 700px; margin: 2rem auto; }
.grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
</style>


