<template>
  <div class="p-4">
    <div class="flex items-center justify-between mb-3">
      <h2 class="text-xl font-semibold">Courses → Topics → Lessons</h2>
      <a-button type="primary" @click="openCreateCourse">+ Course</a-button>
    </div>

    <div class="flex flex-col h-[calc(100vh-180px)]">
      <!-- Collapse + Tables -->
      <div class="flex-1 overflow-y-auto">
        <a-collapse v-model:activeKey="activeCourseKeys" accordion>
          <a-collapse-panel v-for="course in courses" :key="course.id" :header="course.title">
            <div class="flex items-center justify-between mb-2">
              <div class="text-gray-500">Course ID: {{ course.id }}</div>
              <div class="space-x-2">
                <a-button size="small" @click="openEditCourse(course)">Edit Course</a-button>
                <a-button size="small" danger @click="confirmRemoveCourse(course)">Delete Course</a-button>
                <a-button size="small" type="primary" @click="openCreateTopic(course.id)">+ Topic</a-button>
              </div>
            </div>

            <a-table :columns="columns" :data-source="treeByCourse[course.id] || []" row-key="key"
              :loading="loading[course.id]" :pagination="false" sticky
              :scroll="{ y: 'calc(100vh - 160px)', x: 'max-content' }" :expandable="{
                childrenColumnName: 'children',
                expandRowByClick: false,
                defaultExpandAllRows: false
              }" @expand="(expanded, record) => onExpandTopic(expanded, record, course.id)" />

          </a-collapse-panel>
        </a-collapse>
      </div>

      <div class="mt-4 flex justify-center">
        <a-pagination :current="pagination.current" :page-size="pagination.pageSize" :total="pagination.total"
          show-size-changer :show-total="pagination.showTotal" @change="onPageChange" @showSizeChange="onPageChange" />
      </div>
    </div>
    <!-- Modals -->
    <CreateCourseModal v-model:open="createCourse.open" @finish="onCreatedCourse" />
    <EditCourseModal v-model:open="editCourse.open" :course="editCourse.course" @finish="onEditedCourse" />

    <CreateTopicModal v-model:open="createTopic.open" :course-id="createTopic.courseId" @finish="onCreatedTopic" />

    <EditTopicModal v-model:open="editTopic.open" :topic="editTopic.topic" @finish="onEditedTopic" />

    <CreateLessonModal v-model:open="createLesson.open" :topic-id="createLesson.topicId"
      :course-id="createLesson.courseId" :youtube-connected="youtubeConnected" @finish="onCreatedLesson" />

    <EditLessonModal v-model:open="editLesson.open" :lesson="editLesson.lesson" @finish="onEditedLesson" />
  </div>
</template>

<script setup lang="ts">
import { ref, h, onMounted, watch, nextTick, reactive } from 'vue'
import { Button, Space, message, Popconfirm, Tag, Modal, notification } from 'ant-design-vue'

import { courseApi } from '@/api/courseApi'
import { topicApi } from '@/api/topicApi'
import { lessonApi } from '@/api/lessonApi'

// ====== Modal components ======
import CreateCourseModal from '@/components/admin/course/actions/CreateCourseModal.vue'
import EditCourseModal from '@/components/admin/course/actions/EditCourseModal.vue'
import CreateTopicModal from '@/components/admin/topic/actions/CreateTopicModal.vue'
import CreateLessonModal from '@/components/admin/lesson/actions/CreateLessonModal.vue'
import EditTopicModal from '@/components/admin/topic/actions/EditTopicModal.vue'

import type { Topic } from '@/types/Topic'
import type { Course } from '@/types/Course'
import { youtubeApi } from '@/api/youtubeApi'

interface TopicNode extends Topic {
  key: string;
  type: "topic";
  children?: TreeNode[] | null;
  loaded?: boolean;
  hasChildren?: boolean;
}

type TreeNode =
  | TopicNode
  | { key: string; type: "lesson"; lesson_id: number; title: string; order: number; content?: string | null }
  | { key: string; type: "loading"; title: string; order: number; lesson_id: number }

const courses = ref<Course[]>([])
const pagination = reactive({
  current: 1,
  pageSize: 10,
  total: 0,
  showTotal: (total: number) => `Tổng ${total} khóa học`
})
const loadingCourses = ref(false)

const activeCourseKeys = ref<(string | number)[]>([])
const treeByCourse = reactive<Record<number, TreeNode[]>>({})
const loading = ref<Record<number, boolean>>({})

// ====== Course Modals ======
const createCourse = ref<{ open: boolean }>({ open: false })
const editCourse = ref<{ open: boolean; course: Course | null }>({ open: false, course: null })

const openCreateCourse = () => { createCourse.value.open = true }
const youtubeConnected = ref(false)

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
    title: `Xoá khóa học: ${course.title}?`,
    okText: 'Xoá',
    cancelText: 'Hủy',
    onOk: () => removeCourse(course.id),
  });
};

const removeCourse = async (courseId: number) => {
  try {
    await courseApi.deleteCourse(courseId);
    message.success('Đã xoá Course');
    await fetchCourses();
  } catch (error: any) {
    message.error(error.message || 'Xoá Course thất bại');
  }
};

