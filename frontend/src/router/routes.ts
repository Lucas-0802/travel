import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import Login from '../pages/Login.vue';
import Dashboard from '../pages/Dashboard.vue';


const authGuard = (to: any, from: any, next: any) => {
  const token = localStorage.getItem('token');
  if (!token && to.name !== 'login') {
    next({ name: 'login' }); 
  } else {
    next();
  }
};

export const routes: RouteRecordRaw[] = [
  { path: '/login', name: 'login', component: Login },
  {
    path: '/home',
    name: 'home',
    component: Dashboard,
    beforeEnter: authGuard, 
  },
  { path: '/', redirect: '/login' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
