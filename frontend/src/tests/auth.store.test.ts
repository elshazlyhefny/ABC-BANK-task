import { describe, it, expect, vi, beforeEach } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useAuthStore } from '@/stores/auth'
import api from '@/api/client'

vi.mock('@/api/client', () => {
	return {
		default: {
			post: vi.fn(),
			get: vi.fn(),
		},
	}
})

describe('auth store', () => {
	beforeEach(() => {
		setActivePinia(createPinia())
		localStorage.clear()
	})

	it('login stores token and fetches user', async () => {
		// @ts-expect-error mock
		api.post.mockResolvedValueOnce({ data: { data: { token: 'tok123' } } })
		// @ts-expect-error mock
		api.get.mockResolvedValueOnce({ data: { data: { id: 1, name: 'A', email: 'a@a.com' } } })
		const auth = useAuthStore()
		await auth.login({ email: 'a@a.com', password: 'x' })
		expect(auth.token).toBe('tok123')
		expect(auth.user?.email).toBe('a@a.com')
	})
})


