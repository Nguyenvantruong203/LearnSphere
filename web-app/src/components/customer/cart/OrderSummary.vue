<template>
  <div class="bg-white rounded-3xl shadow-lg p-8">
    <h1 class="text-4xl font-semibold text-secondary mb-8">Your Cart</h1>

    <div v-if="orderItems.length === 0" class="text-center py-12">
      <div class="text-gray-400 text-xl mb-4">Your cart is empty</div>
      <a-button type="primary" size="large" @click="$router.push('/courses')">
        Explore Courses
      </a-button>
    </div>

    <div v-else class="grid lg:grid-cols-3 gap-12">
      <div class="grid-cols-2 lg:col-span-2">
        <h2 class="text-2xl font-semibold text-secondary mb-6">Selected Courses</h2>

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
              <p class="text-sm text-gray-600 mb-2">
                {{ item.subtitle || 'High-quality course' }}
              </p>
              <div class="flex items-center justify-between">
                <FormatPrice :price="item?.price" class="text-xl font-semibold text-primary" />
                <a-button type="text" danger size="small" @click="removeFromCart(item.id)">
                  <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                    viewBox="0 0 24 24"><!-- Icon from Material Symbols by Google - https://github.com/google/material-design-icons/blob/master/LICENSE -->
                    <path fill="currentColor"
                      d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zM9 17h2V8H9zm4 0h2V8h-2zM7 6v13z" />
                  </svg>
                </a-button>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-8">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Discount Code</h3>
          <div class="flex gap-3">
            <a-input v-model:value="couponCode" placeholder="Enter discount code" class="flex-1" />
            <a-button type="default" @click="applyCoupon" :loading="couponLoading">
              Apply
            </a-button>
          </div>
          <div v-if="appliedCoupon" class="mt-2 text-green-600 text-sm">
            ✓ Coupon applied: {{ appliedCoupon }}
          </div>
        </div>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-accent bg-opacity-10 rounded-2xl p-6 mb-8">
          <h3 class="text-xl font-semibold text-secondary mb-4">Order Summary</h3>

          <div class="space-y-3 mb-4">
            <div class="flex justify-between">
              <span class="text-gray-600">Subtotal</span>
              <FormatPrice :price="subtotal" class="font-medium" />
            </div>

            <div v-if="couponDiscount > 0" class="flex justify-between text-green-600">
              <span>Discount ({{ couponDiscount }}%)</span>
              <span>-
                <FormatPrice :price="discountAmount" class="text-xl font-semibold text-primary" />
              </span>
            </div>

            <div class="border-t pt-3 mt-3">
              <div class="flex justify-between items-center">
                <span class="text-lg font-semibold">Total</span>
                <FormatPrice :price="total" class="ext-xl font-bold text-primary" />
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <h3 class="text-xl font-semibold text-secondary">Payment Information</h3>

          <a-form :model="paymentForm" layout="vertical" @finish="handlePayment">
            <a-form-item label="Payment Method">
              <div class="grid grid-cols-3 gap-3">
                <div v-for="method in paymentMethods" :key="method.id" class="relative cursor-pointer"
                  @click="paymentForm.paymentMethod = method.id">
                  <div class="border-2 rounded-lg p-3 text-center hover:border-primary transition-colors" :class="paymentForm.paymentMethod === method.id
                    ? 'border-primary bg-primary bg-opacity-5'
                    : 'border-gray-200'">
                    <img :src="method.image" :alt="method.name" class="h-8 mx-auto mb-2" />
                    <div class="text-xs font-medium">{{ method.name }}</div>
                  </div>
                </div>
              </div>
            </a-form-item>

            <div class="mb-4">
              <div v-if="paymentForm.paymentMethod === 'visa'" class="space-y-4">
                <a-form-item name="cardName" label="Cardholder Name">
                  <a-input v-model:value="paymentForm.cardName" placeholder="Enter cardholder name" size="large" />
                </a-form-item>

                <a-form-item name="cardNumber" label="Card Number">
                  <a-input v-model:value="paymentForm.cardNumber" placeholder="1234 5678 9012 3456" size="large"
                    :maxlength="19" @input="formatCardNumber" />
                </a-form-item>

                <div class="grid grid-cols-2 gap-4">
                  <a-form-item name="expiryDate" label="Expiry Date">
                    <a-input v-model:value="paymentForm.expiryDate" placeholder="MM/YY" size="large" :maxlength="5"
                      @input="formatExpiryDate" />
                  </a-form-item>

                  <a-form-item name="cvv" label="CVV">
                    <a-input v-model:value="paymentForm.cvv" placeholder="123" size="large" :maxlength="4"
                      type="password" />
                  </a-form-item>
                </div>
              </div>

              <div v-else-if="paymentForm.paymentMethod === 'vnpay'" class="p-4 bg-blue-50 rounded-lg">
                <div class="text-center">
                  <div class="text-blue-600 font-medium mb-2">Payment via VNPay</div>
                  <div class="text-sm text-gray-600">
                    You will be redirected to the VNPay gateway to complete your payment.
                  </div>
                </div>
              </div>

              <!-- MoMo Payment Info -->
              <div v-else class="p-4 bg-pink-50 rounded-lg">
                <div class="text-center">
                  <div class="text-pink-600 font-medium mb-2">Payment via MoMo</div>
                  <div class="text-sm text-gray-600">
                    You will be redirected to the MoMo app to complete your payment.
                  </div>
                </div>
              </div>
            </div>

            <a-form-item>
              <a-button type="primary" html-type="submit" size="large" block :loading="paymentLoading"
                :disabled="orderItems.length === 0">
                <template v-if="paymentForm.paymentMethod === 'momo'">
                  Pay with MoMo -
                  <FormatPrice :price="total" />
                </template>
                <template v-else-if="paymentForm.paymentMethod === 'vnpay'">
                  Pay with VNPay -
                  <FormatPrice :price="total" />
                </template>
                <template v-else>
                  Confirm Payment -
                  <FormatPrice :price="total" />
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

