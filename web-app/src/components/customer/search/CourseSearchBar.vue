<template>
  <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
    <!-- Search box -->
    <div class="mb-4">
      <a-input-search v-model:value="searchText" placeholder="Search for your favorite course..." enter-button="Search"
        size="large" class="w-full" @search="onSearch" @pressEnter="onSearch">
        <template #prefix>
          <SearchOutlined class="text-gray-400" />
        </template>
      </a-input-search>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 items-center">
      <span class="text-sm font-medium text-gray-700 mr-2">Filters:</span>

      <!-- Subject -->
      <a-select v-model:value="filters.subject" placeholder="Subject" style="width: 160px" allow-clear size="small">
        <a-select-option value="it">IT</a-select-option>
        <a-select-option value="design">Design</a-select-option>
        <a-select-option value="development">Development</a-select-option>
        <a-select-option value="business">Business</a-select-option>
        <a-select-option value="marketing">Marketing</a-select-option>
        <a-select-option value="finance">Finance</a-select-option>
        <a-select-option value="language">Language</a-select-option>
      </a-select>

      <!-- Level -->
      <a-select v-model:value="filters.level" placeholder="Level" style="width: 160px" allow-clear size="small">
        <a-select-option value="beginner">Beginner</a-select-option>
        <a-select-option value="intermediate">Intermediate</a-select-option>
        <a-select-option value="advanced">Advanced</a-select-option>
      </a-select>

      <!-- Language -->
      <a-select v-model:value="filters.language" placeholder="Language" style="width: 160px" allow-clear size="small">
        <a-select-option value="vi">Vietnamese</a-select-option>
        <a-select-option value="en">English</a-select-option>
        <a-select-option value="jp">日本語</a-select-option>
      </a-select>

      <!-- Paid/Free -->
      <a-select v-model:value="filters.is_paid" placeholder="Payment" style="width: 160px" allow-clear size="small">
        <a-select-option :value="false">Free</a-select-option>
        <a-select-option :value="true">Paid</a-select-option>
      </a-select>

      <!-- Price range -->
      <div class="flex items-center space-x-2">
        <a-input-number v-model:value="filters.price_min" placeholder="Min price" :min="0" style="width: 100px"
          size="small" />
        <span>-</span>
        <a-input-number v-model:value="filters.price_max" placeholder="Max price" :min="0" style="width: 100px"
          size="small" />
      </div>

      <a-button v-if="hasActiveFilters" size="small" type="text" @click="clearAllFilters"
        class="text-gray-500 hover:text-red-500">
        <span class="flex items-center">
          <ClearOutlined class="mr-1" /> Xóa tất cả
        </span>
      </a-button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch, computed } from "vue"
import { SearchOutlined, ClearOutlined } from "@ant-design/icons-vue"
import type { CourseSearchPayload } from "@/types/Course"

const emit = defineEmits<{
  (e: "search", payload: CourseSearchPayload): void
}>()

const searchText = ref("")
const filters = reactive({
  subject: undefined,
  level: undefined,
  language: undefined,
  is_paid: undefined,
  price_min: undefined,
  price_max: undefined,
})

const hasActiveFilters = computed(
  () => searchText.value.trim() !== "" || Object.values(filters).some(v => v !== undefined)
)

const clearAllFilters = () => {
  searchText.value = ""
  Object.keys(filters).forEach(k => (filters[k as keyof typeof filters] = undefined))
  onSearch()
}

const onSearch = () => {
  emit("search", { search: searchText.value.trim(), filters })
}

watch(filters, onSearch, { deep: true })
</script>
