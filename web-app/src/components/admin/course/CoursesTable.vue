<template>
  <div class="bg-white rounded-2xl shadow-sm p-6">
    <!-- 🔹 Header -->
    <div class="flex items-center justify-between mb-3">
      <h2 class="text-xl font-semibold">Courses → Topics → Lessons</h2>
      <div class="flex items-center gap-3">
        <a-input-search placeholder="Search courses..." class="w-64" v-model:value="searchQuery"
          @search="fetchCourses" />
        <a-button type="primary" @click="openCreateCourse"><span class="flex justify-center items-center"><PlusOutlined /> Course</span></a-button>
      </div>
    </div>

    <div class="flex flex-col h-[calc(100vh-180px)]">
      <div class="flex-1 overflow-y-auto">
        <a-collapse v-model:activeKey="activeCourseKeys" accordion>
          <a-collapse-panel v-for="course in filteredCourses" :key="course.id" :header="course.title">
            <div class="flex items-center justify-between mb-2">
              <div class="text-gray-500"></div>
              <div class="space-x-2">
                <a-button size="small" @click="openEditCourse(course)">Edit Course</a-button>
                <a-button size="small" danger @click="confirmRemoveCourse(course)">Delete</a-button>
                <a-button size="small" type="primary" @click="openCreateTopic(course.id)">+ Topic</a-button>
              </div>
            </div>

            <a-table :columns="columns" :data-source="treeByCourse[course.id] || []" row-key="key"
              :loading="loading[course.id]" :pagination="false" sticky table-layout="fixed"
              :scroll="{ y: 'calc(100vh - 180px)' }" :expandable="{
                childrenColumnName: 'children',
                expandRowByClick: false,
                defaultExpandAllRows: false
              }" @expand="(expanded, record) => onExpand(expanded, record, course.id)" />
          </a-collapse-panel>
        </a-collapse>
      </div>

      <div class="mt-2 flex justify-center">
        <a-pagination :current="pagination.current" :page-size="pagination.pageSize" :total="pagination.total"
          show-size-changer :show-total="pagination.showTotal" @change="onPageChange" @showSizeChange="onPageChange" />
      </div>
    </div>

    <CreateCourseModal v-model:open="createCourse.open" @finish="onCreatedCourse" />
    <EditCourseModal v-model:open="editCourse.open" :course="editCourse.course" @finish="onEditedCourse" />

    <CreateTopicModal v-model:open="createTopic.open" :course-id="createTopic.courseId" @finish="onCreatedTopic" />
    <EditTopicModal v-model:open="editTopic.open" :topic="editTopic.topic" @finish="onEditedTopic" />

    <CreateLessonModal v-model:open="createLesson.open" :topic-id="createLesson.topicId"
      :course-id="createLesson.courseId" :youtube-connected="youtubeConnected" @finish="onCreatedLesson" />
    <EditLessonModal v-model:open="editLesson.open" :lesson="editLesson.lesson" @finish="onEditedLesson" />
    <CreateQuizModal v-model:open="createQuiz.open" :lesson-id="createQuiz.lessonId" :topic-id="createQuiz.topicId"
      :scope="createQuiz.scope" @finish="onCreatedQuiz" />
    <EditQuizModal v-model:open="editQuiz.open" :quiz="editQuiz.quiz" @finish="onEditedQuiz" />
    <QuizDrawer v-model:open="quizDrawer.open" :quiz="quizDrawer.quiz" />
  </div>
</template>

<script setup lang="ts">
import { ref, h, onMounted, watch, nextTick, reactive, computed } from 'vue'
import { Button, Space, Popconfirm, Tag, Modal, notification } from 'ant-design-vue'
import { courseApi } from '@/api/admin/courseApi'
import { topicApi } from '@/api/admin/topicApi'
import { lessonApi } from '@/api/admin/lessonApi'