const getUserId = (): string | null => {
  const auth = JSON.parse(localStorage.getItem('client_auth') || '{}')
  return auth?.user?.id ? String(auth.user.id) : null
}

onMounted(() => {
  const userId = getUserId()
  if (!userId) return
  orderItems.value = CartStorage.getCart(userId).map((item: any) => ({
    ...item,
    price: Number(item.price) || 0,
  }))
})

const subtotal = computed(() => {
  return orderItems.value.reduce((sum, item) => sum + (Number(item.price) || 0), 0)
})

const discountAmount = computed(() => (subtotal.value * couponDiscount.value) / 100)

const total = computed(() => subtotal.value - discountAmount.value)

const removeFromCart = (courseId: number) => {
  const userId = getUserId()
  if (!userId) return

  CartStorage.removeItem(userId, courseId)
  orderItems.value = CartStorage.getCart(userId)

  notification.success({ message: 'Course removed from cart' })
}

function formatPriceVN(amount: number | string) {
  const num = parseFloat(String(amount).replace(/[^0-9.-]/g, '')) || 0
  return num.toLocaleString('vi-VN', {
    style: 'currency',
    currency: 'VND',
    minimumFractionDigits: 0
  })
}


const applyCoupon = async () => {
  if (!couponCode.value.trim()) {
    notification.warning({ message: 'Please enter a discount code' })
    return
  }

  couponLoading.value = true
  try {
    const res = await couponApi.applyCoupon(couponCode.value, subtotal.value)
    if (res.status === 'success') {
      appliedCoupon.value = res.data.code
      couponDiscount.value = (res.data.discount / subtotal.value) * 100

      notification.success({
        message: 'Coupon applied successfully',
        description: `You saved ${formatPriceVN(res.data.discount)} 🎉`
      })

    } else {
      notification.error({ message: res.message || 'Invalid discount code' })
    }
  } catch (error: any) {
    notification.error({
      message: 'Failed to apply coupon',
      description: error?.message || 'Please try again later'
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
    notification.warning({ message: 'Your cart is empty' })
    return
  }

  const userId = getUserId()
  if (!userId) return

  paymentLoading.value = true
  try {
    if (paymentForm.paymentMethod === 'momo') {
      notification.success({
        message: 'Payment successful!',
        description: 'You have successfully enrolled in the course. Happy learning!'
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
        throw new Error(res.message || 'Unable to create VNPay payment URL')
      }
    } else {
      notification.success({
        message: 'Payment successful!',
        description: 'You have successfully enrolled in the course. Happy learning!'
      })
      CartStorage.clearCart(userId)
      setTimeout(() => router.push('/courses'), 1000)
    }
  } catch (err: any) {
    console.error('Payment error:', err)
    notification.error({
      message: 'Payment failed',
      description: err?.message || 'Please check your information and try again'
    })
  } finally {
    paymentLoading.value = false
  }
}
</script>
