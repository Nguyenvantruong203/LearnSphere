<template>
  <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
    <!-- Search box -->
    <div class="mb-4">
      <a-input-search
        v-model:value="searchText"
        placeholder="Tìm kiếm khóa học yêu thích của bạn..."
        enter-button="Tìm kiếm"
        size="large"
        class="w-full"
        @search="onSearch"
      >
        <template #prefix>
          <SearchOutlined class="text-gray-400" />
        </template>
      </a-input-search>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 items-center">
      <span class="text-sm font-medium text-gray-700 mr-2">Bộ lọc:</span>

      <!-- Subject -->
      <a-select v-model:value="filters.subject" placeholder="Chủ đề" style="width: 160px" allow-clear size="small">
        <a-select-option value="it">IT</a-select-option>
        <a-select-option value="design">Design</a-select-option>
        <a-select-option value="development">Development</a-select-option>
        <a-select-option value="business">Business</a-select-option>
        <a-select-option value="marketing">Marketing</a-select-option>
        <a-select-option value="finance">Finance</a-select-option>
        <a-select-option value="language">Language</a-select-option>
      </a-select>

      <!-- Level -->
      <a-select v-model:value="filters.level" placeholder="Trình độ" style="width: 160px" allow-clear size="small">
        <a-select-option value="beginner">Cơ bản</a-select-option>
        <a-select-option value="intermediate">Trung cấp</a-select-option>
        <a-select-option value="advanced">Nâng cao</a-select-option>
      </a-select>

      <!-- Language -->
      <a-select v-model:value="filters.language" placeholder="Ngôn ngữ" style="width: 160px" allow-clear size="small">
        <a-select-option value="vi">Tiếng Việt</a-select-option>
        <a-select-option value="en">English</a-select-option>
        <a-select-option value="jp">日本語</a-select-option>
      </a-select>

      <!-- Paid/Free -->
      <a-select v-model:value="filters.is_paid" placeholder="Học phí" style="width: 160px" allow-clear size="small">
        <a-select-option :value="false">Miễn phí</a-select-option>
        <a-select-option :value="true">Trả phí</a-select-option>
      </a-select>

      <!-- Price range -->
      <div class="flex items-center space-x-2">
        <a-input-number
          v-model:value="filters.price_min"
          placeholder="Giá từ"
          :min="0"
          style="width: 100px"
          size="small"
        />
        <span>-</span>
        <a-input-number
          v-model:value="filters.price_max"
          placeholder="Đến"
          :min="0"
          style="width: 100px"
          size="small"
        />
      </div>

      <!-- Clear all filters button -->
      <a-button
        v-if="hasActiveFilters"
        size="small"
        type="text"
        @click="clearAllFilters"
        class="text-gray-500 hover:text-red-500"
      >
        <template #icon>
          <ClearOutlined />
        </template>
        Xóa bộ lọc
      </a-button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch, computed } from "vue"
import { SearchOutlined, ClearOutlined } from "@ant-design/icons-vue"
import type { CourseSearchPayload } from "@/types/Course"

const emit = defineEmits<{
  search: [payload: CourseSearchPayload]
}>()

const searchText = ref("")

// đồng bộ với BE filters
const filters = reactive({
  subject: undefined as string | undefined,
  level: undefined as string | undefined,
  language: undefined as string | undefined,
  is_paid: undefined as boolean | undefined,
  price_min: undefined as number | undefined,
  price_max: undefined as number | undefined,
  category_id: undefined as number | undefined
})

const hasActiveFilters = computed(() => {
  return (
    searchText.value.trim() !== "" ||
    Object.values(filters).some(v => v !== undefined)
  )
})

const clearAllFilters = () => {
  searchText.value = ""
  Object.keys(filters).forEach(key => {
    filters[key as keyof typeof filters] = undefined
  })
  onSearch()
}

const onSearch = () => {
  const payload: CourseSearchPayload = {
    search: searchText.value.trim(),
    ...filters
  }
  emit("search", payload)
}

// Auto search khi thay đổi filters
watch(
  filters,
  () => {
    onSearch()
  },
  { deep: true }
)
</script>
