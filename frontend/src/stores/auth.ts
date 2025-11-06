import { defineStore } from 'pinia';
import api from '@/api/client';

interface UserInfo { id: number; name: string; email: string }

export const useAuthStore = defineStore('auth', {
	state: () => ({
		token: (typeof localStorage !== 'undefined' ? localStorage.getItem('token') : null) as string | null,
		user: null as UserInfo | null,
	}),
	actions: {
		setToken(token: string | null) {
			this.token = token;
			if (token) localStorage.setItem('token', token);
			else localStorage.removeItem('token');
		},
		async register(payload: { name: string; email: string; password: string; password_confirmation: string }) {
			const { data } = await api.post('/auth/register', payload);
			this.setToken(data.data.token);
			await this.fetchMe();
		},
		async login(payload: { email: string; password: string }) {
			const { data } = await api.post('/auth/login', payload);
			this.setToken(data.data.token);
			await this.fetchMe();
		},
		async logout() {
			await api.post('/auth/logout');
			this.setToken(null);
			this.user = null;
		},
		async fetchMe() {
			const { data } = await api.get('/auth/user');
			this.user = data.data as UserInfo;
		},
	},
});
