<template>
  <div class="p-6 space-y-8 h-[calc(100vh-85px)] overflow-auto">

    <!-- ===========================
         SECTION 1 — OVERVIEW STATS
    ============================ -->
    <section>
      <h2 class="text-2xl font-bold mb-6 text-gray-800">System Overview</h2>

      <!-- Loading skeleton for overview cards -->
      <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="n in 4" :key="n" class="animate-pulse">
          <div class="p-6 bg-white rounded-2xl shadow">
            <div class="flex items-center justify-between">
              <div class="h-4 bg-gray-200 rounded w-20"></div>
              <div class="h-6 w-6 bg-gray-200 rounded"></div>
            </div>
            <div class="mt-4 h-8 bg-gray-200 rounded w-16"></div>
          </div>
        </div>
      </div>

      <!-- Actual overview cards -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="card in overviewCards" :key="card.title"
          class="p-6 bg-white rounded-2xl shadow hover:shadow-lg transition-all border border-gray-100">
          <div class="flex items-center justify-between">
            <h3 class="text-sm text-gray-600 font-medium">{{ card.title }}</h3>
            <i :class="card.icon" class="text-2xl text-teal-600"></i>
          </div>
          <p class="mt-3 text-3xl font-bold text-gray-800">{{ card.value }}</p>
          <p v-if="card.change" class="mt-1 text-sm" :class="card.changeColor">{{ card.change }}</p>
        </div>
      </div>
    </section>

    <!-- ===========================
         SECTION 2 — REVENUE ANALYTICS  
    ============================ -->
    <section class="space-y-6">
      <h2 class="text-2xl font-bold text-gray-800">Revenue & Finance</h2>

      <!-- Revenue Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Monthly Revenue Chart -->
        <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-700">Monthly Revenue</h3>
            <select class="px-3 py-1 border border-gray-200 rounded-lg text-sm">
              <option>Last 6 months</option>
              <option>Last 12 months</option>
            </select>
          </div>

          <!-- Chart loading skeleton -->
          <div v-if="isLoading" class="animate-pulse">
            <div class="h-[300px] bg-gray-200 rounded"></div>
          </div>
          <v-chart v-else class="h-[300px]" :option="revenueChart" autoresize />
        </div>

        <!-- Revenue Summary (Wallet Info) -->
        <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-700 mb-6">Wallet Information</h3>

          <div v-if="isLoading" class="space-y-4 animate-pulse">
            <div v-for="n in 3" :key="n" class="flex justify-between">
              <div class="h-4 bg-gray-200 rounded w-24"></div>
              <div class="h-4 bg-gray-200 rounded w-20"></div>
            </div>
          </div>

          <div v-else class="space-y-6">
            <div class="flex justify-between items-center p-4 bg-teal-50 rounded-xl">
              <span class="text-sm font-medium text-gray-600">Current Balance</span>
              <span class="text-xl font-bold text-teal-600">{{ formatCurrency(walletInfo.balance) }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Total Earned</span>
              <span class="font-semibold text-gray-800">{{ formatCurrency(walletInfo.totalEarned) }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Withdrawn</span>
              <span class="font-semibold text-gray-800">{{ formatCurrency(walletInfo.totalWithdrawn) }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Withdrawal Count</span>
              <span class="font-semibold text-gray-800">{{ walletInfo.totalPayoutItems || 0 }} times</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Revenue Courses -->
      <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-700">Top Revenue Courses</h3>
          <span class="text-sm text-gray-500">{{ topCourses.length }} courses</span>
        </div>

        <!-- Table loading skeleton -->
        <div v-if="isLoading" class="space-y-3 animate-pulse">
          <div v-for="n in 5" :key="n" class="flex justify-between py-3">
            <div class="h-4 bg-gray-200 rounded w-48"></div>
            <div class="h-4 bg-gray-200 rounded w-24"></div>
          </div>
        </div>

        <!-- Empty state -->
        <div v-else-if="topCourses.length === 0" class="text-center py-8">
          <i class="fas fa-chart-line text-4xl text-gray-300 mb-4"></i>
          <p class="text-gray-500">No revenue data yet</p>
        </div>

        <!-- Actual table -->
        <div v-else class="space-y-2">
          <div v-for="(course, index) in topCourses.slice(0, 10)" :key="course.title"
            class="flex justify-between items-center py-4 hover:bg-gray-50 rounded-lg px-3 transition-colors">
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                :class="index < 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600'">
                {{ index + 1 }}
              </div>
              <span class="font-medium text-gray-700">{{ course.title }}</span>
            </div>
            <span class="text-teal-600 font-semibold">{{ formatCurrency(course.total || 0) }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- ===========================
         SECTION 3 — STUDENT ANALYTICS
    ============================ -->
    <section class="space-y-6">
      <h2 class="text-2xl font-bold text-gray-800">Student Analytics</h2>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Student Summary Cards -->
        <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Student Overview</h3>

          <div v-if="isLoading" class="space-y-4 animate-pulse">
            <div v-for="n in 3" :key="n" class="flex justify-between">
              <div class="h-4 bg-gray-200 rounded w-20"></div>
              <div class="h-6 bg-gray-200 rounded w-12"></div>
            </div>
          </div>

          <div v-else class="space-y-4">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Total Students</span>
              <span class="text-xl font-bold text-blue-600">{{ studentStats.total || 0 }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Active Students</span>
              <span class="text-lg font-semibold text-green-600">{{ studentStats.active || 0 }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Quiz Attempts</span>
              <span class="text-lg font-semibold text-purple-600">{{ studentStats.quizAttempts || 0 }}</span>
            </div>
          </div>
        </div>

        <!-- Daily Learning Activity Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Daily Learning Activity</h3>

          <div v-if="isLoading" class="animate-pulse">
            <div class="h-[250px] bg-gray-200 rounded"></div>
          </div>
          <v-chart v-else class="h-[250px]" :option="activityChart" autoresize />
        </div>
      </div>

      <!-- Course Progress Table -->
      <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Course Progress</h3>

        <!-- Table loading skeleton -->
        <div v-if="isLoading" class="animate-pulse">
          <div class="grid grid-cols-3 gap-4 py-3 border-b">
            <div class="h-4 bg-gray-200 rounded"></div>
            <div class="h-4 bg-gray-200 rounded"></div>
            <div class="h-4 bg-gray-200 rounded"></div>
          </div>
          <div v-for="n in 5" :key="n" class="grid grid-cols-3 gap-4 py-3 border-b">
            <div class="h-4 bg-gray-200 rounded"></div>
            <div class="h-4 bg-gray-200 rounded"></div>
            <div class="h-4 bg-gray-200 rounded"></div>
          </div>
        </div>

        <!-- Empty state -->
        <div v-else-if="courseProgress.length === 0" class="text-center py-8">
          <i class="fas fa-graduation-cap text-4xl text-gray-300 mb-4"></i>
          <p class="text-gray-500">No course progress data yet</p>
        </div>

        <!-- Actual table -->
        <div v-else class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-gray-200">
                <th class="text-left py-3 px-4 font-semibold text-gray-600">Course</th>
                <th class="text-center py-3 px-4 font-semibold text-gray-600">Average Progress</th>
                <th class="text-center py-3 px-4 font-semibold text-gray-600">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in courseProgress" :key="item.title"
                class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <td class="py-4 px-4">
                  <div class="font-medium text-gray-800">{{ item.title }}</div>
                </td>
                <td class="py-4 px-4 text-center">
                  <div class="flex items-center justify-center space-x-2">
                    <div class="w-20 bg-gray-200 rounded-full h-2">
                      <div class="bg-teal-500 h-2 rounded-full" :style="{ width: (item.progress_avg || 0) + '%' }">
                      </div>
                    </div>
                    <span class="text-sm font-medium">{{ Math.round(item.progress_avg || 0) }}%</span>
                  </div>
                </td>
                <td class="py-4 px-4 text-center">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="(item.progress_avg || 0) >= 80 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                    {{ (item.progress_avg || 0) >= 80 ? 'Completed' : 'In Progress' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ===========================
         SECTION 4 — CHAT & SUPPORT ANALYTICS
    ============================ -->
    <section class="space-y-6">
      <h2 class="text-2xl font-bold text-gray-800">Chat & Support Interactions</h2>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Chat Statistics Cards -->
        <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Interaction Overview</h3>

          <div v-if="isLoading" class="space-y-4 animate-pulse">
            <div class="flex justify-between">
              <div class="h-4 bg-gray-200 rounded w-24"></div>
              <div class="h-4 bg-gray-200 rounded w-12"></div>
            </div>
            <div class="flex justify-between">
              <div class="h-4 bg-gray-200 rounded w-24"></div>
              <div class="h-4 bg-gray-200 rounded w-12"></div>
            </div>
          </div>

          <div v-else-if="chatStats" class="space-y-4">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Messages Sent</span>
              <span class="text-xl font-bold text-blue-600">
                {{ chatStats.sent_messages }}
              </span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Messages Received</span>
              <span class="text-lg font-semibold text-green-600">
                {{ chatStats.received_messages }}
              </span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Response Rate</span>
              <span class="text-lg font-semibold text-teal-600">
                {{ chatStats.response_rate }}%
              </span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Avg. Response Time</span>
              <span class="text-lg font-semibold text-purple-600">
                {{ chatStats.avg_response_time }} mins
              </span>
            </div>
          </div>
        </div>

        <!-- Interaction Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Interaction Chart</h3>

          <div v-if="isLoading" class="animate-pulse">
            <div class="h-[250px] bg-gray-200 rounded"></div>
          </div>

          <v-chart v-else-if="chatStats" class="h-[250px]" :option="chatChart" autoresize />
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { dashboardApi } from '@/api/instructor/dashboardApi'
import type {
  RevenueByCourse,
  CourseProgressItem,
  ChatStats
} from '@/types/Dashboard'

/* ============================
   STATE MANAGEMENT
============================ */
const isLoading = ref(true)
const overviewCards = ref<Array<{ title: string; value: string; icon: string; change?: string; changeColor?: string }>>([])
const revenueChart = ref({})
const activityChart = ref({})
const topCourses = ref<RevenueByCourse[]>([])
const courseProgress = ref<CourseProgressItem[]>([])
const chatStats = ref<ChatStats | null>(null)
const chatChart = ref({})

// Wallet & Student info
const walletInfo = ref({
  balance: 0,
  totalEarned: 0,
  totalWithdrawn: 0,
  totalPayoutItems: 0
})

const studentStats = ref({
  total: 0,
  active: 0,
  quizAttempts: 0
})

/* ============================
   UTILITY FUNCTIONS
============================ */
const formatCurrency = (amount: number): string => {
  return amount.toLocaleString('en-US') + ' VND'
}

/* ============================
   DATA FETCHING
============================ */
const fetchDashboard = async () => {
  try {
    isLoading.value = true

    const [
      overview,
      revenueSummary,
      revenueByMonth,
      revenueByCourse,
      studentSummary,
      studentActivity,
      progress,
      chatStatsRes
    ] = await Promise.allSettled([
      dashboardApi.getOverview(),
      dashboardApi.getRevenueSummary(),
      dashboardApi.getRevenueByMonth(),
      dashboardApi.getRevenueByCourse(),
      dashboardApi.getStudentSummary(),
      dashboardApi.getStudentActivity(),
      dashboardApi.getCourseProgress(),
      dashboardApi.getChatStats()
    ])

    // Process overview data
    if (overview.status === 'fulfilled') {
      const data = overview.value
      overviewCards.value = [
        {
          title: 'Total Courses',
          value: (data.total_courses || 0).toString(),
          icon: 'fas fa-book-open',
          change: '+2 new courses',
          changeColor: 'text-green-600'
        },
        {
          title: 'Total Students',
          value: (data.total_students || 0).toString(),
          icon: 'fas fa-users'
        },
        {
          title: 'Active Students',
          value: (data.active_students || 0).toString(),
          icon: 'fas fa-user-check',
          change: 'This week',
          changeColor: 'text-blue-600'
        },
        {
          title: 'Wallet Balance',
          value: formatCurrency(data.wallet?.balance || 0),
          icon: 'fas fa-wallet'
        }
      ]
    }

    // Wallet info
    if (revenueSummary.status === 'fulfilled') {
      const data = revenueSummary.value
      walletInfo.value = {
        balance: data.balance || 0,
        totalEarned: data.total_earned || 0,
        totalWithdrawn: data.total_withdrawn || 0,
        totalPayoutItems: data.total_payout_items || 0
      }
    }

    // Student summary
    if (studentSummary.status === 'fulfilled') {
      const data = studentSummary.value
      studentStats.value = {
        total: data.total_students || 0,
        active: data.active_students || 0,
        quizAttempts: data.quiz_attempts || 0
      }
    }

    // Revenue chart
    if (revenueByMonth.status === 'fulfilled') {
      const data = revenueByMonth.value
      revenueChart.value = {
        tooltip: {
          trigger: 'axis',
          formatter: '{b}: {c} VND'
        },
        xAxis: {
          type: 'category',
          data: data.map(i => i.month),
          axisLabel: { rotate: 45 }
        },
        yAxis: {
          type: 'value',
          axisLabel: {
            formatter: '{value} VND'
          }
        },
        series: [{
          name: 'Revenue',
          type: 'line',
          smooth: true,
          data: data.map(i => i.total),
          areaStyle: { opacity: 0.3 },
          lineStyle: { width: 3, color: '#14b8a6' },
          itemStyle: { color: '#14b8a6' }
        }],
        grid: { left: '3%', right: '4%', bottom: '15%', top: '10%', containLabel: true }
      }
    }

    // Activity chart
    if (studentActivity.status === 'fulfilled') {
      const data = studentActivity.value
      activityChart.value = {
        tooltip: {
          trigger: 'axis',
          formatter: '{b}: {c} completions'
        },
        xAxis: {
          type: 'category',
          data: data.map(i => i.date)
        },
        yAxis: {
          type: 'value',
          minInterval: 1
        },
        series: [{
          name: 'Lesson Completions',
          type: 'bar',
          data: data.map(i => i.completions),
          itemStyle: {
            color: '#6366f1',
            borderRadius: [4, 4, 0, 0]
          }
        }],
        grid: { left: '3%', right: '4%', bottom: '10%', top: '10%', containLabel: true }
      }
    }

    // Top courses & progress
    if (revenueByCourse.status === 'fulfilled') {
      topCourses.value = revenueByCourse.value
    }

    if (progress.status === 'fulfilled') {
      courseProgress.value = progress.value
    }

    // Chat stats & chart
    if (chatStatsRes.status === 'fulfilled') {
      chatStats.value = chatStatsRes.value

      chatChart.value = {
        tooltip: { trigger: 'axis' },
        xAxis: {
          type: 'category',
          data: ['Sent', 'Received', 'Response Rate (%)', 'Active Threads']
        },
        yAxis: { type: 'value' },
        series: [
          {
            type: 'bar',
            data: [
              chatStats.value.sent_messages,
              chatStats.value.received_messages,
              chatStats.value.response_rate,
              chatStats.value.active_threads
            ],
            itemStyle: { color: '#10b981', borderRadius: [6, 6, 0, 0] }
          }
        ]
      }
    }

  } catch (error) {
    console.error('Error loading dashboard:', error)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchDashboard()
})
</script>