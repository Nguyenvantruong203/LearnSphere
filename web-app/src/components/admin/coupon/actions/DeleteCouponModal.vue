<template>
  <a-modal
    v-model:visible="visible"
    title="Xác nhận xóa mã giảm giá"
    :footer="null"
    :width="500"
    @cancel="handleCancel"
  >
    <div class="mb-4">
      <a-alert
        message="Cảnh báo"
        description="Bạn có chắc chắn muốn xóa mã giảm giá này? Hành động này không thể hoàn tác."
        type="warning"
        show-icon
      />
    </div>

    <div v-if="coupon" class="bg-gray-50 p-4 rounded-lg mb-6">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <span class="text-gray-600">Mã coupon:</span>
          <div class="font-semibold">{{ coupon.code }}</div>
        </div>
        <div>
          <span class="text-gray-600">Loại:</span>
          <div class="font-semibold">
            {{ coupon.type === 'percent' ? 'Phần trăm' : 'Cố định' }}
          </div>
        </div>
        <div>
          <span class="text-gray-600">Giá trị:</span>
          <div class="font-semibold">
            {{ coupon.type === 'percent' ? coupon.value + '%' : formatMoney(coupon.value) }}
          </div>
        </div>
        <div>
          <span class="text-gray-600">Đã sử dụng:</span>
          <div class="font-semibold">{{ coupon.used_count }} lần</div>
        </div>
        <div v-if="coupon.description" class="col-span-2">
          <span class="text-gray-600">Mô tả:</span>
          <div class="font-semibold">{{ coupon.description }}</div>
        </div>
      </div>
    </div>

    <div class="flex justify-end gap-2">
      <a-button @click="handleCancel" :disabled="loading">
        Hủy
      </a-button>
      <a-button type="primary" danger :loading="loading" @click="handleDelete">
        Xóa mã giảm giá
      </a-button>
    </div>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { couponApi } from '@/api/admin/couponApi'
import type { Coupon } from '@/types/coupon'
import { notification } from 'ant-design-vue'

interface Props {
  visible: boolean
  coupon: Coupon | null
}

interface Emits {
  (e: 'update:visible', value: boolean): void
  (e: 'finish'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

const visible = ref(props.visible)
const loading = ref(false)

const formatMoney = (amount: number) => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND'
  }).format(amount)
}

const handleDelete = async () => {
  if (!props.coupon) return

  loading.value = true
  try {
    await couponApi.deleteCoupon(props.coupon.id)
    notification.success({ message: 'Xóa mã giảm giá thành công!' })
    handleCancel()
    emit('finish')
  } catch (e: any) {
    notification.error({ 
      message: 'Lỗi xóa mã giảm giá', 
      description: e.message || 'Có lỗi xảy ra khi xóa mã giảm giá'
    })
  } finally {
    loading.value = false
  }
}

const handleCancel = () => {
  emit('update:visible', false)
}

watch(() => props.visible, (newVal) => {
  visible.value = newVal
})

watch(visible, (newVal) => {
  emit('update:visible', newVal)
})
</script>
