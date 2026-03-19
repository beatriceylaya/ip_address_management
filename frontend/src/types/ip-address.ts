export interface IpAddressUser {
  id: number
  name: string
}

export interface IpAddress {
  id: number
  user_id: number
  user?: IpAddressUser | null
  created_by: string
  ip_address: string
  label: string
  comment: string
  created_at: string
}

export type CreateIpAddressPayload = Pick<IpAddress, 'ip_address' | 'label' | 'comment'>
export type UpdateIpAddressPayload = Partial<CreateIpAddressPayload>