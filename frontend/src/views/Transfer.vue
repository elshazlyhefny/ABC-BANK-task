<template>
	<div class="container">
		<h2>Transfer</h2>
		<form @submit.prevent="onSubmit">
			<div>
				<label>Recipient Account Number</label>
				<input v-model="to" type="text" required />
			</div>
			<div>
				<label>Amount</label>
				<input v-model.number="amount" type="number" step="0.01" min="0.01" required />
			</div>
			<button type="submit">Send</button>
		</form>
	</div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAccountStore } from '@/stores/account'

const accountStore = useAccountStore()
const router = useRouter()
const to = ref('')
const amount = ref<number>(0)

async function onSubmit() {
	await accountStore.transfer(to.value, amount.value)
	router.push({ name: 'dashboard' })
}
</script>

<style scoped>
.container { max-width: 480px; margin: 2rem auto; display: grid; gap: 1rem; }
label { display: block; margin-bottom: .25rem; }
input { width: 100%; padding: .5rem; }
button { padding: .5rem 1rem; }
</style>


