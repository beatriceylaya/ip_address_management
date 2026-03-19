import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import router from '@/router'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
})

api.interceptors.request.use((config) => {
  const auth = useAuthStore()

  if (auth.accessToken) {
    config.headers = config.headers ?? {}
    config.headers.Authorization = `Bearer ${auth.accessToken}`
  }

  return config
})

// Response interceptor: silent refresh on 401
let isRefreshing = false
let failedQueue: {
  resolve: (token?: string | null) => void
  reject: (error: unknown) => void
}[] = []

function processQueue(error: unknown, token: string | null = null) {
  failedQueue.forEach(({ resolve, reject }) => {
    if (error) {
      reject(error)
    } else {
      resolve(token)
    }
  })
  failedQueue = []
}

api.interceptors.response.use(
  response => response,

  async error => {
    const originalRequest = error.config
    const auth = useAuthStore()

    // Only attempt refresh once per request (_retry flag prevents loops)
    if (error.response?.status !== 401 || originalRequest._retry) {
      return Promise.reject(error)
    }

    // Don't refresh if the failing request IS the refresh call
    if (originalRequest.url?.includes('/auth/refresh')) {
      useAuthStore().clearAuth()
      window.location.href = '/login'
      return Promise.reject(error)
    }

    originalRequest._retry = true

    if (isRefreshing) {
      // Another refresh is already in flight — queue this request
      return new Promise((resolve, reject) => {
        failedQueue.push({ resolve, reject })
      }).then(token => {
        originalRequest.headers.Authorization = `Bearer ${token}`
        return api(originalRequest)
      })
    }

    isRefreshing = true

    try {
      const newToken = await auth.refresh()

      processQueue(null, newToken)
      originalRequest.headers.Authorization = `Bearer ${newToken}`
      return api(originalRequest)

    } catch (refreshError) {
      processQueue(refreshError, null)
      useAuthStore().clearAuth()
      window.location.href = '/login'
      return Promise.reject(refreshError)

    } finally {
      isRefreshing = false
    }
  }
)

export default api
