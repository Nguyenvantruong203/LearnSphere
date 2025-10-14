<template>
  <a-drawer :open="open" @update:open="val => $emit('update:open', val)" :title="quiz?.title || 'Quiz Details'"
    placement="right" width="80%" destroyOnClose class="quiz-drawer">
    <div v-if="quiz" class="space-y-8">
      <!-- 🧾 Quiz Info -->
      <section>
        <div class="mb-5">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center shadow-lg shadow-purple-500/30">
              <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                <g fill="none" stroke="currentColor" stroke-width="1.5">
                  <path
                    d="M7.5 3.5c-1.556.047-2.483.22-3.125.862c-.879.88-.879 2.295-.879 5.126v6.506c0 2.832 0 4.247.879 5.127C5.253 22 6.668 22 9.496 22h5c2.829 0 4.243 0 5.121-.88c.88-.879.88-2.294.88-5.126V9.488c0-2.83 0-4.246-.88-5.126c-.641-.642-1.569-.815-3.125-.862" />
                  <path stroke-linejoin="round"
                    d="M7.496 3.75c0-.966.784-1.75 1.75-1.75h5.5a1.75 1.75 0 1 1 0 3.5h-5.5a1.75 1.75 0 0 1-1.75-1.75Z" />
                  <path stroke-linecap="round" d="M6.5 10h4" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 11s.5 0 1 1c0 0 1.588-2.5 3-3" />
                  <path stroke-linecap="round" d="M6.5 16h4" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 17s.5 0 1 1c0 0 1.588-2.5 3-3" />
                </g>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Quiz Information</h3>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 xl:grid-cols-4 gap-2">
          <!-- Duration Card -->
          <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-200">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center shrink-0 shadow-md">
                <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Duration</div>
                <div class="text-2xl font-bold text-slate-800">
                  {{ quiz.duration_minutes }} 
                  <span class="text-sm font-medium text-slate-500">min</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Max Attempts Card -->
          <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-200">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shrink-0 shadow-md">
                <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 20V10"></path>
                  <path d="M12 20V4"></path>
                  <path d="M6 20v-6"></path>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Max Attempts</div>
                <div class="text-2xl font-bold text-slate-800">
                  {{ quiz.max_attempts === 0 ? 'Unlimited' : quiz.max_attempts }}
                </div>
              </div>
            </div>
          </div>

          <!-- Shuffle Questions Card -->
          <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-200">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shrink-0 shadow-md">
                <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M16 3h5v5"></path>
                  <path d="M8 3H3v5"></path>
                  <path d="M12 22v-7"></path>
                  <path d="m2 7 10 10"></path>
                  <path d="m22 7-10 10"></path>
                  <path d="m16 21 5-5"></path>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Shuffle Questions</div>
                <div class="mt-1">
                  <span v-if="quiz.shuffle_questions" 
                    class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow-sm">
                    Enabled
                  </span>
                  <span v-else 
                    class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-slate-100 text-slate-600">
                    Disabled
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Shuffle Options Card -->
          <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-200">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shrink-0 shadow-md">
                <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="16 3 21 3 21 8"></polyline>
                  <line x1="4" x2="21" y1="20" y2="3"></line>
                  <polyline points="21 16 21 21 16 21"></polyline>
                  <line x1="15" x2="21" y1="15" y2="21"></line>
                  <line x1="4" x2="9" y1="4" y2="9"></line>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Shuffle Options</div>
                <div class="mt-1">
                  <span v-if="quiz.shuffle_options" 
                    class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow-sm">
                    Enabled
                  </span>
                  <span v-else 
                    class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-slate-100 text-slate-600">
                    Disabled
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- 🧩 Questions Section -->
      <section>
        <div class="bg-white rounded-2xl pt-1 shadow-sm border border-slate-200">
          <QuestionsTable v-if="quiz" :key="quizKey" :quiz-id="quiz.id" :mode="quizMode"
            @update:questions="qs => $emit('update:quiz', { ...quiz, questions: qs } as Quiz)" />
        </div>
      </section>
    </div>

    <!-- 🚫 Empty State -->
    <div v-else class="flex flex-col items-center justify-center min-h-[400px] text-center">
      <div class="text-8xl mb-6 opacity-50">📝</div>
      <h3 class="text-2xl font-bold text-slate-800 mb-2">No Quiz Information</h3>
      <p class="text-sm text-slate-500 max-w-xs">Select a quiz to view its details and questions</p>
    </div>
  </a-drawer>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { Quiz } from '@/types/Quiz'
import QuestionsTable from '@/components/instructor/question/QuestionsTable.vue'

const props = defineProps<{
  open: boolean
  quiz: Quiz | null
}>()

defineEmits<{
  (e: 'update:open', value: boolean): void
  (e: 'update:quiz', quiz: Quiz): void
}>()

// 🧠 Determine mode (lesson/topic)
const quizMode = computed(() => {
  if (!props.quiz) return 'topic'
  const mode = props.quiz.lesson_id ? 'lesson' : 'topic'
  console.log('QuizDrawer Mode:', mode, 'Quiz ID:', props.quiz?.id)
  return mode
})

// 🔑 Unique key for rerendering
const quizKey = computed(() => {
  if (!props.quiz) return 'empty'
  return `quiz-${props.quiz.id}-${quizMode.value}-${props.quiz.lesson_id || props.quiz.topic_id}`
})
</script>

<style scoped>
/* Custom Drawer Styling */
:deep(.ant-drawer-header) {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-bottom: none;
  padding: 24px;
}

:deep(.ant-drawer-title) {
  font-weight: 700;
  font-size: 20px;
  color: white;
}

:deep(.ant-drawer-close) {
  color: white;
  opacity: 0.9;
  transition: opacity 0.2s;
}

:deep(.ant-drawer-close:hover) {
  opacity: 1;
}

:deep(.ant-drawer-body) {
  padding: 32px;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

@media (max-width: 768px) {
  :deep(.ant-drawer-body) {
    padding: 20px;
  }
}
</style>