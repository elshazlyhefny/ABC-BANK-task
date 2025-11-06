<template>
	<article>
		<h2>Transfer</h2>
		<form @submit.prevent="onSubmit">
			<label>
				Recipient Account Number
				<input v-model.trim="to" type="text" :class="{invalid: errors.to}" />
				<small v-if="errors.to">{{ errors.to }}</small>
			</label>
			<label>
				Amount
				<input v-model.number="amount" type="number" step="0.01" min="0.01" :class="{invalid: errors.amount}" />
				<small v-if="errors.amount">{{ errors.amount }}</small>
			</label>
			<button type="submit" :disabled="!isValid || loading">{{ loading ? 'Sending…' : 'Send' }}</button>
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
const to = ref('')
const amount = ref<number>(0)
const loading = ref(false)
const serverError = ref('')
const errors = reactive<{ to?: string; amount?: string }>({})

const isValid = computed(() => !errors.to && !errors.amount && to.value && amount.value > 0)

watchEffect(() => {
	errors.to = to.value.length >= 6 ? '' : 'Enter a valid account number'
	errors.amount = amount.value > 0 ? '' : 'Enter an amount greater than 0'
})

async function onSubmit() {
	serverError.value = ''
	if (!isValid.value) return
	loading.value = true
	try {
		await accountStore.transfer(to.value, amount.value)
		router.push({ name: 'dashboard' })
	} catch (e: any) {
		serverError.value = e?.response?.data?.error || e?.response?.data?.message || 'Transfer failed'
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