// ====== Modal components ======
import CreateCourseModal from '@/components/admin/course/actions/CreateCourseModal.vue'
import EditCourseModal from '@/components/admin/course/actions/EditCourseModal.vue'
import CreateTopicModal from '@/components/admin/topic/actions/CreateTopicModal.vue'
import CreateLessonModal from '@/components/admin/lesson/actions/CreateLessonModal.vue'
import EditTopicModal from '@/components/admin/topic/actions/EditTopicModal.vue'
import EditLessonModal from '@/components/admin/lesson/actions/EditLessonModal.vue'
import CreateQuizModal from '@/components/admin/quiz/actions/CreateQuizModal.vue'
import EditQuizModal from '@/components/admin/quiz/actions/EditQuizModal.vue'
import QuizDrawer from '@/components/admin/quiz/QuizDrawer.vue'

import type { Topic } from '@/types/Topic'
import type { Course } from '@/types/Course'
import { youtubeApi } from '@/api/admin/youtubeApi'
import { quizApi } from '@/api/admin/quizApi'

interface TopicNode extends Topic {
  key: string;
  type: "topic";
  children?: TreeNode[] | null;
  loaded?: boolean;
  hasChildren?: boolean;
}

type TreeNode =
  | TopicNode
  | { key: string; type: "lesson"; lesson_id: number; title: string; order: number; content?: string | null; children?: TreeNode[] }
  | { key: string; type: "quiz"; quiz_id: number; title: string; duration_minutes: number; order?: number; lesson_id?: number }
  | { key: string; type: "loading"; title: string; order: number; lesson_id?: number }

const courses = ref<Course[]>([])
const searchQuery = ref('')

const filteredCourses = computed(() => {
  const q = searchQuery.value.trim().toLowerCase()
  if (!q) return courses.value
  return courses.value.filter(course => course.title.toLowerCase().includes(q))
})
const pagination = reactive({
  current: 1,
  pageSize: 10,
  total: 0,
  showTotal: (total: number) => `Total ${total} courses`
})
const loadingCourses = ref(false)
const activeCourseKeys = ref<(string | number)[]>([])
const treeByCourse = reactive<Record<number, TreeNode[]>>({})
const loading = ref<Record<number, boolean>>({})

// ====== Course Modals ======
const createCourse = ref<{ open: boolean }>({ open: false })
const editCourse = ref<{ open: boolean; course: Course | null }>({ open: false, course: null })
const youtubeConnected = ref(false)

const openCreateCourse = () => { createCourse.value.open = true }

const onCreatedCourse = async () => {
  await fetchCourses()
}

const openEditCourse = (course: Course) => {
  editCourse.value = { open: true, course }
}
const onEditedCourse = async () => {
  await fetchCourses()
}

const confirmRemoveCourse = (course: Course) => {
  Modal.confirm({
    icon: h(ExclamationCircleOutlined),
    content: `Are you sure you want to delete the course "${course.title}"? This action cannot be undone.`,
    okText: 'Delete',
    cancelText: 'Cancel',
    title: `Delete course: ${course.title}?`,
    onOk: () => removeCourse(course.id)
  })
}

const removeCourse = async (courseId: number) => {
  try {
    await courseApi.deleteCourse(courseId)
    notification.success({ message: 'Course deleted successfully.' })
    await fetchCourses()
  } catch (error: any) {
    notification.error({ message: error.message || 'Failed to delete course.' })
  }
}

