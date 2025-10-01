<template>
  <div>
    <!-- Recommended Courses -->
    <section class="relative py-24 px-6 lg:px-20 bg-gradient-to-br from-white to-gray-50">
      <div class="relative max-w-7xl mx-auto">
        <div v-motion
          class="flex justify-between items-center mb-16"
          :initial="{ opacity: 0, y: 40 }"
          :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
          <div>
            <h2 class="text-[#2F327D] text-4xl lg:text-5xl font-bold mb-2">Khóa học được đề xuất</h2>
            <p class="text-[#696984] text-lg">Dựa trên sở thích và lịch sử học tập của bạn</p>
          </div>
          <a href="#" class="group text-teal-600 text-xl font-bold hover:text-[#2F327D] transition-colors">
            <span>Xem tất cả</span>
            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
          </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
          <div v-for="(course, index) in recommendedCourses" :key="course.id"
            v-motion
            :initial="{ opacity: 0, y: 50, scale: 0.9 }"
            :enter="{ opacity: 1, y: 0, scale: 1, transition: { delay: index * 0.1, duration: 0.8, type: 'spring' } }">
            <CourseCard 
              :course="course"
              :showPrice="true"
            />
          </div>
        </div>
        
        <!-- Navigation Arrows -->
        <div v-motion
          class="flex justify-end space-x-4"
          :initial="{ opacity: 0, x: 40 }"
          :enter="{ opacity: 1, x: 0, transition: { delay: 0.6, duration: 0.8 } }">
          <button class="group w-14 h-14 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg flex items-center justify-center hover:bg-white hover:shadow-xl transition-all duration-300 border border-gray-100">
            <i class="fas fa-chevron-left text-xl text-[#2F327D] group-hover:text-teal-500 group-hover:-translate-x-0.5 transition-all duration-300"></i>
          </button>
          <button class="group w-14 h-14 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-2xl shadow-lg flex items-center justify-center hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <i class="fas fa-chevron-right text-xl text-white group-hover:translate-x-0.5 transition-transform duration-300"></i>
          </button>
        </div>
      </div>
    </section>
    
    <!-- Get Choice Courses -->
    <section class="py-24 px-6 lg:px-20 bg-white">
      <div class="max-w-7xl mx-auto">
        <div v-motion
          class="flex justify-between items-center mb-16"
          :initial="{ opacity: 0, y: 40 }"
          :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
          <div>
            <h2 class="text-[#2F327D] text-4xl lg:text-5xl font-bold mb-2">Lựa chọn khóa học của bạn</h2>
            <p class="text-[#696984] text-lg">Những khóa học phổ biến và được đánh giá cao</p>
          </div>
          <a href="#" class="group text-teal-600 text-xl font-bold hover:text-[#2F327D] transition-colors">
            <span>Xem tất cả</span>
            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
          </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="(course, index) in choiceCourses" :key="course.id"
            v-motion
            :initial="{ opacity: 0, y: 50, scale: 0.9 }"
            :enter="{ opacity: 1, y: 0, scale: 1, transition: { delay: index * 0.1, duration: 0.8, type: 'spring' } }">
            <CourseCard 
              :course="course"
              :showPrice="true"
            />
          </div>
        </div>
      </div>
    </section>
    
    <!-- Personal Development -->
    <section class="py-24 px-6 lg:px-20 bg-gradient-to-br from-purple-50 to-pink-50">
      <div class="max-w-7xl mx-auto">
        <div v-motion
          class="flex justify-between items-center mb-16"
          :initial="{ opacity: 0, y: 40 }"
          :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
          <div>
            <h2 class="text-[#2F327D] text-4xl lg:text-5xl font-bold mb-2">Phát triển bản thân</h2>
            <p class="text-[#696984] text-lg">Nâng cao kỹ năng và kiến thức cá nhân</p>
          </div>
          <a href="#" class="group text-teal-600 text-xl font-bold hover:text-[#2F327D] transition-colors">
            <span>Xem tất cả</span>
            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
          </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="(course, index) in personalCourses" :key="course.id"
            v-motion
            :initial="{ opacity: 0, y: 50, scale: 0.9 }"
            :enter="{ opacity: 1, y: 0, scale: 1, transition: { delay: index * 0.1, duration: 0.8, type: 'spring' } }">
            <CourseCard 
              :course="course"
              :showPrice="true"
            />
          </div>
        </div>
      </div>
    </section>
    
    <!-- Student Viewing -->
    <section class="py-24 px-6 lg:px-20 bg-gradient-to-br from-blue-50 to-indigo-50">
      <div class="max-w-7xl mx-auto">
        <div v-motion
          class="flex justify-between items-center mb-16"
          :initial="{ opacity: 0, y: 40 }"
          :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
          <div>
            <h2 class="text-[#2F327D] text-4xl lg:text-5xl font-bold mb-2">Học viên đang xem</h2>
            <p class="text-[#696984] text-lg">Những khóa học được quan tâm nhiều nhất hiện tại</p>
          </div>
          <a href="#" class="group text-teal-600 text-xl font-bold hover:text-[#2F327D] transition-colors">
            <span>Xem tất cả</span>
            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
          </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="(course, index) in studentCourses" :key="course.id"
            v-motion
            :initial="{ opacity: 0, y: 50, scale: 0.9 }"
            :enter="{ opacity: 1, y: 0, scale: 1, transition: { delay: index * 0.1, duration: 0.8, type: 'spring' } }">
            <CourseCard 
              :course="course"
              :showPrice="true"
            />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import CourseCard from './CourseCard.vue'

