import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import { useClientAuthStore } from '@/stores/clientAuth'
import { useAdminAuthStore } from '@/stores/adminAuth'

/* --- Import Pages --- */
import CustomerLogin from '@/pages/customer/auth/CustomerLogin.vue'
import AdminLogin from '@/pages/admin/auth/AdminLogin.vue'
import GoogleCallback from '@/pages/customer/auth/GoogleCallback.vue'

import Homepage from '@/pages/customer/Homepage.vue'
import Blog from '@/pages/customer/blog/Blog.vue'
import Course from '@/pages/customer/course/Course.vue'
import CourseDetail from '@/pages/customer/course/detail/CourseDetail.vue'
import About from '@/pages/customer/About.vue'

import Cart from '@/pages/customer/cart/Cart.vue'
import VNPayReturn from '@/pages/customer/payment/VNPayReturn.vue'
import MyCourses from '@/pages/customer/myCourses/MyCourses.vue'
import Learning from '@/pages/customer/learning/Learning.vue'
import QuizReview from '@/pages/customer/quiz/QuizReview.vue'
import StudentProfile from '@/pages/customer/profile/StudentProfile.vue'

/* --- Admin & Instructor Pages --- */
import ListUsers from '@/pages/admin/user/ListUsers.vue'
import UserProfile from '@/pages/admin/profile/UserProfile.vue'
import ListCourses from '@/pages/instructor/course/ListCourses.vue'
import CouponList from '@/pages/admin/coupon/CouponList.vue'
import Chat from '@/pages/instructor/chat/Chat.vue'

/* --- Error pages --- */
import NotFound from '@/pages/error/404.vue'
import Forbidden from '@/pages/error/403.vue'
import ApproveCourse from '@/pages/admin/course/approveCourse.vue'

/* --- meta typing --- */
declare module 'vue-router' {
  interface RouteMeta {
    layout?: 'public' | 'admin'
    requiresAuth?: boolean
    roles?: ('admin' | 'student' | 'instructor')[]
    title?: string
  }
}

/* ================================
   PUBLIC ROUTES (Customer)
================================ */
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

  /* ---- Customer Public ---- */
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
    meta: { layout: 'public', title: 'Courses' },
  },
  {
    path: '/courses/:id',
    name: 'CourseDetail',
    component: CourseDetail,
    meta: { layout: 'public', title: 'Course Detail' },
  },
  {
    path: '/about',
    name: 'About',
    component: About,
    meta: { layout: 'public', title: 'About Us' },
  },

  /* ---- Customer Private ---- */
  {
    path: '/cart',
    name: 'Cart',
    component: Cart,
    meta: { layout: 'public', title: 'Cart', requiresAuth: true, roles: ['student'] },
  },
  {
    path: '/payment/vnpay-return',
    name: 'VNPayReturn',
    component: VNPayReturn,
    meta: { layout: 'public', title: 'Kết quả thanh toán' },
  },
  {
    path: '/my-courses/:id',
    name: 'MyCourses',
    component: MyCourses,
    meta: {
      layout: 'public',
      title: 'Khóa học của tôi',
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
      title: 'Hồ sơ',
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

  /* ================================
        ADMIN / INSTRUCTOR ROUTES
  ================================= */

  {
    path: '/admin/login',
    name: 'AdminLogin',
    component: AdminLogin,
    meta: { layout: 'public', title: 'Admin Login', requiresAuth: false },
  },

  {
    path: '/admin',
    meta: { layout: 'admin' },
    children: [
      {
        path: 'users',
        name: 'admin-users',
        component: ListUsers,
        meta: { title: 'User Management', requiresAuth: true, roles: ['admin'] },
      },
      {
        path: 'profile',
        name: 'admin-profile',
        component: UserProfile,
        meta: { title: 'My Profile', requiresAuth: true, roles: ['admin', 'instructor'] },
      },
      {
        path: 'courses',
        name: 'admin-courses',
        component: ListCourses,
        meta: { title: 'Course Management', requiresAuth: true, roles: ['instructor'] },
      },
      {
        path: 'coupons',
        name: 'admin-coupons',
        component: CouponList,
        meta: { title: 'Coupon Management', requiresAuth: true, roles: ['admin'] },
      },
      {
        path: 'approveCourses',
        name: 'admin-aprrove-courses',
        component: ApproveCourse,
        meta: { title: 'Approve Courses', requiresAuth: true, roles: ['admin'] },
      },
      {
        path: 'chat',
        name: 'admin-chat',
        component: Chat,
        meta: { title: 'Instructor Chat', requiresAuth: true, roles: ['admin', 'instructor'] },
      },
    ],
  },

  /* --- Errors --- */
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

/* ================================
   ROUTER INSTANCE + GUARD
================================ */
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

  const isAdminRoute = to.path.startsWith('/admin')
  const auth = isAdminRoute ? adminAuth : clientAuth

  const isAuthenticated = auth.isLoggedIn
  const userRole = auth.user?.role

  /* Set page title */
  if (to.meta.title) {
    document.title = `${to.meta.title} — LearnSphere`
  }

  /* Requires auth? */
  if (to.meta.requiresAuth && !isAuthenticated) {
    return next({ name: isAdminRoute ? 'AdminLogin' : 'CustomerLogin' })
  }

  /* Check roles */
  if (to.meta.roles && isAuthenticated) {
    if (!userRole || !to.meta.roles.includes(userRole)) {
      return next({ name: 'Forbidden' })
    }
  }

  /* Prevent logged-in from accessing login */
  if (isAuthenticated && (to.name === 'CustomerLogin' || to.name === 'AdminLogin')) {
    return next({ name: isAdminRoute ? 'admin-users' : 'Homepage' })
  }
  router.getRoutes().map((r) => r.path)
  next()
})

export default router