// ====== Columns (action gài +Lesson / Edit / Delete) ======
const columns = [
  {
    title: 'Type',
    key: 'type',
    width: 110,
    customRender: ({ record }: any) => {
      if (record.type === 'topic') return h(Tag, {}, () => 'Topic')
      if (record.type === 'lesson') return h(Tag, { color: 'blue' }, () => 'Lesson')
      if (record.type === 'quiz') return h(Tag, { color: 'green' }, () => 'Quiz')
      if (record.type === 'loading') return h(Tag, { color: 'orange' }, () => 'Loading')
      return null
    }
  },
  {
    title: 'Title',
    key: 'title',
    ellipsis: true,
    customRender: ({ record }: any) => {
      if (record.type === 'quiz') {
        return h(
          'a',
          {
            style: { textDecoration: 'underline', cursor: 'pointer', color: '#1677ff' },
            onClick: () => openQuizDrawer(record)
          },
          record.title
        )
      }
      return record.title
    }
  },
  {
    title: 'Order / Duration',
    key: 'order',
    width: 160,
    customRender: ({ record }: any) => {
      if (record.type === 'lesson') return record.order
      if (record.type === 'quiz') return `${record.duration_minutes ?? 0} min`
      return record.order
    }
  },
  {
    title: 'Actions',
    key: 'action',
    width: 320,
    customRender: ({ record }: any) => {
      if (record.type === 'topic') {
        return h(Space, {}, () => [
          h(Button, { type: 'link', onClick: () => openCreateLesson(record.id, Number(activeCourseKeys.value[0])) }, () => '+ Lesson'),
          h(Button, { type: 'link', onClick: () => openCreateQuiz(record) }, () => '+ Quiz'),
          h(Button, { type: 'link', onClick: () => openEditTopic(record) }, () => 'Edit'),
          h(Popconfirm, { title: 'Delete this topic?', onConfirm: () => removeTopic(record) },
            { default: () => h(Button, { type: 'link', danger: true }, () => 'Delete') })
        ])
      }
      if (record.type === 'lesson') {
        return h(Space, {}, () => [
          h(Button, { type: 'link', onClick: () => openCreateQuiz(record) }, () => '+ Quiz'),
          h(Button, { type: 'link', onClick: () => openEditLesson(record) }, () => 'Edit'),
          h(Popconfirm, { title: 'Delete this lesson?', onConfirm: () => removeLesson(record) },
            { default: () => h(Button, { type: 'link', danger: true }, () => 'Delete') })
        ])
      }
      if (record.type === 'quiz') {
        return h(Space, {}, () => [
          h(Button, { type: 'link', onClick: () => openEditQuiz(record) }, () => 'Edit'),
          h(Popconfirm, { title: 'Delete this quiz?', onConfirm: () => removeQuiz(record) },
            { default: () => h(Button, { type: 'link', danger: true }, () => 'Delete') })
        ])
      }
      return null
    }
  }
]
const quizDrawer = ref<{ open: boolean; quiz: any | null }>({ open: false, quiz: null })

const openQuizDrawer = (quizRow: any) => {
  quizDrawer.value = { open: true, quiz: quizRow }
}

// ====== Lifecycle ======
onMounted(async () => {
  const savedCourseId = localStorage.getItem('activeCourseId')
  if (savedCourseId) {
    activeCourseKeys.value = [Number(savedCourseId)]
  }

  await fetchCourses()
})

watch(activeCourseKeys, async (keys) => {
  const cid = Number(keys?.[0])
  if (cid) {
    // Lưu vào localStorage
    localStorage.setItem('activeCourseId', String(cid))

    if (!treeByCourse[cid]) {
      await fetchTopics(cid)
    }
  }
})

// ====== Data fetchers ======
const fetchCourses = async () => {
  loadingCourses.value = true
  try {
    const res = await courseApi.getCourses({
      page: pagination.current,
      per_page: pagination.pageSize,
      search: searchQuery.value || undefined
    })
    courses.value = res.data
    pagination.total = res.total
    pagination.current = res.current_page
    pagination.pageSize = res.per_page
  } finally {
    loadingCourses.value = false
  }
}

const onPageChange = (page: number, pageSize?: number) => {
  pagination.current = page
  if (pageSize) pagination.pageSize = pageSize
  fetchCourses()
}

const fetchTopics = async (courseId: number) => {
  loading.value[courseId] = true
  try {
    const res = await topicApi.getTopicsByCourse(courseId)
    console.log('🏗️ Building topics tree for course:', courseId)
    console.log('📦 Topics from API:', res)

    const topics = res.map((t: Topic) => {
      const topicNode = {
        ...t,
        key: `topic-${t.id}`,
        type: "topic" as const,
        children: [{
          key: `loading-${t.id}`,
          type: 'loading' as const,
          title: 'Đang tải lessons...',
          order: 0,
          lesson_id: -1
        }],
        loaded: false,
        hasChildren: true
      }
      console.log('🎯 Created topic node:', topicNode)
      return topicNode
    })

    treeByCourse[courseId] = topics
    await nextTick()

  } finally {
    loading.value[courseId] = false
  }
}