const recommendedCourses = ref([
  {
    id: 1,
    title: 'AWS Certified Solutions Architect',
    description: 'Học cách thiết kế và triển khai hệ thống trên AWS với các best practices và patterns hiện đại',
    instructor: 'Nguyễn Thị Linh',
    originalPrice: '2.500.000đ',
    price: '2.000.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/9eRRfPvnBF.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/vKrXEuwtJc.png',
    category: 'Cloud Computing',
    duration: '3 tháng'
  },
  {
    id: 2,
    title: 'Full Stack Web Development',
    description: 'Khóa học toàn diện về phát triển web từ frontend đến backend với React và Node.js',
    instructor: 'Trần Văn Nam',
    originalPrice: '3.000.000đ',
    price: '2.400.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/9kmyN1T7LL.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/07QdZx2H2X.png',
    category: 'Lập trình',
    duration: '4 tháng'
  },
  {
    id: 3,
    title: 'UI/UX Design Mastery',
    description: 'Nắm vững nguyên lý thiết kế giao diện và trải nghiệm người dùng với Figma và Adobe XD',
    instructor: 'Lê Thị Hương',
    originalPrice: '2.200.000đ',
    price: '1.800.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/fo0qk6bVR6.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/wOgB0ddBey.png',
    category: 'Thiết kế',
    duration: '2.5 tháng'
  },
  {
    id: 4,
    title: 'Digital Marketing Strategy',
    description: 'Học cách xây dựng chiến lược marketing số hiệu quả và tối ưu hóa ROI',
    instructor: 'Phạm Minh Tuấn',
    originalPrice: '1.800.000đ',
    price: '1.500.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/K3DP4zHm6n.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/pWGv4wy3i7.png',
    category: 'Marketing',
    duration: '2 tháng'
  }
])

const choiceCourses = ref([
  {
    id: 5,
    title: 'Data Science with Python',
    description: 'Khám phá thế giới khoa học dữ liệu với Python, pandas, numpy và machine learning',
    instructor: 'Hoàng Văn Đức',
    originalPrice: '2.800.000đ',
    price: '2.200.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/6JQCEDiJZG.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/iAnsHmPEQo.png',
    category: 'Data Science',
    duration: '3.5 tháng'
  },
  {
    id: 6,
    title: 'Mobile App Development',
    description: 'Phát triển ứng dụng di động với React Native và Flutter cho iOS và Android',
    instructor: 'Vũ Thị Mai',
    originalPrice: '2.600.000đ',
    price: '2.100.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/GULpG9CdNY.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/SWrH36CmU4.png',
    category: 'Mobile',
    duration: '3 tháng'
  },
  {
    id: 7,
    title: 'Blockchain & Cryptocurrency',
    description: 'Tìm hiểu công nghệ blockchain, smart contracts và phát triển DApps',
    instructor: 'Đặng Quốc Anh',
    originalPrice: '3.200.000đ',
    price: '2.500.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/wGeBwdBjo5.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/G4wCVBy5vq.png',
    category: 'Blockchain',
    duration: '4 tháng'
  },
  {
    id: 8,
    title: 'Cybersecurity Fundamentals',
    description: 'Nền tảng an ninh mạng, ethical hacking và bảo mật hệ thống thông tin',
    instructor: 'Bùi Thanh Hải',
    originalPrice: '2.400.000đ',
    price: '1.900.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/zoz3JXDdr0.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/fpdGFXj0iY.png',
    category: 'Bảo mật',
    duration: '2.5 tháng'
  }
])

