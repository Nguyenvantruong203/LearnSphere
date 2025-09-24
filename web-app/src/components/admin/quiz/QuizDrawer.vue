<template>
    <a-drawer :open="props.open" @update:open="(val) => emit('update:open', val)" :title="quiz?.title" placement="right"
        width="80%" destroyOnClose class="quiz-drawer">

        <div v-if="quiz" class="quiz-content">
            <!-- Quiz Info Section -->
            <div class="info-section">
                <div class="section-header">
                    <h3 class="section-title">
                        Thông tin Quiz
                    </h3>
                </div>
                
                <a-card class="info-card" :bordered="false">
                    <a-row :gutter="[16, 16]">
                        <a-col :span="12">
                            <div class="info-item">
                                <div class="info-label">Thời gian</div>
                                <div class="info-value">
                                    <a-tag color="blue" class="time-tag">
                                        {{ localQuiz.duration_minutes }} phút
                                    </a-tag>
                                </div>
                            </div>
                        </a-col>
                        
                        <a-col :span="12">
                            <div class="info-item">
                                <div class="info-label">Số lần làm tối đa</div>
                                <div class="info-value">
                                    <a-tag :color="localQuiz.max_attempts === 0 ? 'orange' : 'purple'" class="attempts-tag">
                                        <span class="icon">🔄</span>
                                        {{ localQuiz.max_attempts === 0 ? 'Không giới hạn' : localQuiz.max_attempts }}
                                    </a-tag>
                                </div>
                            </div>
                        </a-col>
                        
                        <a-col :span="12">
                            <div class="info-item">
                                <div class="info-label">Xáo trộn câu hỏi</div>
                                <div class="info-value">
                                    <a-tag :color="localQuiz.shuffle_questions ? 'success' : 'default'" class="toggle-tag">
                                        <span class="icon">{{ localQuiz.shuffle_questions ? '✅' : '❌' }}</span>
                                        {{ localQuiz.shuffle_questions ? 'Có' : 'Không' }}
                                    </a-tag>
                                </div>
                            </div>
                        </a-col>
                        
                        <a-col :span="12">
                            <div class="info-item">
                                <div class="info-label">Xáo trộn đáp án</div>
                                <div class="info-value">
                                    <a-tag :color="localQuiz.shuffle_options ? 'success' : 'default'" class="toggle-tag">
                                        <span class="icon">{{ localQuiz.shuffle_options ? '✅' : '❌' }}</span>
                                        {{ localQuiz.shuffle_options ? 'Có' : 'Không' }}
                                    </a-tag>
                                </div>
                            </div>
                        </a-col>
                    </a-row>
                </a-card>
            </div>

            <a-divider />

            <!-- Question List Section -->
            <div class="questions-section">
                <div class="section-header">
                    <h3 class="section-title">
                        Danh sách câu hỏi
                        <a-badge :count="questions.length" :number-style="{ backgroundColor: '#52c41a' }" />
                    </h3>
                    
                    <a-button type="primary" @click="addQuestion" class="add-question-btn">
                        <template #icon>
                            <span class="icon">➕</span>
                        </template>
                        Thêm câu hỏi
                    </a-button>
                </div>

                <a-card class="questions-card" :bordered="false" v-if="questions.length > 0">
                    <a-table 
                        :columns="questionColumns" 
                        :data-source="questions" 
                        row-key="id" 
                        :pagination="{ pageSize: 10, showSizeChanger: true }"
                        size="middle"
                        class="questions-table"
                    >
                        <template #bodyCell="{ column, record, index }">
                            <template v-if="column.key === 'index'">
                                <div class="question-index">{{ index + 1 }}</div>
                            </template>
                            
                            <template v-if="column.key === 'text'">
                                <div class="question-text">{{ record.text }}</div>
                            </template>
                            
                            <template v-if="column.key === 'type'">
                                <a-tag :color="getQuestionTypeColor(record.type)" class="question-type-tag">
                                    <span class="icon">{{ getQuestionTypeIcon(record.type) }}</span>
                                    {{ getQuestionTypeLabel(record.type) }}
                                </a-tag>
                            </template>
                            
                            <template v-if="column.key === 'action'">
                                <a-space>
                                    <a-button type="link" size="small" @click="editQuestion(record)" class="action-btn edit-btn">
                                        <span class="icon">✏️</span>
                                        Sửa
                                    </a-button>
                                    <a-button type="link" size="small" danger @click="deleteQuestion(record)" class="action-btn delete-btn">
                                        <span class="icon">🗑️</span>
                                        Xóa
                                    </a-button>
                                </a-space>
                            </template>
                        </template>
                    </a-table>
                </a-card>

                <!-- Empty State -->
                <a-empty v-else description="Chưa có câu hỏi nào" class="empty-state">
                    <template #image>
                        <div class="empty-icon">❓</div>
                    </template>
                    <a-button type="primary" @click="addQuestion" class="empty-add-btn">
                        <template #icon>
                            <span class="icon">➕</span>
                        </template>
                        Tạo câu hỏi đầu tiên
                    </a-button>
                </a-empty>
            </div>
        </div>
    </a-drawer>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

