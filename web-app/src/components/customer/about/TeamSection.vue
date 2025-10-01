<template>
  <section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
      <!-- Section Header -->
      <div v-motion
        class="text-center mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          Đội ngũ chuyên gia
        </h2>
        <p class="text-lg text-[#696984] max-w-3xl mx-auto">
          Những con người tài năng và tận tâm đang xây dựng tương lai giáo dục trực tuyến
        </p>
      </div>

      <!-- Team Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
        <div v-for="(member, index) in teamMembers" :key="member.id"
          v-motion
          :initial="{ opacity: 0, y: 50, scale: 0.9 }"
          :enter="{ opacity: 1, y: 0, scale: 1, transition: { delay: index * 0.1, duration: 0.8, type: 'spring' } }"
          class="group">
          
          <div class="relative bg-gradient-to-br from-gray-50 to-white rounded-3xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4">
            <!-- Member Image -->
            <div class="relative mb-6">
              <div class="relative mx-auto w-32 h-32 rounded-full overflow-hidden shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                <img :src="member.avatar" :alt="member.name" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
              </div>
              <!-- Online status -->
              <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 rounded-full border-4 border-white shadow-lg"></div>
            </div>

            <!-- Member Info -->
            <div class="text-center">
              <h3 class="text-xl font-bold text-[#2F327D] mb-2">{{ member.name }}</h3>
              <p class="text-teal-600 font-semibold mb-3">{{ member.position }}</p>
              <p class="text-[#696984] text-sm leading-relaxed mb-6">{{ member.description }}</p>
              
              <!-- Skills -->
              <div class="flex flex-wrap justify-center gap-2 mb-6">
                <span v-for="skill in member.skills" :key="skill" 
                  class="px-3 py-1 bg-gradient-to-r from-teal-100 to-cyan-100 text-teal-700 text-xs font-medium rounded-full">
                  {{ skill }}
                </span>
              </div>

              <!-- Social Links -->
              <div class="flex justify-center space-x-4">
                <a v-for="social in member.socials" :key="social.platform" 
                  :href="social.url" 
                  :class="`w-10 h-10 rounded-full flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-lg ${social.color}`">
                  <i :class="social.icon"></i>
                </a>
              </div>
            </div>

            <!-- Decorative elements -->
            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <div class="w-3 h-3 bg-gradient-to-r from-teal-400 to-cyan-400 rounded-full animate-pulse"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Join Team CTA -->
      <div v-motion
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { delay: 0.8, duration: 0.8 } }"
        class="text-center">
        
        <div class="bg-gradient-to-r from-teal-50 via-cyan-50 to-blue-50 rounded-3xl p-12 border border-gray-100 relative overflow-hidden">
          <!-- Background decoration -->
          <div class="absolute inset-0 opacity-5">
            <div class="absolute top-10 left-10 w-32 h-32 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full blur-2xl"></div>
          </div>
          
          <div class="relative z-10">
            <h3 class="text-3xl font-bold text-[#2F327D] mb-4">
              🚀 Gia nhập đội ngũ LearnSphere
            </h3>
            <p class="text-lg text-[#696984] mb-8 max-w-2xl mx-auto">
              Bạn đam mê giáo dục và công nghệ? Hãy cùng chúng tôi xây dựng tương lai của học tập trực tuyến!
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
              <button class="group px-8 py-4 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-xl font-semibold text-lg hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                <span class="flex items-center space-x-2">
                  <span>Xem vị trí tuyển dụng</span>
                  <i class="fas fa-briefcase group-hover:scale-110 transition-transform duration-300"></i>
                </span>
              </button>
              
              <button class="group px-8 py-4 bg-white/80 backdrop-blur-sm text-[#2F327D] rounded-xl font-semibold text-lg hover:bg-white transition-all duration-300 border border-gray-200 hover:shadow-lg">
                <span class="flex items-center space-x-2">
                  <i class="fas fa-paper-plane group-hover:scale-110 transition-transform duration-300"></i>
                  <span>Gửi CV</span>
                </span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface Social {
  platform: string
  url: string
  icon: string
  color: string
}

