<template>
	<article>
		<h2>Deposit</h2>
		<form @submit.prevent="onSubmit">
			<label>
				Amount
				<input v-model.number="amount" type="number" step="0.01" min="0.01" :class="{invalid: errors.amount}" />
				<small v-if="errors.amount">{{ errors.amount }}</small>
			</label>
			<button type="submit" :disabled="!isValid || loading">{{ loading ? 'Processing…' : 'Deposit' }}</button>
			<p v-if="serverError" class="contrast">{{ serverError }}</p>
		</form>
	</article>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watchEffect } from 'vue'
import { useRouter } from 'vue-router'
import { useAccountStore } from '@/stores/account'

const accountStore = useAccountStore()
const router = useRouter()
const amount = ref<number>(0)
const loading = ref(false)
const serverError = ref('')
const errors = reactive<{ amount?: string }>({})

const isValid = computed(() => !errors.amount && amount.value > 0)

watchEffect(() => {
	errors.amount = amount.value > 0 ? '' : 'Enter an amount greater than 0'
})

async function onSubmit() {
	serverError.value = ''
	if (!isValid.value) return
	loading.value = true
	try {
		await accountStore.deposit(amount.value)
		router.push({ name: 'dashboard' })
	} catch (e: any) {
		serverError.value = e?.response?.data?.error || 'Deposit failed'
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


