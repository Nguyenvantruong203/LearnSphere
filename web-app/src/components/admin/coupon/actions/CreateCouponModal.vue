<template>
  <a-modal v-model:visible="visible" title="Create New Coupon" :footer="null" :width="600" @cancel="handleCancel">
    <a-form :model="form" :rules="rules" layout="vertical" @finish="handleSubmit" ref="formRef">
      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item name="code" label="Coupon Code">
            <a-input v-model:value="form.code" placeholder="Enter coupon code" :disabled="loading" />
          </a-form-item>
        </a-col>

        <a-col :span="12">
          <a-form-item name="value" label="Discount">
            <a-space-compact class="w-full">
              <a-select v-model:value="form.type" :disabled="loading" style="width: 40%;">
                <a-select-option value="percent">%</a-select-option>
                <a-select-option value="fixed">VND</a-select-option>
              </a-select>

              <a-input-number v-model:value="form.value" :min="0" :max="form.type === 'percent' ? 100 : undefined"
                :precision="0" class="w-full" :disabled="loading" placeholder="Enter discount value"
                :formatter="formatter" :parser="parser" />

            </a-space-compact>
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item name="usage_limit" label="Usage Limit">
            <a-input-number v-model:value="form.usage_limit" :min="1" placeholder="Unlimited" class="w-full"
              :disabled="loading" />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item name="min_order_amount" label="Minimum Order Value">
            <a-input-number v-model:value="form.min_order_amount" :min="0" placeholder="No minimum required"
              class="w-full" :disabled="loading"
              :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
              :parser="(value) => value.replace(/\$\s?|(,*)/g, '')" />
          </a-form-item>
        </a-col>
      </a-row>
      
      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item name="valid_from" label="Start Date">
            <a-date-picker v-model:value="form.valid_from" class="w-full" format="DD/MM/YYYY" :disabled="loading" />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item name="valid_to" label="End Date">
            <a-date-picker v-model:value="form.valid_to" class="w-full" format="DD/MM/YYYY" :disabled="loading"
              :disabled-date="disabledEndDate" />
          </a-form-item>
        </a-col>
      </a-row>

      <a-form-item name="description" label="Description">
        <a-textarea v-model:value="form.description" :rows="3" placeholder="Enter a description for this coupon"
          :disabled="loading" />
      </a-form-item>

      <a-form-item name="is_active" label="Status">
        <a-switch v-model:checked="form.is_active" :disabled="loading">
          <template #checkedChildren>Active</template>
          <template #unCheckedChildren>Paused</template>
        </a-switch>
      </a-form-item>

      <div class="flex justify-end gap-2 mt-6">
        <a-button @click="handleCancel" :disabled="loading">
          Cancel
        </a-button>
        <a-button type="primary" html-type="submit" :loading="loading">
          Create Coupon
        </a-button>
      </div>
    </a-form>
  </a-modal>
</template>

<script setup lang="ts">
import { ref, reactive, watch, computed } from 'vue'
import { couponApi } from '@/api/admin/couponApi'
import type { CreateCouponData } from '@/types/Coupon'
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
  value: undefined,
  usage_limit: undefined,
  min_order_amount: undefined,
  valid_from: undefined,
  valid_to: undefined,
  is_active: true,
  description: ''
})

const rules = {
  code: [
    { required: true, message: 'Please enter a coupon code' },
    { min: 3, message: 'Coupon code must be at least 3 characters long' }
  ],
  type: [
    { required: true, message: 'Please select a discount type' }
  ],
  value: [
    { required: true, message: 'Please enter a value' },
    { validator: (_: any, value: number) => value > 0 ? Promise.resolve() : Promise.reject('Value must be greater than 0') }
  ]
}

const formatter = computed(() => {
  if (form.type === 'percent') {
    return (value: string | number | undefined) =>
      value ? `${value}%` : ''
  }
  return (value: string | number | undefined) =>
    value ? `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',') : ''
})

const parser = computed(() => {
  if (form.type === 'percent') {
    return (value: string) => value.replace('%', '')
  }
  return (value: string) => value.replace(/\$\s?|(,*)/g, '')
})


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
    notification.success({ message: 'Coupon created successfully!' })
    handleCancel()
    emit('finish')
  } catch (e: any) {
    notification.error({
      message: 'Coupon creation failed',
      description: e.message || 'An error occurred while creating the coupon'
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
    value: undefined,
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
