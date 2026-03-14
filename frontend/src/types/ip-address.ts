export interface IpAddress {
  id: number
  user_id: number
  ip_address: string
  label: string
  comment: string
  created_at: string
}

export type CreateIpAddressPayload = Omit<IpAddress, 'id' | 'user_id' | 'created_at'>
export type UpdateIpAddressPayload = Partial<CreateIpAddressPayload>