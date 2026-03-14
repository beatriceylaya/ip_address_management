import api from "@/api/axios";
import type { AuthResponse, LoginPayload, TokenResponse } from "@/types/auth";

export const authService = {
  login: (payload: LoginPayload) => 
    api.post<AuthResponse>('/auth/login', payload),

  logout: () =>
    api.post('/auth/logout'),

  refresh: (refreshToken: string) =>
    api.post<TokenResponse>('/auth/refresh', {}, {
      headers: { Authorization: `Bearer ${refreshToken}` }
    }),

  profile: () =>
    api.get<{user: AuthResponse['user'] }>('/auth/profile')
}