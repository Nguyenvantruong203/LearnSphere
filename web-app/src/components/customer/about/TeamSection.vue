<template>
  <section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
      <!-- Section Header -->
      <div
        v-motion
        class="text-center mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }"
      >
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          Our Expert Team
        </h2>
        <p class="text-lg text-[#696984] max-w-3xl mx-auto">
          Talented and dedicated people shaping the future of online education
        </p>
      </div>

      <!-- Team Grid -->
      <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16"
      >
        <div
          v-for="(member, index) in teamMembers"
          :key="member.id"
          v-motion
          :initial="{ opacity: 0, y: 50, scale: 0.9 }"
          :enter="{
            opacity: 1,
            y: 0,
            scale: 1,
            transition: { delay: index * 0.1, duration: 0.8, type: 'spring' },
          }"
          class="group flex flex-col"
        >
          <div
            class="relative bg-gradient-to-br from-gray-50 to-white rounded-3xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4 flex flex-col h-full"
          >
            <!-- Member Image -->
            <div class="relative mb-6">
              <div
                class="relative mx-auto w-32 h-32 rounded-full overflow-hidden shadow-lg group-hover:shadow-xl transition-shadow duration-300"
              >
                <img
                  :src="member.avatar"
                  :alt="member.name"
                  class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                />
              </div>
              <div
                class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 rounded-full border-4 border-white shadow-lg"
              ></div>
            </div>

            <!-- Member Info -->
            <div class="text-center flex-1 flex flex-col justify-between">
              <div>
                <h3 class="text-xl font-bold text-[#2F327D] mb-2">
                  {{ member.name }}
                </h3>
                <p class="text-teal-600 font-semibold mb-3">
                  {{ member.position }}
                </p>
                <p class="text-[#696984] text-sm leading-relaxed mb-6">
                  {{ member.description }}
                </p>
              </div>

              <!-- Skills -->
              <div class="flex flex-wrap justify-center gap-2 mb-6">
                <span
                  v-for="skill in member.skills"
                  :key="skill"
                  class="px-3 py-1 bg-gradient-to-r from-teal-100 to-cyan-100 text-teal-700 text-xs font-medium rounded-full"
                >
                  {{ skill }}
                </span>
              </div>

              <!-- Social Links -->
              <div class="flex justify-center space-x-4 mt-auto">
                <a
                  v-for="social in member.socials"
                  :key="social.platform"
                  :href="social.url"
                  target="_blank"
                  rel="noopener"
                  :class="`w-10 h-10 rounded-full flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-lg ${social.color}`"
                >
                  <i :class="social.icon"></i>
                </a>
              </div>
            </div>

            <!-- Decorative elements -->
            <div
              class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
            >
              <div
                class="w-3 h-3 bg-gradient-to-r from-teal-400 to-cyan-400 rounded-full animate-pulse"
              ></div>
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
    name: 'Nguyen Van Truong',
    position: 'CEO & Founder',
    description:
      'With over 10 years of experience in education and technology, he leads the vision and strategy behind LearnSphere.',
    avatar:
      'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    skills: ['Leadership', 'Strategy', 'EdTech Innovation'],
    socials: [
      {
        platform: 'LinkedIn',
        url: '#',
        icon: 'fab fa-linkedin-in',
        color: 'bg-blue-600 hover:bg-blue-700',
      },
      {
        platform: 'Twitter',
        url: '#',
        icon: 'fab fa-twitter',
        color: 'bg-sky-500 hover:bg-sky-600',
      },
    ],
  },
  {
    id: 2,
    name: 'Tran Thi Minh Anh',
    position: 'CTO',
    description:
      'A technology expert experienced in building large-scale systems and advanced cloud computing architecture.',
    avatar:
      'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    skills: ['Cloud Architecture', 'AI/ML', 'Full Stack Development'],
    socials: [
      {
        platform: 'GitHub',
        url: '#',
        icon: 'fab fa-github',
        color: 'bg-gray-800 hover:bg-gray-900',
      },
      {
        platform: 'LinkedIn',
        url: '#',
        icon: 'fab fa-linkedin-in',
        color: 'bg-blue-600 hover:bg-blue-700',
      },
    ],
  },
  {
    id: 3,
    name: 'Le Hoang Nam',
    position: 'Head of Product',
    description:
      'A UX/UI specialist passionate about creating intuitive and delightful learning experiences.',
    avatar:
      'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    skills: ['Product Design', 'User Research', 'Prototyping'],
    socials: [
      {
        platform: 'Dribbble',
        url: '#',
        icon: 'fab fa-dribbble',
        color: 'bg-pink-500 hover:bg-pink-600',
      },
      {
        platform: 'Behance',
        url: '#',
        icon: 'fab fa-behance',
        color: 'bg-blue-500 hover:bg-blue-600',
      },
    ],
  },
  {
    id: 4,
    name: 'Pham Thi Huong',
    position: 'Head of Content',
    description:
      'An academic with 15 years of teaching experience, specializing in developing high-quality educational materials.',
    avatar:
      'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    skills: ['Curriculum Design', 'Educational Psychology', 'Content Strategy'],
    socials: [
      {
        platform: 'LinkedIn',
        url: '#',
        icon: 'fab fa-linkedin-in',
        color: 'bg-blue-600 hover:bg-blue-700',
      },
      {
        platform: 'Academia',
        url: '#',
        icon: 'fas fa-graduation-cap',
        color: 'bg-green-600 hover:bg-green-700',
      },
    ],
  },
  {
    id: 5,
    name: 'Do Van Khoi',
    position: 'Lead Developer',
    description:
      'A full-stack developer specializing in modern web technologies and scalable microservice architectures.',
    avatar:
      'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    skills: ['Vue.js', 'Node.js', 'Docker', 'AWS'],
    socials: [
      {
        platform: 'GitHub',
        url: '#',
        icon: 'fab fa-github',
        color: 'bg-gray-800 hover:bg-gray-900',
      },
      {
        platform: 'Stack Overflow',
        url: '#',
        icon: 'fab fa-stack-overflow',
        color: 'bg-orange-500 hover:bg-orange-600',
      },
    ],
  },
  {
    id: 6,
    name: 'Ngo Thi Lan',
    position: 'Marketing Director',
    description:
      'A digital marketing expert with experience in brand strategy and building vibrant learning communities.',
    avatar:
      'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    skills: ['Digital Marketing', 'Brand Strategy', 'Community Growth'],
    socials: [
      {
        platform: 'LinkedIn',
        url: '#',
        icon: 'fab fa-linkedin-in',
        color: 'bg-blue-600 hover:bg-blue-700',
      },
      {
        platform: 'Instagram',
        url: '#',
        icon: 'fab fa-instagram',
        color:
          'bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600',
      },
    ],
  },
])
</script>
