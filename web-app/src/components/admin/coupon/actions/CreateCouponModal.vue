<template>
  <a-modal
    v-model:visible="visible"
    title="Tạo mã giảm giá mới"
    :footer="null"
    :width="600"
    @cancel="handleCancel"
  >
    <a-form
      :model="form"
      :rules="rules"
      layout="vertical"
      @finish="handleSubmit"
      ref="formRef"
    >
      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item name="code" label="Mã coupon">
            <a-input 
              v-model:value="form.code" 
              placeholder="Nhập mã coupon"
              :disabled="loading"
            />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item name="type" label="Loại giảm giá">
            <a-select v-model:value="form.type" placeholder="Chọn loại giảm giá" :disabled="loading">
              <a-select-option value="percent">Phần trăm (%)</a-select-option>
              <a-select-option value="fixed">Cố định (VND)</a-select-option>
            </a-select>
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item name="value" label="Giá trị">
            <a-input-number
              v-model:value="form.value"
              :min="0"
              :max="form.type === 'percent' ? 100 : undefined"
              :precision="form.type === 'percent' ? 0 : 0"
              class="w-full"
              :disabled="loading"
              :formatter="form.type === 'percent' ? (value) => `${value}%` : (value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
              :parser="form.type === 'percent' ? (value) => value.replace('%', '') : (value) => value.replace(/\$\s?|(,*)/g, '')"
            />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item name="usage_limit" label="Giới hạn sử dụng">
            <a-input-number
              v-model:value="form.usage_limit"
              :min="1"
              placeholder="Không giới hạn"
              class="w-full"
              :disabled="loading"
            />
          </a-form-item>
        </a-col>
      </a-row>

      <a-form-item name="min_order_amount" label="Giá trị đơn hàng tối thiểu">
        <a-input-number
          v-model:value="form.min_order_amount"
          :min="0"
          placeholder="Không yêu cầu"
          class="w-full"
          :disabled="loading"
          :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
          :parser="(value) => value.replace(/\$\s?|(,*)/g, '')"
        />
      </a-form-item>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item name="valid_from" label="Ngày bắt đầu">
            <a-date-picker
              v-model:value="form.valid_from"
              class="w-full"
              format="DD/MM/YYYY"
              :disabled="loading"
            />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item name="valid_to" label="Ngày kết thúc">
            <a-date-picker
              v-model:value="form.valid_to"
              class="w-full"
              format="DD/MM/YYYY"
              :disabled="loading"
              :disabled-date="disabledEndDate"
            />
          </a-form-item>
        </a-col>
      </a-row>

      <a-form-item name="description" label="Mô tả">
        <a-textarea
          v-model:value="form.description"
          :rows="3"
          placeholder="Nhập mô tả cho mã giảm giá"
          :disabled="loading"
        />
      </a-form-item>

      <a-form-item name="is_active" label="Trạng thái">
        <a-switch v-model:checked="form.is_active" :disabled="loading">
          <template #checkedChildren>Hoạt động</template>
          <template #unCheckedChildren>Tạm dừng</template>
        </a-switch>
      </a-form-item>

      <div class="flex justify-end gap-2 mt-6">
        <a-button @click="handleCancel" :disabled="loading">
          Hủy
        </a-button>
        <a-button type="primary" html-type="submit" :loading="loading">
          Tạo mã giảm giá
        </a-button>
      </div>
    </a-form>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import { couponApi } from '@/api/admin/couponApi'
import type { CreateCouponData } from '@/types/coupon'
import { notification } from 'ant-design-vue'
import type { Dayjs } from 'dayjs'

interface Props {
  visible: boolean
}

interface Emits {
  (e: 'update:visible', value: boolean): void
  (e: 'finish'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

const visible = ref(props.visible)
const loading = ref(false)
const formRef = ref()

const form = reactive<CreateCouponData & { valid_from?: Dayjs; valid_to?: Dayjs }>({
  code: '',
  type: 'percent',
  value: 0,
  usage_limit: undefined,
  min_order_amount: undefined,
  valid_from: undefined,
  valid_to: undefined,
  is_active: true,
  description: ''
})

const rules = {
  code: [
    { required: true, message: 'Vui lòng nhập mã coupon' },
    { min: 3, message: 'Mã coupon phải có ít nhất 3 ký tự' }
  ],
  type: [
    { required: true, message: 'Vui lòng chọn loại giảm giá' }
  ],
  value: [
    { required: true, message: 'Vui lòng nhập giá trị' },
    { validator: (_: any, value: number) => value > 0 ? Promise.resolve() : Promise.reject('Giá trị phải lớn hơn 0') }
  ]
}

const disabledEndDate = (endValue: Dayjs) => {
  if (!form.valid_from || !endValue) {
    return false
  }
  return endValue.valueOf() <= form.valid_from.valueOf()
}

const handleSubmit = async () => {
  loading.value = true
  try {
    const data: CreateCouponData = {
      code: form.code.toUpperCase(),
      type: form.type,
      value: form.value,
      usage_limit: form.usage_limit,
      min_order_amount: form.min_order_amount,
      valid_from: form.valid_from?.format('YYYY-MM-DD'),
      valid_to: form.valid_to?.format('YYYY-MM-DD'),
      is_active: form.is_active,
      description: form.description
    }

    await couponApi.createCoupon(data)
    notification.success({ message: 'Tạo mã giảm giá thành công!' })
    handleCancel()
    emit('finish')
  } catch (e: any) {
    notification.error({ 
      message: 'Lỗi tạo mã giảm giá', 
      description: e.message || 'Có lỗi xảy ra khi tạo mã giảm giá'
    })
  } finally {
    loading.value = false
  }
}

const handleCancel = () => {
  formRef.value?.resetFields()
  Object.assign(form, {
    code: '',
    type: 'percent',
    value: 0,
    usage_limit: undefined,
    min_order_amount: undefined,
    valid_from: undefined,
    valid_to: undefined,
    is_active: true,
    description: ''
  })
  emit('update:visible', false)
}

watch(() => props.visible, (newVal) => {
  visible.value = newVal
})

watch(visible, (newVal) => {
  emit('update:visible', newVal)
})
</script>
