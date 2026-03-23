import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '@/views/auth/LoginPage.vue'
import RegisterPage from '@/views/auth/RegisterPage.vue'
import { useAuthStore } from '@/stores/auth'
import IpAddressPage from '@/views/IpAddressPage.vue'
import AuditLogPage from '@/views/AuditLogPage.vue'
import type { RoleName  } from '@/types/auth'

declare module 'vue-router' {
  interface RouteMeta {
    requiresAuth?: boolean
    guest?: boolean
    roles?: RoleName[]
  }
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      redirect: '/ip-addresses',
      meta: { requiresAuth: true }
    },
    {
      path: '/login',
      name: 'login',
      component: LoginPage,
      meta: { guest: true }
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterPage,
      meta: { guest: true }
    },
    {
      path: '/ip-addresses',
      name: 'ip-addresses',
      component: IpAddressPage,
      meta: { requiresAuth: true }
    },
    {
      path: '/audit-logs',
      name: 'audit-logs',
      component: AuditLogPage,
      meta: {
        requiresAuth: true,
        roles: ['super_admin']
      }
    }
  ],
}) 

router.beforeEach((to, from, next) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next('/login')
  }

  if (!to.meta.requiresAuth && auth.isAuthenticated) {
    return next('/')
  }

  if (to.meta.roles && !to.meta.roles.some(r => auth.hasRole(r))) {
    return next('/')
  }

  next()
})

export default router