// ====== FIXED: Fetch lessons and quizzes together ======
const fetchTopicContent = async (courseId: number, topicId: number) => {
  try {

    // Fetch lessons
    const lessonRes = await lessonApi.getLessons({ topicId, page: 1, limit: 50 })
    const lessonRows = Array.isArray(lessonRes.data) ? lessonRes.data : lessonRes

    // Fetch quizzes for this topic
    const quizRes = await quizApi.getQuizzesByTopic(topicId)
    const allQuizzes = Array.isArray(quizRes.data) ? quizRes.data : (quizRes || [])

    // Separate topic-level quizzes and lesson-level quizzes
    const topicQuizzes = allQuizzes.filter((q: any) => !q.lesson_id)
    const lessonQuizzesMap = new Map<number, any[]>()

    allQuizzes.filter((q: any) => q.lesson_id).forEach((q: any) => {
      if (!lessonQuizzesMap.has(q.lesson_id)) {
        lessonQuizzesMap.set(q.lesson_id, [])
      }
      lessonQuizzesMap.get(q.lesson_id)!.push(q)
    })

    // Create lesson nodes with their quizzes
    const lessons = (lessonRows ?? []).map((ls: any) => {
      const lessonQuizzes = lessonQuizzesMap.get(ls.id) || []
      const quizNodes = lessonQuizzes.map((q: any) => ({
        ...q, // Spread all properties from the quiz object
        key: `quiz-${q.id}`,
        type: 'quiz' as const,
        quiz_id: q.id,
      }))

      return {
        key: `lesson-${ls.id}`,
        type: 'lesson' as const,
        lesson_id: ls.id,
        title: ls.title,
        order: ls.order,
        content: ls.content ?? null,
        children: quizNodes.length > 0 ? quizNodes : undefined, // Set to undefined if no quizzes
      }
    })

    // Create topic-level quiz nodes
    const topicQuizNodes = topicQuizzes.map((q: any) => ({
      ...q, // Spread all properties from the quiz object
      key: `quiz-${q.id}`,
      type: 'quiz' as const,
      quiz_id: q.id,
    }))

    // Update the tree
    const currentCourseTree = treeByCourse[courseId] || []
    const topicIndex = currentCourseTree.findIndex(node =>
      node.type === 'topic' && node.id === topicId
    )

    if (topicIndex !== -1) {
      const topicNode = currentCourseTree[topicIndex]

      // Combine topic quizzes and lessons (with their quizzes)
      const allChildren = [...topicQuizNodes, ...lessons]

      const updatedTopic = {
        ...topicNode,
        children: allChildren.length > 0 ? allChildren : undefined,
        loaded: true,
      }

      const newTree = [...currentCourseTree]
      newTree[topicIndex] = updatedTopic
      treeByCourse[courseId] = newTree

      console.log('✅ Updated topic tree:', updatedTopic)
      await nextTick()
    }
  } catch (err) {
    console.error('❌ fetchTopicContent failed:', err)
    notification.error({ message: 'Tải nội dung thất bại' })
  }
}

// ====== SIMPLIFIED: Expand handler ======
const onExpand = async (expanded: boolean, record: TreeNode, courseId: number) => {
  if (!expanded || record.type !== 'topic') return

  console.log('🔍 Expanding topic:', record.id)

  // If already loaded, don't fetch again
  if (record.loaded) {
    console.log('📋 Topic already loaded, skipping fetch')
    return
  }

  await fetchTopicContent(courseId, record.id)
}

// ====== Create Topic ======
const createTopic = ref({ open: false, courseId: 0 })
const openCreateTopic = (courseId: number) => (createTopic.value = { open: true, courseId })
const onCreatedTopic = async (courseId?: number) => {
  const cid = courseId ?? createTopic.value.courseId ?? Number(activeCourseKeys.value[0])
  if (cid) await fetchTopics(cid)
}

