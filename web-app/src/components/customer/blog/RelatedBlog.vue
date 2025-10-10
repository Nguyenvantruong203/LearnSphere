<template>
  <section class="py-24 bg-gradient-to-br from-indigo-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
      <!-- Header -->
      <div
        v-motion
        class="flex justify-between items-center mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }"
      >
        <div>
          <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-2">Related Blogs</h2>
          <p class="text-lg text-[#696984]">Articles you might be interested in</p>
        </div>
        <a
          href="#"
          class="group text-teal-600 text-xl font-bold hover:text-[#2F327D] transition-colors"
        >
          <span>View All</span>
          <i
            class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"
          ></i>
        </a>
      </div>

      <!-- Blog Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <article
          v-for="(post, index) in blogPosts"
          :key="post.id"
          v-motion
          :initial="{ opacity: 0, y: 50, scale: 0.95 }"
          :enter="{
            opacity: 1,
            y: 0,
            scale: 1,
            transition: { delay: index * 0.2, duration: 0.8, type: 'spring' },
          }"
          class="group bg-white rounded-3xl shadow-lg hover:shadow-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-2"
        >
          <!-- Image -->
          <div class="relative overflow-hidden">
            <img
              :src="post.image"
              :alt="post.title"
              class="w-full h-56 object-cover transition-transform duration-500 group-hover:scale-110"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
            ></div>

            <!-- Category badge -->
            <div
              class="absolute top-4 left-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white px-3 py-1 rounded-full text-sm font-semibold"
            >
              {{ post.category }}
            </div>
          </div>

          <!-- Content -->
          <div class="p-8">
            <h3
              class="text-2xl font-bold text-[#2F327D] mb-4 leading-tight group-hover:text-purple-600 transition-colors duration-300 line-clamp-2"
            >
              {{ post.title }}
            </h3>

            <!-- Author Info -->
            <div class="flex items-center mb-4">
              <div class="relative">
                <img
                  :src="post.author.avatar"
                  :alt="post.author.name"
                  class="w-12 h-12 rounded-full object-cover shadow-md"
                />
                <div
                  class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"
                ></div>
              </div>
              <div class="ml-3">
                <span class="text-gray-800 font-semibold block">{{ post.author.name }}</span>
                <span class="text-gray-500 text-sm">{{ post.publishDate }}</span>
              </div>
            </div>

            <p class="text-[#696984] mb-6 leading-relaxed line-clamp-3">{{ post.excerpt }}</p>

            <!-- Footer with stats -->
            <div
              class="flex justify-between items-center pt-4 border-t border-gray-100"
            >
              <div class="flex items-center space-x-4 text-gray-500 text-sm">
                <span class="flex items-center space-x-1">
                  <i class="fas fa-heart"></i>
                  <span>{{ post.likes }}</span>
                </span>
                <span class="flex items-center space-x-1">
                  <i class="fas fa-comment"></i>
                  <span>{{ post.comments }}</span>
                </span>
              </div>

              <button
                class="group/btn flex items-center space-x-2 text-purple-600 font-semibold hover:text-purple-700 transition-colors"
              >
                <span>Read More</span>
                <i
                  class="fas fa-arrow-right group-hover/btn:translate-x-1 transition-transform duration-300"
                ></i>
              </button>
            </div>
          </div>
        </article>
      </div>

      <!-- Pagination -->
      <div
        v-motion
        class="flex justify-center mt-16"
        :initial="{ opacity: 0, y: 30 }"
        :enter="{ opacity: 1, y: 0, transition: { delay: 0.6, duration: 0.8 } }"
      >
        <div class="flex items-center space-x-2">
          <button
            class="w-12 h-12 rounded-2xl bg-white shadow-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:shadow-xl transition-all duration-300"
          >
            <i class="fas fa-chevron-left"></i>
          </button>

          <button
            class="w-12 h-12 rounded-2xl bg-gradient-to-r from-purple-500 to-pink-500 text-white flex items-center justify-center font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
          >
            1
          </button>

          <button
            class="w-12 h-12 rounded-2xl bg-white shadow-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:text-gray-800 hover:shadow-xl transition-all duration-300 font-semibold"
          >
            2
          </button>

          <button
            class="w-12 h-12 rounded-2xl bg-white shadow-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:text-gray-800 hover:shadow-xl transition-all duration-300 font-semibold"
          >
            3
          </button>

          <span class="text-gray-400 px-2">...</span>

          <button
            class="w-12 h-12 rounded-2xl bg-white shadow-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:shadow-xl transition-all duration-300"
          >
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface BlogPost {
  id: number
  title: string
  image: string
  author: {
    name: string
    avatar: string
  }
  excerpt: string
  likes: string
  comments: string
  publishDate: string
  category: string
}

// ✅ Keep original Vietnamese post data
const blogPosts = ref<BlogPost[]>([
  {
    id: 1,
    title:
      'LearnSphere bổ sung 30 triệu đô la vào bảng cân đối kế toán cho giải pháp edtech thân thiện với Zoom',
    image:
      'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
    author: {
      name: 'Nguyễn Minh Anh',
      avatar:
        'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    },
    excerpt:
      'LearnSphere được ra mắt chưa đầy một năm bởi đồng sáng lập Blackboard Michael Chasen, tích hợp độc quyền với các nền tảng học trực tuyến hàng đầu để cung cấp trải nghiệm học tập tốt nhất cho sinh viên và giáo viên.',
    likes: '5.2K',
    comments: '324',
    publishDate: '15 Tháng 9, 2024',
    category: 'Tin tức',
  },
  {
    id: 2,
    title:
      'Cách tích hợp AI vào quá trình học tập để nâng cao hiệu quả giáo dục trực tuyến',
    image:
      'https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
    author: {
      name: 'Trần Văn Đức',
      avatar:
        'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    },
    excerpt:
      'Trí tuệ nhân tạo đang cách mạng hóa ngành giáo dục bằng cách cá nhân hóa trải nghiệm học tập, tự động hóa việc chấm điểm và cung cấp phản hồi thời gian thực cho học sinh.',
    likes: '3.8K',
    comments: '256',
    publishDate: '10 Tháng 9, 2024',
    category: 'Công nghệ',
  },
])
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