interface Quiz {
    id: number
    title: string
    duration_minutes: number
    shuffle_questions: boolean
    shuffle_options: boolean
    max_attempts: number
}

const props = defineProps<{
    open: boolean
    quiz: Quiz | null
}>()

const emit = defineEmits(['update:open'])

// Local state
const localQuiz = ref<any>({})
const questions = ref<any[]>([])

// Watch quiz prop để load dữ liệu
watch(() => props.quiz, async (newQuiz) => {
    if (newQuiz) {
        localQuiz.value = { ...newQuiz }
        // TODO: gọi API fetch questions theo quiz.id
        questions.value = [
            { id: 1, text: 'Câu hỏi về lịch sử Việt Nam trong thế kỷ 20?', type: 'single' },
            { id: 2, text: 'Những yếu tố nào ảnh hưởng đến khí hậu?', type: 'multi' },
            { id: 3, text: 'Giải thích khái niệm phát triển bền vững', type: 'essay' },
        ]
    }
})

// Table columns
const questionColumns = [
    { title: '#', key: 'index', width: 60, align: 'center' },
    { title: 'Nội dung câu hỏi', key: 'text', ellipsis: true },
    { title: 'Loại', key: 'type', width: 120, align: 'center' },
    { title: 'Hành động', key: 'action', width: 150, align: 'center' }
]

// Helper functions
const getQuestionTypeColor = (type: string) => {
    const colors = {
        single: 'blue',
        multi: 'green', 
        essay: 'orange',
        true_false: 'purple'
    }
    return colors[type as keyof typeof colors] || 'default'
}

const getQuestionTypeIcon = (type: string) => {
    const icons = {
        single: '🔘',
        multi: '☑️',
        essay: '📝',
        true_false: '✅'
    }
    return icons[type as keyof typeof icons] || '❓'
}

const getQuestionTypeLabel = (type: string) => {
    const labels = {
        single: 'Một đáp án',
        multi: 'Nhiều đáp án',
        essay: 'Tự luận',
        true_false: 'Đúng/Sai'
    }
    return labels[type as keyof typeof labels] || 'Không xác định'
}

// Handlers
const addQuestion = () => {
    console.log('Thêm câu hỏi cho quiz', props.quiz?.id)
}

const editQuestion = (q: any) => {
    console.log('Sửa question', q)
}

const deleteQuestion = (q: any) => {
    console.log('Xóa question', q)
}
</script>

<style scoped>
.quiz-drawer :deep(.ant-drawer-header) {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: none;
}

.quiz-drawer :deep(.ant-drawer-title) {
    color: white;
    font-weight: 600;
    font-size: 18px;
}

.quiz-drawer :deep(.ant-drawer-close) {
    color: white;
}

.quiz-content {
    padding: 8px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.section-title .icon {
    font-size: 20px;
}

.info-section {
    margin-bottom: 24px;
}

.info-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.info-item {
    text-align: center;
}

.info-label {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 8px;
    font-weight: 500;
}

.info-value {
    display: flex;
    justify-content: center;
}

.time-tag, .attempts-tag, .toggle-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-weight: 500;
    padding: 4px 12px;
    border-radius: 8px;
}

.add-question-btn {
    display: flex;
    align-items: center;
    gap: 4px;
    border-radius: 8px;
    font-weight: 500;
    box-shadow: 0 2px 4px rgba(22, 119, 255, 0.2);
}

.questions-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.questions-table :deep(.ant-table-thead > tr > th) {
    background: #f8fafc;
    color: #374151;
    font-weight: 600;
    border-bottom: 2px solid #e5e7eb;
}

.questions-table :deep(.ant-table-tbody > tr:hover > td) {
    background: #f8fafc;
}

.question-index {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border-radius: 50%;
    font-weight: 600;
    font-size: 12px;
}

.question-text {
    color: #1f2937;
    font-weight: 500;
    line-height: 1.5;
}

.question-type-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-weight: 500;
    border-radius: 6px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.edit-btn:hover {
    background: #e0f2fe;
    color: #0277bd;
}

.delete-btn:hover {
    background: #ffebee;
    color: #d32f2f;
}

.empty-state {
    margin: 40px 0;
}

.empty-icon {
    font-size: 48px;
    margin-bottom: 16px;
}

.empty-add-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-weight: 500;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(22, 119, 255, 0.2);
}

:deep(.ant-badge-count) {
    font-size: 12px;
    min-width: 20px;
    height: 20px;
    line-height: 18px;
}

:deep(.ant-divider) {
    margin: 24px 0;
    border-color: #e5e7eb;
}
</style>