const removeTopic = async (topicRow: any) => {
  try {
    await topicApi.deleteTopic(topicRow.id)
    notification.success({ message: 'Đã xoá Topic' })

    const cid = Number(activeCourseKeys.value[0])
    if (cid) {
      // Xóa topic khỏi cây
      const currentTree = treeByCourse[cid] || []
      treeByCourse[cid] = currentTree.filter(node => !(node.type === 'topic' && node.id === topicRow.id))
    }
  } catch (e: any) {
    notification.error({ message: e?.message || 'Xoá Topic thất bại' })
  }
}

// ====== Create Lesson ======
const createLesson = ref<{ open: boolean; topicId: number | null; courseId?: number; topics: any[] }>({ open: false, topicId: null, topics: [] })

const openCreateLesson = async (topicId: number, courseId: number) => {
  try {
    const res = await youtubeApi.getStatus()
    console.log('YouTube status:', res)
    youtubeConnected.value = res?.connected ?? false
  } catch (err) {
    console.error('getStatus failed', err)
    youtubeConnected.value = false
  }
  createLesson.value = { open: true, topicId, courseId, topics: [] }
}

const onCreatedLesson = async ({ topicId, courseId }: { topicId: number; courseId?: number }) => {
  const cid = courseId ?? Number(activeCourseKeys.value[0])
  if (cid) {
    // Mark topic as not loaded to force refresh
    const currentTree = treeByCourse[cid] || []
    treeByCourse[cid] = currentTree.map(node => {
      if (node.type === 'topic' && node.id === topicId) {
        return { ...node, loaded: false }
      }
      return node
    })

    await fetchTopicContent(cid, topicId)
  }
}

// ====== Edit Lesson ======
const editLesson = ref<{ open: boolean; lesson: any | null }>({ open: false, lesson: null })
const openEditLesson = (lessonRow: any) => {
  editLesson.value = {
    open: true,
    lesson: {
      id: lessonRow.lesson_id,
      title: lessonRow.title,
      content: lessonRow.content,
      order: lessonRow.order,
      topic_id: findTopicIdOfLesson(lessonRow)
    }
  }
}

const findTopicIdOfLesson = (row: any): number | null => {
  const cid = Number(activeCourseKeys.value[0])
  const rows = treeByCourse[cid] || []
  for (const t of rows) {
    if (t.type === 'topic') {
      if (t.children?.some(ch =>
        (ch.type === 'lesson' && ch.lesson_id === row.lesson_id) ||
        (ch.type === 'quiz' && ch.quiz_id === row.quiz_id)
      )) {
        return t.id
      }
      // Kiểm tra quiz trong children của lesson
      for (const lesson of t.children?.filter(ch => ch.type === 'lesson') ?? []) {
        if (lesson.children?.some((ch: any) => ch.type === 'quiz' && ch.quiz_id === row.quiz_id)) {
          return t.id
        }
      }
    }
  }
  return null
}

const onEditedLesson = async ({ topicId, courseId }: { topicId: number; courseId?: number }) => {
  const cid = courseId ?? Number(activeCourseKeys.value[0])
  if (cid && topicId) {
    // Mark as not loaded and refresh
    const currentTree = treeByCourse[cid] || []
    treeByCourse[cid] = currentTree.map(node => {
      if (node.type === 'topic' && node.id === topicId) {
        return { ...node, loaded: false }
      }
      return node
    })

    await fetchTopicContent(cid, topicId)
  }
}

// ====== Edit Topic ======
const editTopic = ref<{ open: boolean; topic: any | null }>({ open: false, topic: null })
const openEditTopic = (topicRow: any) => {
  editTopic.value = { open: true, topic: { ...topicRow, id: topicRow.id } }
  console.log('Open edit topic:', topicRow.id)
}

const onEditedTopic = async (payload?: { id?: number; courseId?: number }) => {
  const cid = payload?.courseId ?? createTopic.value.courseId ?? Number(activeCourseKeys.value[0])
  if (cid) await fetchTopics(cid)
}

