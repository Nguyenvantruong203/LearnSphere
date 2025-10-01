import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import { useClientAuthStore } from '@/stores/clientAuth'
import { useAdminAuthStore } from '@/stores/adminAuth'
import CustomerLogin from '@/pages/customer/auth/CustomerLogin.vue'
import AdminLogin from '@/pages/admin/auth/AdminLogin.vue'
import ListUsers from '@/pages/admin/user/ListUsers.vue'
import Homepage from '@/pages/customer/Homepage.vue'
import Blog from '@/pages/customer/Blog.vue'
import Course from '@/pages/customer/Course.vue'
import GoogleCallback from '@/pages/customer/auth/GoogleCallback.vue'

// --- Error Pages ---
import NotFound from '@/pages/error/404.vue'
import Forbidden from '@/pages/error/403.vue'
import UserProfile from '@/pages/admin/profile/UserProfile.vue'
import ListCourses from '@/pages/admin/course/ListCourses.vue'
import About from '@/pages/customer/About.vue'

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
    path: '/login',
    name: 'CustomerLogin',
    component: CustomerLogin,
    meta: { layout: 'public', title: 'Login' },
  },
  {
    path: '/google/callback-login',
    name: 'GoogleCallback',
    component: GoogleCallback,
    meta: { layout: 'public', title: 'Authenticating...' },
  },
  {
    path: '/',
    name: 'Homepage',
    component: Homepage,
    meta: { layout: 'public', title: 'Trang chủ' },
  },
  {
    path: '/blog',
    name: 'Blog',
    component: Blog,
    meta: { layout: 'public', title: 'Blog', requiresAuth: true, roles: ['student', 'instructor'] },
  },
  {
    path: '/courses',
    name: 'Courses',
    component: Course,
    meta: {
      layout: 'public',
      title: 'Courses',
      requiresAuth: true,
      roles: ['student', 'instructor'],
    },
  },
  {
    path: '/about',
    name: 'About',
    component: About,
    meta: { layout: 'public', title: 'About Us' },
  },
  // --- Admin Pages ---
  {
    path: '/admin/login',
    name: 'AdminLogin',
    component: AdminLogin,
    meta: { layout: 'public', title: 'Admin Login' },
  },
  {
    path: '/admin',
    meta: { layout: 'admin', requiresAuth: true, roles: ['admin'] },
    children: [
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
  const clientAuth = useClientAuthStore()
  const adminAuth = useAdminAuthStore()

  // Chọn store phù hợp
  const isAdminRoute = to.path.startsWith('/admin')
  const auth = isAdminRoute ? adminAuth : clientAuth

  const isAuthenticated = auth.isLoggedIn
  const userRole = auth.user?.role as 'admin' | 'student' | 'instructor' | undefined

  // Set document title
  if (to.meta.title) {
    document.title = `${to.meta.title} — LearnSphere`
  }

  // Rule 1: Route requires authentication
  if (to.meta.requiresAuth && !isAuthenticated) {
    if (isAdminRoute) {
      return next({ name: 'AdminLogin' })
    }
    return next({ name: 'CustomerLogin' })
  }

  // Rule 2: Route requires specific roles
  if (to.meta.roles && isAuthenticated) {
    if (!userRole || !to.meta.roles.includes(userRole)) {
      return next({ name: 'Forbidden' })
    }
  }

  // Rule 3: Prevent authenticated users from accessing login pages again
  if (isAuthenticated && (to.name === 'CustomerLogin' || to.name === 'AdminLogin')) {
    return next({ name: isAdminRoute ? 'admin-users' : 'Homepage' })
  }

  next()
})

export default router
