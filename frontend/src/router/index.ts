import type { RouteRecordRaw } from 'vue-router';
import { createRouter, createWebHistory } from 'vue-router';

const Login = () => import('@/views/Login.vue');
const Register = () => import('@/views/Register.vue');
const Dashboard = () => import('@/views/Dashboard.vue');
const Deposit = () => import('@/views/Deposit.vue');
const Transfer = () => import('@/views/Transfer.vue');

const routes: RouteRecordRaw[] = [
  { path: '/login', name: 'login', component: Login, meta: { public: true } },
  { path: '/register', name: 'register', component: Register, meta: { public: true } },
  { path: '/', redirect: '/dashboard' },
  { path: '/dashboard', name: 'dashboard', component: Dashboard },
  { path: '/deposit', name: 'deposit', component: Deposit },
  { path: '/transfer', name: 'transfer', component: Transfer },
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to) => {
  const isPublic = to.meta.public === true;
  const token = localStorage.getItem('token');
  if (!isPublic && !token) {
    return { name: 'login' };
  }
});

export default router;
