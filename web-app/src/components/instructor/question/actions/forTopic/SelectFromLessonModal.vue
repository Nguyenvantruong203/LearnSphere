<template>
    <a-modal v-model:open="innerOpen" title="Select Questions from Lesson" ok-text="Add Selected" cancel-text="Cancel"
        @ok="handleSave" :confirm-loading="loading" width="800px">
        <a-table row-key="id" :columns="columns" :data-source="pool" :loading="loading" :row-selection="rowSelection"
            size="middle" :pagination="{ pageSize: 10 }" />
    </a-modal>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { topicQuestionApi } from '@/api/instructor/topicQuestionApi'
import type { Question } from '@/types/Question'

const props = defineProps<{
    quizId: number
    open: boolean
}>()

const emit = defineEmits<{
    (e: 'update:open', val: boolean): void
    (e: 'published'): void
}>()

// ✅ innerOpen for v-model:open
const innerOpen = computed({
    get: () => props.open,
    set: (val: boolean) => emit('update:open', val),
})

const pool = ref<(Question & { selected?: boolean })[]>([])
const selectedRowKeys = ref<number[]>([])
const loading = ref(false)

const columns = [
    { title: '#', key: 'index', customRender: ({ index }: any) => index + 1 },
    { title: 'Question Text', dataIndex: 'text' },
    { title: 'Type', dataIndex: 'type' },
    { title: 'Points', dataIndex: 'weight' },
]

const rowSelection = computed(() => ({
    selectedRowKeys: selectedRowKeys.value,
    onChange: (keys: number[]) => {
        selectedRowKeys.value = keys
    },
}))

watch(
    () => props.open,
    async (val) => {
        if (val) {
            loading.value = true
            const res = await topicQuestionApi.getPool(props.quizId)
            pool.value = res ?? []
            selectedRowKeys.value = pool.value
                .filter((q) => q.selected)
                .map((q) => q.id)
            loading.value = false
        }
    },
)

const handleSave = async () => {
    if (!selectedRowKeys.value.length) {
        innerOpen.value = false
        return
    }
    loading.value = true
    await topicQuestionApi.publishQuestions(props.quizId, selectedRowKeys.value)
    loading.value = false
    innerOpen.value = false
    emit('published')
}
</script>
