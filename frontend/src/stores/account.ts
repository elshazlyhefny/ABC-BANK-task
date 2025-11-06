import { defineStore } from 'pinia';
import api from '@/api/client';

interface AccountInfo { account_number: string; balance: string }
interface TransactionItem { id: number; type: 'deposit'|'transfer'; amount: string; created_at: string; from_account_id: number|null; to_account_id: number }

export const useAccountStore = defineStore('account', {
	state: () => ({
		account: null as AccountInfo | null,
		transactions: [] as TransactionItem[],
		loading: false,
	}),
	actions: {
		async fetchAccount() {
			this.loading = true;
			try {
				const { data } = await api.get('/account');
				this.account = data.data as AccountInfo;
			} finally {
				this.loading = false;
			}
		},
		async fetchTransactions() {
			const { data } = await api.get('/accounts/transactions');
			this.transactions = data.data as TransactionItem[];
		},
		async deposit(amount: number) {
			await api.post('/accounts/deposit', { amount });
			await this.fetchAccount();
		},
		async transfer(to_account_number: string, amount: number) {
			await api.post('/accounts/transfer', { to_account_number, amount });
			await this.fetchAccount();
		},
	},
});
