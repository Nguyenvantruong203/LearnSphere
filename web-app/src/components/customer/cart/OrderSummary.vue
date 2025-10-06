<template>
  <div class="bg-white rounded-3xl shadow-lg p-8">
    <h1 class="text-4xl font-semibold text-secondary mb-8">Giỏ hàng của bạn</h1>

    <!-- Alert when cart is empty -->
    <div v-if="orderItems.length === 0" class="text-center py-12">
      <div class="text-gray-400 text-xl mb-4">Giỏ hàng của bạn đang trống</div>
      <a-button type="primary" size="large" @click="$router.push('/courses')">
        Khám phá khóa học
      </a-button>
    </div>

    <!-- Cart Content -->
    <div v-else class="grid lg:grid-cols-3 gap-12">
      <!-- Left Side - Cart Items -->
      <div class="grid-cols-2 lg:col-span-2">
        <h2 class="text-2xl font-semibold text-secondary mb-6">Khóa học đã chọn</h2>

        <!-- Course Items -->
        <div class="space-y-6 mb-8">
          <div v-for="item in orderItems" :key="item.id"
            class="flex gap-4 p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors">
            <div class="relative">
              <img :src="item.thumbnail_url" :alt="item.title" class="w-32 h-20 object-cover rounded-xl" />
              <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-8 h-8 bg-white bg-opacity-80 rounded-full flex items-center justify-center">
                  <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 5v10l7-5-7-5z" />
                  </svg>
                </div>
              </div>
            </div>
            <div class="flex-1">
              <h3 class="text-lg font-medium text-black mb-1">{{ item.title }}</h3>
              <p class="text-sm text-gray-600 mb-2">{{ item.subtitle || 'Khóa học chất lượng cao' }}</p>
              <div class="flex items-center justify-between">
                <p class="text-xl font-semibold text-primary">
                  {{ formatPrice(item.price) }}
                </p>
                <a-button type="text" danger size="small" @click="removeFromCart(item.id)">
                  Xóa
                </a-button>
              </div>
            </div>
          </div>
        </div>

        <!-- Coupon Section -->
        <div class="mb-8">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Mã giảm giá</h3>
          <div class="flex gap-3">
            <a-input v-model:value="couponCode" placeholder="Nhập mã giảm giá" class="flex-1" />
            <a-button type="default" @click="applyCoupon" :loading="couponLoading">
              Áp dụng
            </a-button>
          </div>
          <div v-if="appliedCoupon" class="mt-2 text-green-600 text-sm">
            ✓ Đã áp dụng mã giảm giá: {{ appliedCoupon }}
          </div>
        </div>
      </div>

      <!-- Right Side - Payment Form -->
      <div class="lg:col-span-1">
        <!-- Order Summary -->
        <div class="bg-accent bg-opacity-10 rounded-2xl p-6 mb-8">
          <h3 class="text-xl font-semibold text-secondary mb-4">Tóm tắt đơn hàng</h3>

          <div class="space-y-3 mb-4">
            <div class="flex justify-between">
              <span class="text-gray-600">Tạm tính</span>
              <span class="font-medium">{{ formatPrice(subtotal) }}</span>
            </div>

            <div v-if="couponDiscount > 0" class="flex justify-between text-green-600">
              <span>Giảm giá ({{ couponDiscount }}%)</span>
              <span>-{{ formatPrice(discountAmount) }}</span>
            </div>

            <div class="border-t pt-3 mt-3">
              <div class="flex justify-between items-center">
                <span class="text-lg font-semibold">Tổng cộng</span>
                <span class="text-xl font-bold text-primary">{{ formatPrice(total) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Form -->
        <div class="space-y-6">
          <h3 class="text-xl font-semibold text-secondary">Thông tin thanh toán</h3>

          <a-form :model="paymentForm" :rules="formRules" layout="vertical" @finish="handlePayment">
            <!-- Payment Method Selection -->
            <a-form-item label="Phương thức thanh toán">
              <div class="grid grid-cols-3 gap-3">
                <div v-for="method in paymentMethods" :key="method.id" class="relative cursor-pointer"
                  @click="paymentForm.paymentMethod = method.id">
                  <div class="border-2 rounded-lg p-3 text-center hover:border-primary transition-colors"
                    :class="paymentForm.paymentMethod === method.id ? 'border-primary bg-primary bg-opacity-5' : 'border-gray-200'">
                    <img :src="method.image" :alt="method.name" class="h-8 mx-auto mb-2" />
                    <div class="text-xs font-medium">{{ method.name }}</div>
                  </div>
                </div>
              </div>
            </a-form-item>

            <div class="mb-4">
              <!-- Card Information -->
              <div v-if="paymentForm.paymentMethod === 'visa'" class="space-y-4">
                <a-form-item name="cardName" label="Tên trên thẻ">
                  <a-input v-model:value="paymentForm.cardName" placeholder="Nhập tên trên thẻ" size="large" />
                </a-form-item>

                <a-form-item name="cardNumber" label="Số thẻ">
                  <a-input v-model:value="paymentForm.cardNumber" placeholder="1234 5678 9012 3456" size="large"
                    :maxlength="19" @input="formatCardNumber" />
                </a-form-item>

                <div class="grid grid-cols-2 gap-4">
                  <a-form-item name="expiryDate" label="Ngày hết hạn">
                    <a-input v-model:value="paymentForm.expiryDate" placeholder="MM/YY" size="large" :maxlength="5"
                      @input="formatExpiryDate" />
                  </a-form-item>

                  <a-form-item name="cvv" label="CVV">
                    <a-input v-model:value="paymentForm.cvv" placeholder="123" size="large" :maxlength="4"
                      type="password" />
                  </a-form-item>
                </div>
              </div>
              <!-- VNPay Payment Info -->
              <div v-else-if="paymentForm.paymentMethod === 'vnpay'" class="p-4 bg-blue-50 rounded-lg">
                <div class="text-center">
                  <div class="text-blue-600 font-medium mb-2">Thanh toán qua VNPay</div>
                  <div class="text-sm text-gray-600">Bạn sẽ được chuyển đến cổng VNPay để hoàn tất thanh toán</div>
                </div>
              </div>

              <!-- MoMo Payment Info -->
              <div v-else class="p-4 bg-pink-50 rounded-lg">
                <div class="text-center">
                  <div class="text-pink-600 font-medium mb-2">Thanh toán qua MoMo</div>
                  <div class="text-sm text-gray-600">Bạn sẽ được chuyển đến ứng dụng MoMo để hoàn tất thanh toán</div>
                </div>
              </div>
            </div>


            <!-- Submit Button -->
            <a-form-item>
              <a-button type="primary" html-type="submit" size="large" block :loading="paymentLoading"
                :disabled="orderItems.length === 0">
                <template v-if="paymentForm.paymentMethod === 'momo'">
                  Thanh toán qua MoMo - {{ formatPrice(total) }}
                </template>
                <template v-else-if="paymentForm.paymentMethod === 'vnpay'">
                  Thanh toán qua VNPAY - {{ formatPrice(total) }}
                </template>
                <template v-else>
                  Xác nhận thanh toán - {{ formatPrice(total) }}
                </template>
              </a-button>
            </a-form-item>
          </a-form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { notification } from 'ant-design-vue'
import type { CartItem, PaymentForm } from '@/types/Order'
import { paymentApi } from '@/api/customer/paymentApi'
import { couponApi } from '@/api/customer/couponApi'
import { CartStorage } from '@/helpers/cartStorage'

const router = useRouter()

const orderItems = ref<CartItem[]>([])

// Coupon
const couponCode = ref('')
const couponLoading = ref(false)
const appliedCoupon = ref('')
const couponDiscount = ref(0)
const paymentLoading = ref(false)

const paymentForm = reactive<PaymentForm>({
  paymentMethod: 'vnpay',
  cardName: '',
  cardNumber: '',
  expiryDate: '',
  cvv: '',
  saveInfo: false
})

// Payment Methods
const paymentMethods = [
  {
    id: 'vnpay',
    name: 'VNPay',
    image: 'https://vinadesign.vn/uploads/images/2023/05/vnpay-logo-vinadesign-25-12-57-55.jpg'
  },
  {
    id: 'visa',
    name: 'Visa',
    image: 'https://static.codia.ai/image/2025-10-02/71mqC2CJqp.png'
  },
  {
    id: 'momo',
    name: 'MoMo',
    image: 'https://static.codia.ai/image/2025-10-02/UTymyZQQ11.png'
  }
]

// Helper: lấy userId từ localStorage
const getUserId = (): string | null => {
  const auth = JSON.parse(localStorage.getItem('client_auth') || '{}')
  return auth?.user?.id ? String(auth.user.id) : null
}

// Load cart theo user
onMounted(() => {
  const userId = getUserId()
  if (!userId) return
  orderItems.value = CartStorage.getCart(userId).map((item: any) => ({
    ...item,
    price: Number(item.price) || 0,
  }))
})

// Computed values
const subtotal = computed(() => {
  return orderItems.value.reduce((sum, item) => sum + (Number(item.price) || 0), 0)
})

const discountAmount = computed(() => (subtotal.value * couponDiscount.value) / 100)

const total = computed(() => subtotal.value - discountAmount.value)

// Methods
const formatPrice = (price: number) =>
  new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price)

const removeFromCart = (courseId: number) => {
  const userId = getUserId()
  if (!userId) return

  CartStorage.removeItem(userId, courseId)
  orderItems.value = CartStorage.getCart(userId)

  notification.success({ message: 'Đã xóa khóa học khỏi giỏ hàng' })
}

const applyCoupon = async () => {
  if (!couponCode.value.trim()) {
    notification.warning({ message: 'Vui lòng nhập mã giảm giá' })
    return
  }

  couponLoading.value = true
  try {
    const res = await couponApi.applyCoupon(couponCode.value, subtotal.value)
    if (res.status === 'success') {
      appliedCoupon.value = res.data.code
      couponDiscount.value = (res.data.discount / subtotal.value) * 100

      notification.success({
        message: 'Áp dụng mã giảm giá thành công',
        description: `Bạn được giảm ${formatPrice(res.data.discount)}`
      })
    } else {
      notification.error({ message: res.message || 'Mã giảm giá không hợp lệ' })
    }
  } catch (error: any) {
    notification.error({
      message: 'Có lỗi xảy ra khi áp dụng mã giảm giá',
      description: error?.message || 'Vui lòng thử lại sau'
    })
  } finally {
    couponLoading.value = false
  }
}

const formatCardNumber = (e: Event) => {
  const target = e.target as HTMLInputElement
  let value = target.value.replace(/\D/g, '')
  value = value.replace(/(\d{4})(?=\d)/g, '$1 ')
  paymentForm.cardNumber = value
}

const formatExpiryDate = (e: Event) => {
  const target = e.target as HTMLInputElement
  let value = target.value.replace(/\D/g, '')
  if (value.length >= 2) {
    value = value.substring(0, 2) + '/' + value.substring(2, 4)
  }
  paymentForm.expiryDate = value
}

const handlePayment = async () => {
  if (orderItems.value.length === 0) {
    notification.warning({ message: 'Giỏ hàng trống' })
    return
  }

  const userId = getUserId()
  if (!userId) return

  paymentLoading.value = true
  try {
    if (paymentForm.paymentMethod === 'momo') {
      // Giả lập MoMo
      notification.success({
        message: 'Thanh toán thành công!',
        description: 'Bạn đã đăng ký khóa học thành công. Chúc bạn học tập vui vẻ!'
      })
      CartStorage.clearCart(userId)
      setTimeout(() => router.push('/courses'), 1000)
    } else if (paymentForm.paymentMethod === 'vnpay') {
      const res = await paymentApi.createVNPayPayment(
        total.value,
        userId,
        orderItems.value.map((item) => item.id),
        appliedCoupon.value || undefined
      )

      if (res.status === 'success' && res.url) {
        localStorage.setItem('vnpay_order_info', JSON.stringify({
          amount: total.value,
          items: orderItems.value,
          txnRef: res.txnRef,
          coupon: appliedCoupon.value,
          discount: couponDiscount.value
        }))

        window.location.href = res.url
      } else {
        throw new Error(res.message || 'Không thể tạo URL thanh toán VNPay')
      }
    } else {
      // Giả lập Visa
      notification.success({
        message: 'Thanh toán thành công!',
        description: 'Bạn đã đăng ký khóa học thành công. Chúc bạn học tập vui vẻ!'
      })
      CartStorage.clearCart(userId)
      setTimeout(() => router.push('/courses'), 1000)
    }
  } catch (err: any) {
    console.error('Payment error:', err)
    notification.error({
      message: 'Thanh toán thất bại',
      description: err?.message || 'Vui lòng kiểm tra lại thông tin và thử lại'
    })
  } finally {
    paymentLoading.value = false
  }
}
</script>
