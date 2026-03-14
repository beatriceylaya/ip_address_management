import api from "@/api/axios";
import type { IpAddress, CreateIpAddressPayload, UpdateIpAddressPayload } from "@/types/ip-address";
import type { ApiResponse, PaginatedResponse } from "@/types/common";

export const ipAddressService = {
  getAll: (page = 1, perPage = 15) =>
    api.get<PaginatedResponse<IpAddress>>('/ip-addresses', { params: { page, per_page: perPage } }),

  getById: (id: number) =>
    api.get<ApiResponse<IpAddress>>(`/ip-addresses/${id}`),

  create: (payload: CreateIpAddressPayload) =>
    api.post<ApiResponse<IpAddress>>(`/ip-addresses`, payload),

  update: (id: number, payload: UpdateIpAddressPayload) =>
    api.patch<ApiResponse<IpAddress>>(`/ip-addresses/${id}`, payload),

  delete: (id: number) =>
    api.delete<ApiResponse<IpAddress>>(`/ip-addresses/${id}`)
}