export interface CartItem {
  id: number
  title: string
  price: number
  thumbnail_url: string
  subtitle?: string
  quantity: number
}

export interface OrderPayload {
  items: CartItem[]
  coupon_code?: string | null
}

export interface OrderItem {
  id: number
  course_id: number
  title: string
  price_at_purchase: number
  thumbnail_url: string
  quantity: number
}

export interface Order {
  id: number
  user_id: number
  status: 'pending_payment' | 'paid' | 'canceled' | 'expired'
  total_price: number
  discount_amount: number
  final_price: number
  created_at: string
  updated_at?: string
  items: OrderItem[]
}

export interface PaymentForm {
  paymentMethod: string
  cardName: string
  cardNumber: string
  expiryDate: string
  cvv: string
  saveInfo: boolean
}