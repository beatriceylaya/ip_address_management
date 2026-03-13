import { axiosInstance } from '@/plugins/axios'
import type { AxiosResponse } from 'axios'

export interface LoginPayload {
  email: string
  password: string
}

export interface LoginResponse {
  access_token: string
}

export const loginApi = (payload: LoginPayload): Promise<AxiosResponse<LoginResponse>> => {
  return axiosInstance.post<LoginResponse>('/auth/login', payload)
}