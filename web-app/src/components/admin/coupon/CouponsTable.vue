<template>
  <div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="mb-6 flex justify-between items-center">
      <a-input-search placeholder="Search by code or description..." class="w-72" v-model:value="searchQuery"
        @search="fetchCoupons" />
      <a-button type="primary" @click="showCreateModal">
        <span class="flex justify-center items-center">
          <PlusOutlined /> Add Coupon
        </span>
      </a-button>
    </div>

    <div class="table-container h-[calc(100vh-245px)]">
      <a-table :columns="columns" :data-source="coupons" :pagination="false" :loading="loading" class="coupons-table"
        row-key="id">
        <template #bodyCell="{ column, record }">
          <template v-if="column.key === 'code'">
            <div class="font-bold text-sm text-gray-700">{{ record.code }}</div>
            <div v-if="record.description" class="text-sm text-gray-500 truncate max-w-[200px]">
              {{ record.description }}
            </div>
          </template>

          <template v-else-if="column.key === 'type'">
            <a-tag :color="record.type === 'percent' ? 'blue' : 'green'">
              {{ record.type === 'percent' ? 'Percentage' : 'Fixed Amount' }}
            </a-tag>
          </template>

          <template v-else-if="column.key === 'value'">
            <span class="font-semibold">
              {{ record.type === 'percent' ? record.value + '%' : formatMoney(record.value) }}
            </span>
          </template>

          <template v-else-if="column.key === 'usage'">
            <div class="text-sm">
              <div>Used: <span class="font-semibold">{{ record.used_count }}</span></div>
              <div v-if="record.usage_limit">
                Limit: <span class="font-semibold">{{ record.usage_limit }}</span>
              </div>
              <div v-else class="text-gray-500">Unlimited</div>
            </div>
          </template>

          <template v-else-if="column.key === 'valid_period'">
            <div class="flex items-center gap-1 text-sm text-gray-700">
              <template v-if="record.valid_from && record.valid_to">
                <FormatDate :date="record.valid_from" /> -
                <FormatDate :date="record.valid_to" />
              </template>
              <template v-else-if="record.valid_from">
                From
                <FormatDate :date="record.valid_from" />
              </template>
              <template v-else-if="record.valid_to">
                Until
                <FormatDate :date="record.valid_to" />
              </template>
              <template v-else>
                <span class="text-gray-500">No limit</span>
              </template>
            </div>
          </template>

          <template v-else-if="column.key === 'min_order_amount'">
            <span v-if="record.min_order_amount">{{ formatMoney(record.min_order_amount) }}</span>
            <span v-else class="text-gray-500">None</span>
          </template>

          <template v-else-if="column.key === 'is_active'">
            <a-tag :color="record.is_active ? 'green' : 'red'">
              {{ record.is_active ? 'Active' : 'Paused' }}
            </a-tag>
          </template>

          <template v-else-if="column.key === 'action'">
            <div class="flex gap-2">
              <a-button type="text" size="small" class="text-blue-500 font-bold"
                @click="showEditModal(record as Coupon)">
                <EditOutlined />
              </a-button>
              <a-button type="text" size="small" class="text-red-500 font-bold"
                @click="confirmDelete(record as Coupon)">
                <DeleteOutlined />
              </a-button>
            </div>
          </template>
        </template>
      </a-table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
      <a-pagination v-model:current="pagination.current" v-model:pageSize="pagination.pageSize"
        :total="pagination.total" :show-total="total => `Total ${total} coupons`" :show-size-changer="pagination.showSizeChanger"
        :page-size-options="pagination.pageSizeOptions" @change="handlePageChange" />
    </div>

    <!-- Create / Edit Modals -->
    <CreateCouponModal v-model:visible="isCreateModalVisible" @finish="handleCreateFinish" />
    <EditCouponModal v-model:visible="isEditModalVisible" :coupon="selectedCoupon" @finish="handleEditFinish" />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive, watch, h } from 'vue'
import { couponApi } from '@/api/admin/couponApi'
import type { Coupon, GetCouponsParams } from '@/types/Coupon'
import FormatDate from '@/components/common/FormatDate.vue'
import CreateCouponModal from './actions/CreateCouponModal.vue'
import EditCouponModal from './actions/EditCouponModal.vue'
import { notification, Modal } from 'ant-design-vue'
import { PlusOutlined, EditOutlined, DeleteOutlined, ExclamationCircleOutlined } from '@ant-design/icons-vue'

const coupons = ref<Coupon[]>([])
const isCreateModalVisible = ref(false)
const isEditModalVisible = ref(false)
const selectedCoupon = ref<Coupon | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)
const searchQuery = ref('')

const pagination = reactive({
  current: 1,
  pageSize: 10,
  total: 0,
  showSizeChanger: true,
  pageSizeOptions: ['10', '20', '50']
})

const columns = [
  { title: 'Coupon Code', dataIndex: 'code', key: 'code', width: '15%' },
  { title: 'Type', dataIndex: 'type', key: 'type', width: '10%' },
  { title: 'Value (USD/Percent)', dataIndex: 'value', key: 'value', width: '10%' },
  { title: 'Usage', dataIndex: 'usage', key: 'usage', width: '15%' },
  { title: 'Validity Period', dataIndex: 'valid_period', key: 'valid_period', width: '20%' },
  { title: 'Min Order Amount', dataIndex: 'min_order_amount', key: 'min_order_amount', width: '10%' },
  { title: 'Status', dataIndex: 'is_active', key: 'is_active', width: '10%' },
  { title: 'Actions', key: 'action', width: '10%' }
]

const formatMoney = (amount: number) => {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'USD' }).format(amount)
}

const fetchCoupons = async () => {
  loading.value = true
  error.value = null
  try {
    const params: GetCouponsParams = {
      page: pagination.current,
      per_page: pagination.pageSize,
      search: searchQuery.value || undefined
    }
    const response = await couponApi.getCoupons(params)
    coupons.value = response.data
    pagination.total = response.total
  } catch (e: any) {
    error.value = e.message || 'Failed to load coupons.'
    notification.error({ message: error.value })
  } finally {
    loading.value = false
  }
}

const handlePageChange = (page: number, pageSize: number) => {
  pagination.current = page
  pagination.pageSize = pageSize
  fetchCoupons()
}

const showCreateModal = () => {
  isCreateModalVisible.value = true
}

const showEditModal = (coupon: Coupon) => {
  selectedCoupon.value = coupon
  isEditModalVisible.value = true
}

const confirmDelete = (coupon: Coupon) => {
  Modal.confirm({
    title: 'Delete Coupon Confirmation',
    icon: h(ExclamationCircleOutlined),
    content: `Are you sure you want to delete the coupon "${coupon.code}"? This action cannot be undone.`,
    okText: 'Delete',
    cancelText: 'Cancel',
    okType: 'danger',
    async onOk() {
      try {
        await couponApi.deleteCoupon(coupon.id)
        notification.success({ message: 'Coupon deleted successfully!' })
        fetchCoupons()
      } catch (e: any) {
        notification.error({
          message: 'Failed to delete coupon',
          description: e.message || 'An error occurred while deleting the coupon.'
        })
      }
    }
  })
}

const handleCreateFinish = () => fetchCoupons()
const handleEditFinish = () => fetchCoupons()

onMounted(fetchCoupons)

let debounceTimer: number
watch(searchQuery, () => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    pagination.current = 1
    fetchCoupons()
  }, 500)
})
</script>