// ====== Columns (action gài +Lesson / Edit / Delete) ======
const columns = [
  {
    title: 'Loại',
    key: 'type',
    width: 110,
    customRender: ({ record }: any) => {
      if (record.type === 'topic') return h(Tag, {}, () => 'Topic')
      if (record.type === 'lesson') return h(Tag, { color: 'blue' }, () => 'Lesson')
      if (record.type === 'loading') return h(Tag, { color: 'orange' }, () => 'Loading')
      return null
    }
  },
  { title: 'Tiêu đề', dataIndex: 'title', key: 'title' },
  { title: 'Thứ tự', dataIndex: 'order', key: 'order', width: 100 },
  {
    title: 'Hành động',
    key: 'action',
    width: 260,
    customRender: ({ record }: any) => {
      if (record.type === 'topic') {
        return h(Space, {}, () => [
          h(Button, {
            type: 'link',
            onClick: () => openCreateLesson(record.id, Number(activeCourseKeys.value[0]))
          }, () => '+ Lesson'),
          h(Button, { type: 'link', onClick: () => openEditTopic(record) }, () => 'Sửa'),
          h(Popconfirm,
            {
              title: 'Xoá topic này?',
              onConfirm: () => removeTopic(record),
            },
            { default: () => h(Button, { type: 'link', danger: true }, () => 'Xoá') }
          )
        ])
      } else if (record.type === 'lesson') {
        return h(Space, {}, () => [
          h(Button, { type: 'link', onClick: () => openEditLesson(record) }, () => 'Sửa'),
          h(Popconfirm,
            { title: 'Xoá bài học này?', onConfirm: () => removeLesson(record) },
            { default: () => h(Button, { type: 'link', danger: true }, () => 'Xoá') })
        ])
      }
      return null
    }
  }
]
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
        // QUAN TRỌNG: children phải là array có ít nhất 1 phần tử
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

const fetchLesson = async (courseId: number, topicId: number) => {
  try {
    const res = await lessonApi.getLessons({ topicId, page: 1, limit: 50 })
    const rows = Array.isArray(res.data) ? res.data : res

    const lessons = (rows ?? []).map((ls: any) => ({
      key: `lesson-${ls.id}`,
      type: 'lesson' as const,
      lesson_id: ls.id,
      title: ls.title,
      order: ls.order,
      content: ls.content ?? null
    }))

    const currentCourseTree = treeByCourse[courseId] || []
    const topicIndex = currentCourseTree.findIndex(node =>
      node.type === 'topic' && node.id === topicId
    )
    if (topicIndex !== -1) {
      const updatedTopic = {
        ...currentCourseTree[topicIndex],
        children: lessons,
        loaded: true
      }
      const newTree = [...currentCourseTree]
      newTree[topicIndex] = updatedTopic
      treeByCourse[courseId] = newTree
      await nextTick()
    } else {
      console.error('❌ Topic not found in tree:', topicId)
    }

  } catch (err) {
    console.error('❌ fetchLesson failed with error:', err)

    const currentCourseTree = treeByCourse[courseId] || []
    const topicIndex = currentCourseTree.findIndex(node =>
      node.type === 'topic' && node.id === topicId
    )

    if (topicIndex !== -1) {
      const updatedTopic = {
        ...currentCourseTree[topicIndex],
        children: [], // Empty array
        loaded: true
      }

      const newTree = [...currentCourseTree]
      newTree[topicIndex] = updatedTopic
      treeByCourse[courseId] = newTree
    }
  }
}
// ====== Expand handler (lazy-load lessons) ======
const onExpandTopic = async (expanded: boolean, record: TreeNode, courseId: number) => {

  if (!expanded) {
    console.log('Collapsed - no action needed')
    return
  }

  if (record.type !== 'topic') {
    console.log('Not a topic - no action needed')
    return
  }

  const topicRecord = record as TopicNode

  try {
    await fetchLesson(courseId, topicRecord.id)
  } catch (error) {
    console.error('❌ Fetch lessons failed:', error)
  }
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
    const currentTree = treeByCourse[cid] || []
    treeByCourse[cid] = currentTree.map(node => {
      if (node.type === 'topic' && node.id === topicId) {
        return { ...node, loaded: false }
      }
      return node
    })

    await fetchLesson(cid, topicId)
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

const findTopicIdOfLesson = (lessonRow: any): number | null => {
  const cid = Number(activeCourseKeys.value[0])
  const rows = treeByCourse[cid] || []
  for (const t of rows) {
    if (t.type === 'topic' && t.children?.some(ch => ch.type === 'lesson' && ch.lesson_id === lessonRow.lesson_id)) {
      return t.id
    }
  }
  return null
}
const onEditedLesson = async ({ topicId, courseId }: { topicId: number; courseId?: number }) => {
  const cid = courseId ?? Number(activeCourseKeys.value[0])
  if (cid && topicId) {
    await fetchLesson(cid, topicId)
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
    notification.success({ message: 'Đã xoá Lesson' })
    const topicId = findTopicIdOfLesson(lessonRow)
    const cid = Number(activeCourseKeys.value[0])
    if (cid && topicId) {
      const currentTree = treeByCourse[cid] || []
      treeByCourse[cid] = currentTree.map(node => {
        if (node.type === 'topic' && node.id === topicId) {
          return { ...node, loaded: false }
        }
        return node
      })
      await fetchLesson(cid, topicId)
    }
  } catch (e: any) {
    notification.error({ message: e?.message || 'Xoá thất bại' })
  }
}
</script>