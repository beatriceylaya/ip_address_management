import { useAuthStore } from "@/stores/auth"
import type { LoginPayload } from "@/modules/auth/authApi"

export function useAuth() {
  const store = useAuthStore()

  const login = async (payload: LoginPayload) => {
    await store.login(payload)
  }

  return {
    login
  }
}