const personalCourses = ref([
  {
    id: 9,
    title: 'Leadership & Management',
    description: 'Phát triển kỹ năng lãnh đạo và quản lý nhóm hiệu quả trong môi trường công việc',
    instructor: 'Ngô Thị Lan',
    originalPrice: '2.000.000đ',
    price: '1.600.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/v1kPY9zYTb.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/c7GqZaG3O8.png',
    category: 'Quản lý',
    duration: '2 tháng'
  },
  {
    id: 10,
    title: 'Public Speaking & Communication',
    description: 'Nâng cao kỹ năng thuyết trình, giao tiếp và tạo ấn tượng trước đám đông',
    instructor: 'Lý Minh Quang',
    originalPrice: '1.500.000đ',
    price: '1.200.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/fkN3zy9WRr.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/zSakkGiwYS.png',
    category: 'Kỹ năng mềm',
    duration: '1.5 tháng'
  },
  {
    id: 11,
    title: 'Time Management & Productivity',
    description: 'Quản lý thời gian hiệu quả và tăng năng suất làm việc với các phương pháp khoa học',
    instructor: 'Cao Thị Ngọc',
    originalPrice: '1.200.000đ',
    price: '900.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/MHzaLVa2Y0.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/xbpC9Gw5yg.png',
    category: 'Năng suất',
    duration: '1 tháng'
  },
  {
    id: 12,
    title: 'Emotional Intelligence',
    description: 'Phát triển trí tuệ cảm xúc để cải thiện mối quan hệ và hiệu quả công việc',
    instructor: 'Phan Văn Hùng',
    originalPrice: '1.400.000đ',
    price: '1.100.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/LodOiwothd.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/BWLm2QpsNk.png',
    category: 'Tâm lý học',
    duration: '1.5 tháng'
  }
])

const studentCourses = ref([
  {
    id: 13,
    title: 'Artificial Intelligence Basics',
    description: 'Nhập môn trí tuệ nhân tạo, machine learning và deep learning cơ bản',
    instructor: 'Tô Minh Đức',
    originalPrice: '3.500.000đ',
    price: '2.800.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/kY2aUeOubN.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/2nLpng',
    category: 'AI',
    duration: '4 tháng'
  },
  {
    id: 14,
    title: 'Business Analytics',
    description: 'Phân tích dữ liệu kinh doanh để đưa ra quyết định chiến lược thông minh',
    instructor: 'Đinh Thị Hà',
    originalPrice: '2.300.000đ',
    price: '1.800.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/kY2aUeOubN.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/2nLpng',
    category: 'Analytics',
    duration: '2.5 tháng'
  },
  {
    id: 15,
    title: 'E-commerce Development',
    description: 'Xây dựng website thương mại điện tử hoàn chỉnh với các tính năng hiện đại',
    instructor: 'Huỳnh Văn Long',
    originalPrice: '2.700.000đ',
    price: '2.200.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/kY2aUeOubN.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/2nLpng',
    category: 'E-commerce',
    duration: '3 tháng'
  },
  {
    id: 16,
    title: 'Content Creation & Video Editing',
    description: 'Tạo nội dung số và chỉnh sửa video chuyên nghiệp cho social media và marketing',
    instructor: 'Võ Thị Kim',
    originalPrice: '1.600.000đ',
    price: '1.300.000đ',
    image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/kY2aUeOubN.png',
    avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/2nLpng',
    category: 'Content',
    duration: '2 tháng'
  }
])
</script>
