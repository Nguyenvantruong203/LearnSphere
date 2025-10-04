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
              <span class="font-medium text-green-600">{{ formatPrice(paymentResult.data?.amount || 0) }}</span>
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
import { paymentApi, type PaymentReturnResponse } from '@/api/customer/paymentApi'

const router = useRouter()
const route = useRoute()

const loading = ref(true)
const paymentResult = ref<PaymentReturnResponse | null>(null)

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND'
  }).format(price)
}

const goToCourses = () => {
  // Xóa giỏ hàng và thông tin đơn hàng
  localStorage.removeItem('cart_items')
  localStorage.removeItem('vnpay_order_info')
  router.push('/courses')
}

const goToHome = () => {
  router.push('/')
}

const goToCart = () => {
  router.push('/cart')
}

onMounted(async () => {
  try {
    // Lấy tất cả query parameters từ URL
    const queryParams = route.query as Record<string, string>
    
    if (!queryParams.vnp_TxnRef) {
      throw new Error('Thiếu thông tin giao dịch từ VNPay')
    }

    // Gọi API để xử lý kết quả
    const result = await paymentApi.handleVNPayReturn(queryParams)
    paymentResult.value = result

    // Nếu thanh toán thành công, có thể thực hiện các tác vụ bổ sung
    if (result.status === 'success') {
      // TODO: Có thể gọi API để cập nhật đơn hàng, cấp quyền truy cập khóa học...
      
      notification.success({
        message: 'Thanh toán thành công!',
        description: 'Bạn đã đăng ký khóa học thành công. Chúc bạn học tập vui vẻ!',
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
    
    notification.error({
      message: 'Lỗi xử lý thanh toán',
      description: error?.message || 'Có lỗi xảy ra khi xử lý kết quả thanh toán',
      duration: 5
    })
  } finally {
    loading.value = false
  }
})
</script>
