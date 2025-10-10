<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8 text-center">
      <!-- Loading State -->
      <div v-if="loading" class="space-y-4">
        <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-500 border-t-transparent mx-auto"></div>
        <h2 class="text-xl font-semibold text-gray-700">Đang xử lý kết quả thanh toán...</h2>
        <p class="text-gray-500">Vui lòng chờ trong giây lát</p>
      </div>

      <!-- Success State -->
      <div v-else-if="paymentResult && paymentResult.status === 'success'" class="space-y-6">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
          <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>

        <div>
          <h2 class="text-2xl font-bold text-green-600 mb-2">Thanh toán thành công!</h2>
          <p class="text-gray-600 mb-4">{{ paymentResult.message }}</p>

          <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-600">Mã giao dịch:</span>
              <span class="font-medium">{{ paymentResult.data?.txnRef }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Số tiền:</span>
              <FormatPrice :price="paymentResult.data?.amount || 0" class="font-medium text-green-600" />
            </div>
            <div v-if="paymentResult.data?.transactionNo" class="flex justify-between">
              <span class="text-gray-600">Mã GD VNPay:</span>
              <span class="font-medium">{{ paymentResult.data.transactionNo }}</span>
            </div>
          </div>
        </div>

        <div class="space-y-3">
          <a-button type="primary" size="large" block @click="goToCourses">
            Vào học ngay
          </a-button>
          <a-button size="large" block @click="goToHome">
            Về trang chủ
          </a-button>
        </div>
      </div>

      <!-- Error State -->
      <div v-else class="space-y-6">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto">
          <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </div>

        <div>
          <h2 class="text-2xl font-bold text-red-600 mb-2">Thanh toán thất bại!</h2>
          <p class="text-gray-600 mb-4">
            {{ paymentResult?.message || 'Có lỗi xảy ra trong quá trình thanh toán' }}
          </p>

          <div v-if="paymentResult?.data" class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-600">Mã giao dịch:</span>
              <span class="font-medium">{{ paymentResult.data.txnRef }}</span>
            </div>
            <div v-if="paymentResult.code" class="flex justify-between">
              <span class="text-gray-600">Mã lỗi:</span>
              <span class="font-medium text-red-600">{{ paymentResult.code }}</span>
            </div>
          </div>
        </div>

        <div class="space-y-3">
          <a-button type="primary" size="large" block @click="goToCart">
            Thử lại
          </a-button>
          <a-button size="large" block @click="goToHome">
            Về trang chủ
          </a-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { notification } from 'ant-design-vue'
import { paymentApi } from '@/api/customer/paymentApi'
import type { PaymentReturnResponse } from '@/types/Payment'

const router = useRouter()
const route = useRoute()

const loading = ref(true)
const paymentResult = ref<PaymentReturnResponse | null>(null)
const firstPaidCourseId = ref<number | null>(null)

const goToCourses = () => {
  localStorage.removeItem('cart_data')
  localStorage.removeItem('vnpay_order_info')

  if (firstPaidCourseId.value) {
    router.push(`/learning/${firstPaidCourseId.value}`)
  } else {
    notification.warning({
      message: 'Không tìm thấy khóa học',
      description: 'Không xác định được khóa học bạn vừa thanh toán, vui lòng kiểm tra lại!',
      duration: 4
    })
    router.push('/my-courses')
  }
}


const goToHome = () => router.push('/')
const goToCart = () => router.push('/cart')

onMounted(async () => {
  try {
    const queryParams = route.query as Record<string, string>
    if (!queryParams.vnp_TxnRef) throw new Error('Thiếu thông tin giao dịch từ VNPay')

    const result = await paymentApi.handleVNPayReturn(queryParams)
    paymentResult.value = result

    if (result.status === 'success') {
      // ✅ Lấy thông tin order đã lưu trước khi thanh toán
      const orderInfo = JSON.parse(localStorage.getItem('vnpay_order_info') || '{}')
      const paidItems = Array.isArray(orderInfo.items) ? orderInfo.items : []

      // ✅ Xác định ID khóa học đầu tiên
      if (paidItems.length > 0) {
        firstPaidCourseId.value = paidItems[0]?.id || paidItems[0]
      }

      // ✅ Xử lý lại giỏ hàng
      const cartData = JSON.parse(localStorage.getItem('cart_data') || '{}')
      const clientAuth = JSON.parse(localStorage.getItem('client_auth') || '{}')
      const userId = clientAuth?.user?.id

      if (userId && Array.isArray(cartData[userId])) {
        const updatedCart = cartData[userId].filter(
          (item: any) => !paidItems.some((p: any) => p.id === item.id || p === item)
        )

        cartData[userId] = updatedCart
        localStorage.setItem('cart_data', JSON.stringify(cartData))
      }

      localStorage.removeItem('vnpay_order_info')

      notification.success({
        message: 'Thanh toán thành công!',
        description: 'Các khóa học đã được đăng ký thành công!',
        duration: 5
      })
    } else {
      notification.error({
        message: 'Thanh toán thất bại!',
        description: result.message,
        duration: 5
      })
    }
  } catch (error: any) {
    console.error('VNPay return error:', error)
    paymentResult.value = {
      status: 'error',
      message: error?.message || 'Có lỗi xảy ra khi xử lý kết quả thanh toán'
    }
  } finally {
    loading.value = false
  }
})

</script>
