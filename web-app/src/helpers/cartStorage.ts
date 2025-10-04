export class CartStorage {
  private static KEY = 'cart_data'

  private static getData(): Record<string, any[]> {
    const raw = localStorage.getItem(this.KEY)
    return raw ? JSON.parse(raw) : {}
  }

  private static setData(data: Record<string, any[]>) {
    localStorage.setItem(this.KEY, JSON.stringify(data))
  }

  static getCart(userId: string) {
    const data = this.getData()
    return data[userId] || []
  }

  static saveCart(userId: string, cart: any[]) {
    const data = this.getData()
    data[userId] = cart
    this.setData(data)
    // 🔔 bắn event để header update ngay
    window.dispatchEvent(new Event('cartUpdated'))
  }

  static addItem(userId: string, item: any) {
    const cart = this.getCart(userId)
    if (!cart.find((i) => i.id === item.id)) {
      cart.push(item)
      this.saveCart(userId, cart)
    }
  }

  static removeItem(userId: string, itemId: number) {
    let cart = this.getCart(userId)
    cart = cart.filter((i) => i.id !== itemId)
    this.saveCart(userId, cart)
    window.dispatchEvent(new Event('cartUpdated'))
  }

  static clearCart(userId: string) {
    const data = this.getData()
    delete data[userId]
    this.setData(data)
    window.dispatchEvent(new Event('cartUpdated'))
  }
}
