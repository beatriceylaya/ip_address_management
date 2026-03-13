import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { loginApi, type LoginPayload } from '@/modules/auth/authApi'

export const useAuthStore = defineStore('auth', () => {

  const token = ref(localStorage.getItem('token'))

  const isAuthenticated = computed(() => !!token.value)

  const login = async (payload: LoginPayload) => {
    const res = await loginApi(payload)
    console.log(res)
    token.value = res.data.access_token

    localStorage.setItem('token', res.data.access_token)
  }

  return {
    token,
    isAuthenticated,
    login
  }

})