// ====== Delete Lesson ======
const removeLesson = async (lessonRow: any) => {
  try {
    await lessonApi.deleteLesson(lessonRow.lesson_id)
    notification.success({ message: 'Delete successful' })
    const topicId = findTopicIdOfLesson(lessonRow)
    const cid = Number(activeCourseKeys.value[0])
    if (cid && topicId) {
      // Mark as not loaded and refresh
      const currentTree = treeByCourse[cid] || []
      treeByCourse[cid] = currentTree.map(node => {
        if (node.type === 'topic' && node.id === topicId) {
          return { ...node, loaded: false }
        }
        return node
      })
      await fetchTopicContent(cid, topicId)
    }
  } catch (e: any) {
    notification.error({ message: e?.message || 'Delete failed' })
  }
}

// ====== Quiz Modals ======
const createQuiz = ref<{
  open: boolean
  lessonId: number | null
  topicId: number | null
  scope: 'lesson' | 'topic'
}>({
  open: false,
  lessonId: null,
  topicId: null,
  scope: 'lesson',
})

const openCreateQuiz = (row: any) => {
  if (row.type === 'lesson') {
    const topicId = findTopicIdOfLesson(row)
    createQuiz.value = { open: true, lessonId: row.lesson_id, topicId, scope: 'lesson' }
  }
  if (row.type === 'topic') {
    createQuiz.value = { open: true, lessonId: null, topicId: row.id, scope: 'topic' }
  }
}

const onCreatedQuiz = async ({ lessonId, topicId }: { lessonId?: number; topicId?: number }) => {
  const cid = Number(activeCourseKeys.value[0])
  if (!cid) return

  let targetTopicId: number | null = topicId ?? null
  if (!targetTopicId && lessonId) {
    targetTopicId = findTopicIdOfLesson({ lesson_id: lessonId })
  }

  if (targetTopicId) {
    // Mark topic as not loaded and refresh
    const currentTree = treeByCourse[cid] || []
    treeByCourse[cid] = currentTree.map(node => {
      if (node.type === 'topic' && node.id === targetTopicId) {
        return { ...node, loaded: false }
      }
      return node
    })

    await fetchTopicContent(cid, targetTopicId)
  }
}

const editQuiz = ref<{
  open: boolean
  quiz: any | null
}>({
  open: false,
  quiz: null,
})

const openEditQuiz = (quizRow: any) => {
  editQuiz.value = {
    open: true,
    quiz: {
      ...quizRow,
      topic_id: findTopicIdOfLesson(quizRow),
    }
  }
}

const onEditedQuiz = async ({ lessonId, topicId }: { lessonId?: number; topicId?: number }) => {
  const cid = Number(activeCourseKeys.value[0])
  if (!cid) return

  let targetTopicId = topicId
  if (!targetTopicId && lessonId) {
    targetTopicId = findTopicIdOfLesson({ lesson_id: lessonId })
  }

  if (targetTopicId) {
    // Mark topic as not loaded and refresh
    const currentTree = treeByCourse[cid] || []
    treeByCourse[cid] = currentTree.map(node => {
      if (node.type === 'topic' && node.id === targetTopicId) {
        return { ...node, loaded: false }
      }
      return node
    })

    await fetchTopicContent(cid, targetTopicId)
  }
}

const removeQuiz = async (quizRow: any) => {
  try {
    await quizApi.deleteQuiz(quizRow.quiz_id)
    notification.success({ message: 'Delete successful' })

    const topicId = findTopicIdOfLesson(quizRow)
    const cid = Number(activeCourseKeys.value[0])

    if (cid && topicId) {
      const currentTree = treeByCourse[cid] || []
      treeByCourse[cid] = currentTree.map(node => {
        if (node.type === 'topic' && node.id === topicId) {
          return { ...node, loaded: false }
        }
        return node
      })

      await fetchTopicContent(cid, topicId)
    }
  } catch (e: any) {
    notification.error({ message: e?.message || 'Delete failed' })
  }
}
</script>
