import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import CustomerLogin from '@/pages/customer/auth/CustomerLogin.vue'
import AdminLogin from '@/pages/admin/auth/AdminLogin.vue'
import ListUsers from '@/pages/admin/user/ListUsers.vue'
import Homepage from '@/pages/customer/Homepage.vue'
import Blog from '@/pages/customer/Blog.vue'
import Membership from '@/pages/customer/Membership.vue'
import Course from '@/pages/customer/Course.vue'
import GoogleCallback from '@/pages/customer/auth/GoogleCallback.vue'
// --- Admin Pages (example) ---
// import AdminDashboard from '@/pages/admin/Dashboard.vue'

// --- Error Pages ---
import NotFound from '@/pages/error/404.vue'
import Forbidden from '@/pages/error/403.vue'
import UserProfile from '@/pages/admin/profile/UserProfile.vue'
import ListCourses from '@/pages/admin/course/ListCourses.vue'
import ListTopics from '@/pages/admin/topic/ListTopics.vue'

declare module 'vue-router' {
  interface RouteMeta {
    requiresAuth?: boolean
    roles?: ('admin' | 'student' | 'instructor')[]
    layout?: 'public' | 'admin'
    title?: string
  }
}

export const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'Homepage',
    component: Homepage,
    meta: { layout: 'public', title: 'Trang chủ' },
  },
  {
    path: '/login',
    name: 'CustomerLogin',
    component: CustomerLogin,
    meta: { layout: 'public', title: 'Login' },
  },
  {
    path: '/auth/google/callback',
    name: 'GoogleCallback',
    component: GoogleCallback,
    meta: { layout: 'public', title: 'Authenticating...' },
  },
  {
    path: '/blog',
    name: 'Blog',
    component: Blog,
    meta: { layout: 'public', title: 'Blog', requiresAuth: true },
  },
  {
    path: '/membership',
    name: 'Membership',
    component: Membership,
    meta: { layout: 'public', title: 'Membership', requiresAuth: true },
  },
  {
    path: '/courses',
    name: 'Courses',
    component: Course,
    meta: { layout: 'public', title: 'Courses', requiresAuth: true },
  },

  // --- Admin Pages ---
  // {
  //   path: '/admin/dashboard',
  //   name: 'AdminDashboard',
  //   component: AdminDashboard,
  //   meta: { requiresAuth: true, roles: ['admin'], layout: 'admin', title: 'Dashboard' },
  // },

  {
    path: '/admin/login',
    name: 'AdminLogin',
    component: AdminLogin,
    meta: { layout: 'public', title: 'Admin Login' },
  },
  {
    path: '/admin/listUsers',
    name: 'AdminListUsers',
    component: ListUsers,
    meta: { layout: 'public', title: 'Admin List Users' },
  },
  {
    path: '/admin/profile',
    name: 'AdminUpdateProfile',
    component: UserProfile,
    meta: { layout: 'public', title: 'Admin Update Profile' },
  },
  {
    path: '/admin',
    meta: { layout: 'admin', requiresAuth: true, roles: ['admin'] },
    children: [
      {
        path: '',
        name: 'admin-dashboard',
        component: () => import('@/pages/admin/Dashboard.vue'),
        meta: { title: 'Bảng điều khiển' },
      },
      {
        path: 'users',
        name: 'admin-users',
        component: ListUsers,
        meta: { title: 'Quản lý người dùng' },
      },
      {
        path: 'profile',
        name: 'admin-profile',
        component: UserProfile,
        meta: { title: 'Hồ sơ của tôi' },
      },
      {
        path: 'courses',
        name: 'admin-courses',
        component: ListCourses,
        meta: { title: 'Quản lý khóa học' },
      },
      {
        path: 'topics',
        name: 'admin-topics',
        component: ListTopics,
        meta: { title: 'Quản lý chủ đề' },
      },
    ],
  },

  // --- Error Routes ---
  {
    path: '/403',
    name: 'Forbidden',
    component: Forbidden,
    meta: { layout: 'public', title: 'Forbidden' },
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound,
    meta: { layout: 'public', title: 'Not Found' },
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(_to, _from, saved) {
    return saved ?? { top: 0 }
  },
})

router.beforeEach((to, from, next) => {
  // Initialize store here to ensure it's used within an active pinia instance
  const auth = useAuthStore()

  // Set document title
  if (to.meta.title) {
    document.title = `${to.meta.title} — LearnSphere`
  }

  const isAuthenticated = auth.isLoggedIn
  const userRole = auth.user?.role as 'admin' | 'student' | 'instructor' | undefined

  // Rule 1: Route requires authentication
  if (to.meta.requiresAuth && !isAuthenticated) {
    return next({ name: 'Forbidden' })
  }

  // Rule 2: Route requires specific roles
  if (to.meta.roles && isAuthenticated) {
    if (!userRole || !to.meta.roles.includes(userRole)) {
      // User does not have the required role
      return next({ name: 'Forbidden' })
    }
  }

  // Rule 3: Prevent authenticated users from accessing login pages again
  if (isAuthenticated && (to.name === 'CustomerLogin' || to.name === 'AdminLogin')) {
    return next({ name: 'Homepage' })
  }

  // If all checks pass, proceed
  next()
})

export default router
