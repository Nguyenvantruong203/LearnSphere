<template>
  <a-drawer :open="open" @update:open="val => $emit('update:open', val)" :title="quiz?.title" placement="right"
    width="80%" destroyOnClose class="quiz-drawer">
    <div v-if="quiz" class="quiz-content">
      <!-- Quiz Info -->
      <div class="info-section">
        <h3 class="section-title">Thông tin Quiz</h3>
        <a-card class="info-card" :bordered="false" >
          <a-row>
            <a-col :span="6">
              <div class="info-item">
                <div class="info-label">Thời gian</div>
                <div class="info-value">
                  <a-tag color="blue">{{ quiz.duration_minutes }} phút</a-tag>
                </div>
              </div>
            </a-col>
            <a-col :span="6">
              <div class="info-item">
                <div class="info-label">Số lần làm tối đa</div>
                <div class="info-value">
                  <a-tag :color="quiz.max_attempts === 0 ? 'orange' : 'purple'">
                    {{ quiz.max_attempts === 0 ? 'Không giới hạn' : quiz.max_attempts }}
                  </a-tag>
                </div>
              </div>
            </a-col>
            <a-col :span="6">
              <div class="info-item">
                <div class="info-label">Xáo trộn câu hỏi</div>
                <a-tag :color="quiz.shuffle_questions ? 'success' : 'default'">
                  {{ quiz.shuffle_questions ? 'Có' : 'Không' }}
                </a-tag>
              </div>
            </a-col>
            <a-col :span="6">
              <div class="info-item">
                <div class="info-label">Xáo trộn đáp án</div>
                <a-tag :color="quiz.shuffle_options ? 'success' : 'default'">
                  {{ quiz.shuffle_options ? 'Có' : 'Không' }}
                </a-tag>
              </div>
            </a-col>
          </a-row>
        </a-card>
      </div>
      <a-divider />

      <!-- Questions Table -->
      <QuestionsTable :quiz-id="quiz.id" :questions="quiz.questions || []"
        @update:questions="qs => $emit('update:quiz', { ...quiz, questions: qs })" />
    </div>

    <div v-else class="error-state">
      <a-empty description="Không có thông tin quiz" />
    </div>
  </a-drawer>
</template>

<script setup lang="ts">
import type { Quiz } from '@/types/Quiz'
import QuestionsTable from '@/components/admin/question/QuestionsTable.vue'

defineProps<{
  open: boolean
  quiz: Quiz | null
}>()

defineEmits<{
  (e: 'update:open', value: boolean): void
  (e: 'update:quiz', quiz: Quiz): void
}>()
</script>

