import { describe, it, expect, vi, beforeEach } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useAccountStore } from '@/stores/account'
import api from '@/api/client'

vi.mock('@/api/client', () => {
	return {
		default: {
			get: vi.fn(),
			post: vi.fn(),
		},
	}
})

describe('account store', () => {
	beforeEach(() => {
		setActivePinia(createPinia())
	})

	it('fetchAccount assigns account data', async () => {
		// @ts-expect-error mock
		api.get.mockResolvedValueOnce({ data: { data: { account_number: '123', balance: '0.00' } } })
		const store = useAccountStore()
		await store.fetchAccount()
		expect(store.account?.account_number).toBe('123')
	})
})


