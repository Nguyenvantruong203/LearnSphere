<template>
  <div class="p-6 space-y-10 h-[calc(100vh-90px)] overflow-y-auto">

    <!-- ==============================
         SECTION 1 — OVERVIEW CARDS
    ============================== -->
    <section>
      <h2 class="text-xl font-bold mb-6">System Overview</h2>

      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 animate-pulse">
        <div v-for="n in 4" :key="n" class="h-24 bg-gray-200 rounded-xl"></div>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div v-for="card in overviewCards" :key="card.title"
          class="p-6 bg-white shadow rounded-xl border hover:shadow-lg transition">
          <div class="flex justify-between">
            <span class="text-gray-500 text-sm">{{ card.title }}</span>
            <i :class="card.icon" class="text-xl text-teal-600"></i>
          </div>
          <p class="text-3xl font-bold mt-3">{{ card.value }}</p>
        </div>
      </div>
    </section>

    <!-- ==============================
         SECTION 2 — REVENUE + USERS
    ============================== -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- Revenue Chart -->
      <div class="p-6 bg-white rounded-xl shadow border">
        <h3 class="font-semibold mb-3">Monthly Revenue</h3>
        <v-chart :option="revenueChart" class="h-[320px]" autoresize />
      </div>

      <!-- User Stats Pie -->
      <div class="p-6 bg-white rounded-xl shadow border">
        <h3 class="font-semibold mb-3">User Statistics</h3>
        <v-chart :option="userChart" class="h-[320px]" autoresize />
      </div>

    </section>

    <!-- ==============================
         SECTION 3 — CHAT STATS
    ============================== -->
    <section>
      <h3 class="text-xl font-bold mb-4">Support Interactions</h3>

      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-4 gap-6 animate-pulse">
        <div v-for="n in 4" :key="n" class="h-20 bg-gray-200 rounded-xl"></div>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="p-6 rounded-xl bg-white shadow border">
          <p class="text-sm text-gray-500">Student Messages</p>
          <p class="text-3xl font-bold text-blue-600 mt-2">{{ chatStats.student_messages }}</p>
        </div>

        <div class="p-6 rounded-xl bg-white shadow border">
          <p class="text-sm text-gray-500">Instructor Messages</p>
          <p class="text-3xl font-bold text-violet-600 mt-2">{{ chatStats.instructor_messages }}</p>
        </div>

        <div class="p-6 rounded-xl bg-white shadow border">
          <p class="text-sm text-gray-500">Unread Messages</p>
          <p class="text-3xl font-bold text-red-600 mt-2">{{ chatStats.unread_messages }}</p>
        </div>

        <div class="p-6 rounded-xl bg-white shadow border">
          <p class="text-sm text-gray-500">Active Threads (7 days)</p>
          <p class="text-3xl font-bold text-orange-600 mt-2">{{ chatStats.active_threads }}</p>
        </div>
      </div>
    </section>

    <!-- ==============================
         SECTION 4 — SYSTEM HEALTH
    ============================== -->
    <section>
      <h3 class="text-xl font-bold mb-4">System Health</h3>

      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-4 gap-6 animate-pulse">
        <div v-for="n in 4" :key="n" class="h-20 bg-gray-200 rounded-xl"></div>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="p-6 rounded-xl bg-white shadow border">
          <p class="text-sm text-gray-500">Approved Courses</p>
          <p class="text-3xl font-bold text-green-600 mt-2">{{ systemHealth.approved_courses }}</p>
        </div>

        <div class="p-6 rounded-xl bg-white shadow border">
          <p class="text-sm text-gray-500">Pending Courses</p>
          <p class="text-3xl font-bold text-yellow-600 mt-2">{{ systemHealth.pending_courses }}</p>
        </div>

        <div class="p-6 rounded-xl bg-white shadow border">
          <p class="text-sm text-gray-500">Successful Orders</p>
          <p class="text-3xl font-bold text-blue-600 mt-2">{{ systemHealth.successful_orders }}</p>
        </div>

        <div class="p-6 rounded-xl bg-white shadow border">
          <p class="text-sm text-gray-500">Total Quizzes</p>
          <p class="text-3xl font-bold text-purple-600 mt-2">{{ systemHealth.total_quizzes }}</p>
        </div>
      </div>
    </section>

    <!-- ==============================
         SECTION 5 — TOP COURSES
    ============================== -->
    <section class="p-6 bg-white rounded-xl shadow border">
      <h3 class="font-semibold mb-4">Top Revenue Courses</h3>

      <div v-if="loading" class="space-y-4 animate-pulse">
        <div v-for="n in 5" :key="n" class="h-6 bg-gray-200 rounded"></div>
      </div>

      <div v-else>
        <div v-for="(course, index) in topCourses" :key="index"
          class="flex justify-between py-3 border-b last:border-b-0">
          <span>{{ course.title }}</span>
          <span class="font-semibold text-teal-600">
            {{ formatCurrency(course.revenue) }}
          </span>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { adminDashboardApi } from '@/api/admin/dashboardApi'
