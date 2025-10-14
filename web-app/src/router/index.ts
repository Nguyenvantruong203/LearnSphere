import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import { useClientAuthStore } from '@/stores/clientAuth'
import { useAdminAuthStore } from '@/stores/adminAuth'
import CustomerLogin from '@/pages/customer/auth/CustomerLogin.vue'
import AdminLogin from '@/pages/admin/auth/AdminLogin.vue'
import ListUsers from '@/pages/admin/user/ListUsers.vue'
import Homepage from '@/pages/customer/Homepage.vue'
import Blog from '@/pages/customer/blog/Blog.vue'
import Course from '@/pages/customer/course/Course.vue'
import GoogleCallback from '@/pages/customer/auth/GoogleCallback.vue'

// --- Error Pages ---
import NotFound from '@/pages/error/404.vue'
import Forbidden from '@/pages/error/403.vue'
import UserProfile from '@/pages/admin/profile/UserProfile.vue'
import ListCourses from '@/pages/instructor/course/ListCourses.vue'
import About from '@/pages/customer/About.vue'
import CourseDetail from '@/pages/customer/course/detail/CourseDetail.vue'
import Cart from '@/pages/customer/cart/Cart.vue'
import VNPayReturn from '@/pages/customer/payment/VNPayReturn.vue'
import CouponList from '@/pages/admin/coupon/CouponList.vue'
import MyCourses from '@/pages/customer/myCourses/MyCourses.vue'
import Learning from '@/pages/customer/learning/Learning.vue'
import QuizReview from '@/pages/customer/quiz/QuizReview.vue'
import StudentProfile from '@/pages/customer/profile/StudentProfile.vue'

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
    meta: { layout: 'public', title: 'Blog' },
  },
  {
    path: '/courses',
    name: 'Courses',
    component: Course,
    meta: {
      layout: 'public',
      title: 'Courses',
    },
  },
  {
    path: '/about',
    name: 'About',
    component: About,
    meta: { layout: 'public', title: 'About Us' },
  },
  {
    path: '/courses/:id',
    name: 'CourseDetail',
    component: CourseDetail,
    meta: {
      layout: 'public',
      title: 'Course Detail',
    },
  },
  {
    path: '/cart',
    name: 'Cart',
    component: Cart,
    meta: {
      layout: 'public',
      title: 'Cart',
      requiresAuth: true,
      roles: ['student'],
    },
  },
  {
    path: '/payment/vnpay-return',
    name: 'VNPayReturn',
    component: VNPayReturn,
    meta: {
      layout: 'public',
      title: 'Kết quả thanh toán',
    },
  },
  {
    path: '/my-courses/:id',
    name: 'MyCourses',
    component: MyCourses,
    meta: {
      layout: 'public',
      title: 'Danh sách khóa học của tôi',
      requiresAuth: true,
      roles: ['student'],
    },
  },
  {
    path: '/profile/:id',
    name: 'Profile',
    component: StudentProfile,
    meta: {
      layout: 'public',
      title: 'Hồ sơ của tôi',
      requiresAuth: true,
      roles: ['student'],
    },
  },
  {
    path: '/learning/:courseId',
    name: 'Learning',
    component: Learning,
    meta: {
      layout: 'public',
      title: 'Learning',
      requiresAuth: true,
      roles: ['student'],
    },
  },
  {
    path: '/quiz/:id/review/:attemptId',
    name: 'QuizReview',
    component: QuizReview,
    meta: {
      layout: 'public',
      title: 'Quiz Review',
      requiresAuth: true,
      roles: ['student'],
    },
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
    meta: { layout: 'admin', requiresAuth: true, roles: ['admin', 'instructor'] },
    children: [
      {
        path: 'users',
        name: 'admin-users',
        component: ListUsers,
        meta: { title: 'User Management', roles: ['admin'] },
      },
      {
        path: 'profile',
        name: 'admin-profile',
        component: UserProfile,
        meta: { title: 'My Profile', roles: ['admin', 'instructor'] },
      },
      {
        path: 'courses',
        name: 'admin-courses',
        component: ListCourses,
        meta: { title: 'Course Management', roles: ['instructor'] },
      },
      {
        path: 'coupons',
        name: 'admin-coupons',
        component: CouponList,
        meta: { title: 'Coupon Management', roles: ['admin'] },
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
