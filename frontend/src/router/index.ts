import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/auth/Login.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Register from '@/views/auth/Register.vue'
import { useAuthStore } from '@/stores/auth'
import IpAddressList from '@/views/IpAddressList.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: AppLayout,
      meta: { requiresAuth: true }
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: { guest: true }
    },
    {
      path: '/register',
      name: 'register',
      component: Register,
      meta: { guest: true }
    },
    {
      path: '/ip-addresses',
      name: 'ip address',
      component: IpAddressList,
      meta: { requiresAuth: true }
    }
  ],
}) 

router.beforeEach((to, from, next) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    next('/login')
  } else if (to.meta.guest && auth.isAuthenticated) {
    next('/')
  } else {
    next()
  }
})

export default router
