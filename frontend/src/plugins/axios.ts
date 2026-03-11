import type { App } from 'vue'
import axios, { type AxiosInstance } from 'axios'

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
      $axios: AxiosInstance
  }
}

export default {
  install: (app: App, options?: { baseURL: string }) => {
    const instance = axios.create({
        baseURL: options?.baseURL || import.meta.env.VITE_API_URL,
        headers: {
            'Content-Type': 'application/json',
        },
    })
    app.config.globalProperties.$axios = instance
  }
}