interface TeamMember {
  id: number
  name: string
  position: string
  description: string
  avatar: string
  skills: string[]
  socials: Social[]
}

const teamMembers = ref<TeamMember[]>([
  {
    id: 1,
    name: 'Nguyễn Văn Trường',
    position: 'CEO & Founder',
    description: 'Với hơn 10 năm kinh nghiệm trong lĩnh vực giáo dục và công nghệ, anh là người dẫn dắt tầm nhìn phát triển LearnSphere.',
    avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    skills: ['Leadership', 'Strategy', 'Education Tech'],
    socials: [
      { platform: 'LinkedIn', url: '#', icon: 'fab fa-linkedin-in', color: 'bg-blue-600 hover:bg-blue-700' },
      { platform: 'Twitter', url: '#', icon: 'fab fa-twitter', color: 'bg-sky-500 hover:bg-sky-600' }
    ]
  },
  {
    id: 2,
    name: 'Trần Thị Minh Anh',
    position: 'CTO',
    description: 'Chuyên gia công nghệ với kinh nghiệm phát triển các hệ thống quy mô lớn và kiến trúc cloud computing.',
    avatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    skills: ['Cloud Architecture', 'AI/ML', 'Full-stack'],
    socials: [
      { platform: 'GitHub', url: '#', icon: 'fab fa-github', color: 'bg-gray-800 hover:bg-gray-900' },
      { platform: 'LinkedIn', url: '#', icon: 'fab fa-linkedin-in', color: 'bg-blue-600 hover:bg-blue-700' }
    ]
  },
  {
    id: 3,
    name: 'Lê Hoàng Nam',
    position: 'Head of Product',
    description: 'Chuyên gia UX/UI với niềm đam mê tạo ra những trải nghiệm học tập tuyệt vời và dễ sử dụng.',
    avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    skills: ['Product Design', 'User Research', 'Prototyping'],
    socials: [
      { platform: 'Dribbble', url: '#', icon: 'fab fa-dribbble', color: 'bg-pink-500 hover:bg-pink-600' },
      { platform: 'Behance', url: '#', icon: 'fab fa-behance', color: 'bg-blue-500 hover:bg-blue-600' }
    ]
  },
  {
    id: 4,
    name: 'Phạm Thị Hương',
    position: 'Head of Content',
    description: 'Giáo sư với 15 năm kinh nghiệm giảng dạy, chuyên phát triển nội dung giáo dục chất lượng cao.',
    avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    skills: ['Curriculum Design', 'Educational Psychology', 'Content Strategy'],
    socials: [
      { platform: 'LinkedIn', url: '#', icon: 'fab fa-linkedin-in', color: 'bg-blue-600 hover:bg-blue-700' },
      { platform: 'Academia', url: '#', icon: 'fas fa-graduation-cap', color: 'bg-green-600 hover:bg-green-700' }
    ]
  },
  {
    id: 5,
    name: 'Đỗ Văn Khôi',
    position: 'Lead Developer',
    description: 'Full-stack developer với chuyên môn về các công nghệ web hiện đại và kiến trúc microservices.',
    avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    skills: ['Vue.js', 'Node.js', 'Docker', 'AWS'],
    socials: [
      { platform: 'GitHub', url: '#', icon: 'fab fa-github', color: 'bg-gray-800 hover:bg-gray-900' },
      { platform: 'Stack Overflow', url: '#', icon: 'fab fa-stack-overflow', color: 'bg-orange-500 hover:bg-orange-600' }
    ]
  },
  {
    id: 6,
    name: 'Ngô Thị Lan',
    position: 'Marketing Director',
    description: 'Chuyên gia marketing số với kinh nghiệm xây dựng thương hiệu và phát triển cộng đồng học tập.',
    avatar: 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    skills: ['Digital Marketing', 'Brand Strategy', 'Community Building'],
    socials: [
      { platform: 'LinkedIn', url: '#', icon: 'fab fa-linkedin-in', color: 'bg-blue-600 hover:bg-blue-700' },
      { platform: 'Instagram', url: '#', icon: 'fab fa-instagram', color: 'bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600' }
    ]
  }
])
</script>