import type {
  TopCourse,
  AdminChatStats,
  SystemHealth
} from '@/types/AdminDashboard'

const loading = ref(true)

/* ===============================
   STATE
=============================== */
const overviewCards = ref<any[]>([])
const revenueChart = ref({})
const userChart = ref({})
const topCourses = ref<TopCourse[]>([])
const chatStats = ref<AdminChatStats>({
  student_messages: 0,
  instructor_messages: 0,
  unread_messages: 0,
  active_threads: 0
})
const systemHealth = ref<SystemHealth>({
  approved_courses: 0,
  pending_courses: 0,
  successful_orders: 0,
  total_quizzes: 0
})

/* ===============================
   UTILS
=============================== */
const formatCurrency = (n: number) =>
  n.toLocaleString('en-US', { minimumFractionDigits: 0 }) + ' VND'

/* ===============================
   FETCH DATA
=============================== */
const fetchDashboard = async () => {
  try {
    loading.value = true

    const [
      overviewRes,
      revenueRes,
      userStatsRes,
      topCourseRes,
      chatStatsRes,
      systemHealthRes
    ] = await Promise.all([
      adminDashboardApi.getOverview().catch(() => ({
        total_users: 0,
        total_students: 0,
        total_instructors: 0,
        today_revenue: 0,
        month_revenue: 0,
        new_orders_today: 0,
        new_courses: 0
      })),
      adminDashboardApi.getRevenueMonthly().catch(() => ({
        months: [],
        values: []
      })),
      adminDashboardApi.getUserStats().catch(() => []),
      adminDashboardApi.getTopCourses().catch(() => []),
      adminDashboardApi.getChatStats().catch(() => ({
        student_messages: 0,
        instructor_messages: 0,
        unread_messages: 0,
        active_threads: 0
      })),
      adminDashboardApi.getSystemHealth().catch(() => ({
        approved_courses: 0,
        pending_courses: 0,
        successful_orders: 0,
        total_quizzes: 0
      }))
    ])

    /* Overview cards */
    overviewCards.value = [
      { title: 'Total Users', value: overviewRes.total_users || 0, icon: 'fas fa-users' },
      { title: 'Students', value: overviewRes.total_students || 0, icon: 'fas fa-user-graduate' },
      { title: 'Instructors', value: overviewRes.total_instructors || 0, icon: 'fas fa-chalkboard-teacher' },
      { title: "Today's Revenue", value: formatCurrency(overviewRes.today_revenue || 0), icon: 'fas fa-coins' }
    ]

    /* Revenue Chart */
    revenueChart.value = {
      tooltip: { trigger: 'axis' },
      xAxis: { type: 'category', data: revenueRes.months || [] },
      yAxis: { type: 'value' },
      series: [
        {
          name: 'Revenue',
          type: 'line',
          smooth: true,
          data: revenueRes.values || [],
          areaStyle: {},
          itemStyle: { color: '#14b8a6' }
        }
      ]
    }

    /* User Pie Chart */
    userChart.value = {
      tooltip: {
        trigger: 'item',
        formatter: '{b}: {c} ({d}%)'
      },
      legend: {
        orient: 'vertical',
        right: 10,
        top: 'center',
        textStyle: { fontSize: 14 }
      },
      series: [
        {
          type: 'pie',
          radius: ['40%', '70%'],
          center: ['40%', '50%'],
          data: userStatsRes || [],
          label: {
            show: true,
            formatter: '{b}: {c}',
            fontSize: 14
          },
          emphasis: {
            itemStyle: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
          },
          itemStyle: {
            borderRadius: 8,
            borderColor: '#fff',
            borderWidth: 2
          }
        }
      ]
    }

    /* Top courses */
    topCourses.value = topCourseRes || []

    /* Chat Stats */
    chatStats.value = chatStatsRes || {
      student_messages: 0,
      instructor_messages: 0,
      unread_messages: 0,
      active_threads: 0
    }

    /* System Health */
    systemHealth.value = systemHealthRes || {
      approved_courses: 0,
      pending_courses: 0,
      successful_orders: 0,
      total_quizzes: 0
    }

  } catch (error) {
    console.error('Dashboard fetch error:', error)
  } finally {
    loading.value = false
  }
}

onMounted(fetchDashboard)
</script>