<template>
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="mb-6 flex justify-between items-center">
            <a-input-search placeholder="Tìm kiếm theo mã, mô tả..." class="w-72" v-model:value="searchQuery"
                @search="fetchCoupons"></a-input-search>
            <a-button type="primary" @click="showCreateModal">
                <PlusOutlined />
                Thêm mã giảm giá
            </a-button>
        </div>

        <div class="table-container" style="height: 700px; overflow-y: auto;">
            <a-table :columns="columns" :data-source="coupons" :pagination="false" :loading="loading"
                class="coupons-table" row-key="id">
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'code'">
                        <div class="font-bold text-sm text-gray-700">{{ record.code }}</div>
                        <div v-if="record.description" class="text-sm text-gray-500 truncate max-w-[200px]">
                            {{ record.description }}
                        </div>
                    </template>

                    <template v-else-if="column.key === 'type'">
                        <a-tag :color="record.type === 'percent' ? 'blue' : 'green'">
                            {{ record.type === 'percent' ? 'Phần trăm' : 'Cố định' }}
                        </a-tag>
                    </template>
                    <template v-else-if="column.key === 'value'">
                        <span class="font-semibold">
                            {{ record.type === 'percent' ? record.value + '%' : formatMoney(record.value) }}
                        </span>
                    </template>
                    <template v-else-if="column.key === 'usage'">
                        <div class="text-sm">
                            <div>Đã dùng: <span class="font-semibold">{{ record.used_count }}</span></div>
                            <div v-if="record.usage_limit">
                                Giới hạn: <span class="font-semibold">{{ record.usage_limit }}</span>
                            </div>
                            <div v-else class="text-gray-500">Không giới hạn</div>
                        </div>
                    </template>
                    <template v-else-if="column.key === 'valid_period'">
                        <div class="flex items-center gap-1 text-sm text-gray-700">
                            <template v-if="record.valid_from && record.valid_to">
                                <FormatDate :date="record.valid_from" /> -
                                <FormatDate :date="record.valid_to" />
                            </template>
                            <template v-else-if="record.valid_from">
                                Từ
                                <FormatDate :date="record.valid_from" />
                            </template>
                            <template v-else-if="record.valid_to">
                                Đến
                                <FormatDate :date="record.valid_to" />
                            </template>
                            <template v-else>
                                <span class="text-gray-500">Không giới hạn</span>
                            </template>
                        </div>
                    </template>

                    <template v-else-if="column.key === 'min_order_amount'">
                        <span v-if="record.min_order_amount">{{ formatMoney(record.min_order_amount) }}</span>
                        <span v-else class="text-gray-500">Không có</span>
                    </template>
                    <template v-else-if="column.key === 'is_active'">
                        <a-tag :color="record.is_active ? 'green' : 'red'">
                            {{ record.is_active ? 'Hoạt động' : 'Tạm dừng' }}
                        </a-tag>
                    </template>
                    <template v-else-if="column.key === 'action'">
                        <div class="flex gap-2">
                            <a-button type="text" size="small" class="text-blue-500 font-bold"
                                @click="showEditModal(record as Coupon)">
                                <EditOutlined />
                            </a-button>
                            <a-button type="text" size="small" class="text-red-500 font-bold"
                                @click="showDeleteModal(record as Coupon)">
                                <DeleteOutlined />
                            </a-button>
                        </div>
                    </template>
                </template>
            </a-table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-end">
            <a-pagination v-model:current="pagination.current" v-model:pageSize="pagination.pageSize"
                :total="pagination.total" :show-size-changer="pagination.showSizeChanger"
                :page-size-options="pagination.pageSizeOptions" @change="handlePageChange" />
        </div>

        <!-- Create Coupon Modal -->
        <CreateCouponModal v-model:visible="isCreateModalVisible" @finish="handleCreateFinish" />

        <!-- Edit Coupon Modal -->
        <EditCouponModal v-model:visible="isEditModalVisible" :coupon="selectedCoupon" @finish="handleEditFinish" />

        <!-- Delete Coupon Modal -->
        <DeleteCouponModal v-model:visible="isDeleteModalVisible" :coupon="selectedCoupon"
            @finish="handleDeleteFinish" />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive, watch } from 'vue'
import { couponApi } from '@/api/admin/couponApi'
import type { Coupon, GetCouponsParams } from '@/types/coupon'
import FormatDate from './FormatDate.vue'
import CreateCouponModal from './actions/CreateCouponModal.vue'
import EditCouponModal from './actions/EditCouponModal.vue'
import DeleteCouponModal from './actions/DeleteCouponModal.vue'
import { notification } from 'ant-design-vue'
import { PlusOutlined, EditOutlined, DeleteOutlined } from '@ant-design/icons-vue'

const coupons = ref<Coupon[]>([])
const isCreateModalVisible = ref(false)
const isEditModalVisible = ref(false)
const isDeleteModalVisible = ref(false)
const selectedCoupon = ref<Coupon | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)
const searchQuery = ref('')

const pagination = reactive({
    current: 1,
    pageSize: 10,
    total: 0,
    showSizeChanger: true,
    pageSizeOptions: ['10', '20', '50'],
})

const columns = [
    {
        title: 'Mã coupon',
        dataIndex: 'code',
        key: 'code',
        width: '15%',
    },
    {
        title: 'Loại',
        dataIndex: 'type',
        key: 'type',
        width: '10%',
    },
    {
        title: 'Giá trị',
        dataIndex: 'value',
        key: 'value',
        width: '10%',
    },
    {
        title: 'Sử dụng',
        dataIndex: 'usage',
        key: 'usage',
        width: '15%',
    },
    {
        title: 'Thời gian hiệu lực',
        dataIndex: 'valid_period',
        key: 'valid_period',
        width: '20%',
    },
    {
        title: 'Đơn hàng tối thiểu',
        dataIndex: 'min_order_amount',
        key: 'min_order_amount',
        width: '10%',
    },
    {
        title: 'Trạng thái',
        dataIndex: 'is_active',
        key: 'is_active',
        width: '10%',
    },
    {
        title: 'Thao tác',
        key: 'action',
        width: '10%',
    },
]

const formatMoney = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount)
}

const fetchCoupons = async () => {
    loading.value = true
    error.value = null
    try {
        const params: GetCouponsParams = {
            page: pagination.current,
            per_page: pagination.pageSize,
            search: searchQuery.value || undefined,
        }
        const response = await couponApi.getCoupons(params)

        coupons.value = response.data
        pagination.total = response.total
    } catch (e: any) {
        error.value = e.message || 'Không thể tải danh sách mã giảm giá.'
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

const showDeleteModal = (coupon: Coupon) => {
    selectedCoupon.value = coupon
    isDeleteModalVisible.value = true
}

const handleCreateFinish = () => {
    fetchCoupons() // Refresh the table after creating
}

const handleEditFinish = () => {
    fetchCoupons() // Refresh the table after editing
}

const handleDeleteFinish = () => {
    fetchCoupons() // Refresh the table after deleting
}

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
