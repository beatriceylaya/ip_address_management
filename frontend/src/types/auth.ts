export interface AuthUser {
  id: number
  name: string
  email: string
}

export interface LoginPayload {
  email: string
  password: string
}

export interface TokenResponse {
  access_token: string
  refresh_token: string
  token_type: 'bearer'
  expires_in: number
}

export interface AuthResponse {
  user: AuthUser
  access_token: string,
  refresh_token: string
}