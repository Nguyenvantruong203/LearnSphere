<template>
  <footer class="relative bg-gradient-to-br from-[#2F327D] via-purple-800 to-indigo-900 text-white overflow-hidden">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 opacity-10">
      <div class="absolute top-10 left-10 w-40 h-40 bg-gradient-to-r from-teal-400 to-cyan-400 rounded-full blur-3xl"></div>
      <div class="absolute bottom-10 right-10 w-32 h-32 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full blur-3xl"></div>
      <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-gradient-to-r from-orange-400 to-yellow-400 rounded-full blur-2xl"></div>
    </div>

    <div class="relative z-10">
      <!-- Main Footer Content -->
      <div class="container mx-auto px-6 lg:px-20 py-16">
        <!-- Top Section -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 mb-12">
          <!-- Company Info -->
          <div class="lg:col-span-1 space-y-6">
            <div class="flex items-center space-x-3">
              <img src="@/assets/images/logoFooter.png" alt="LearnSphere Logo" class="h-12 w-auto" />
            </div>
            <p class="text-gray-300 leading-relaxed">
              Nền tảng học trực tuyến hàng đầu Việt Nam, mang đến trải nghiệm học tập hiện đại và hiệu quả cho mọi người.
            </p>
            <!-- Social Media -->
            <div class="flex space-x-4">
              <a href="#" class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-teal-500 hover:scale-110 transition-all duration-300 group">
                <i class="fab fa-facebook-f group-hover:scale-110 transition-transform"></i>
              </a>
              <a href="#" class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-teal-500 hover:scale-110 transition-all duration-300 group">
                <i class="fab fa-twitter group-hover:scale-110 transition-transform"></i>
              </a>
              <a href="#" class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-teal-500 hover:scale-110 transition-all duration-300 group">
                <i class="fab fa-linkedin-in group-hover:scale-110 transition-transform"></i>
              </a>
              <a href="#" class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-teal-500 hover:scale-110 transition-all duration-300 group">
                <i class="fab fa-youtube group-hover:scale-110 transition-transform"></i>
              </a>
            </div>
          </div>

          <!-- Quick Links -->
          <div class="space-y-6">
            <h4 class="text-xl font-bold text-white mb-4">Liên kết nhanh</h4>
            <div class="space-y-3">
              <a href="/" class="block text-gray-300 hover:text-teal-400 transition-colors duration-300 hover:translate-x-1 transform">
                <i class="fas fa-home mr-2"></i>Trang chủ
              </a>
              <a href="/courses" class="block text-gray-300 hover:text-teal-400 transition-colors duration-300 hover:translate-x-1 transform">
                <i class="fas fa-graduation-cap mr-2"></i>Khóa học
              </a>
              <a href="/blog" class="block text-gray-300 hover:text-teal-400 transition-colors duration-300 hover:translate-x-1 transform">
                <i class="fas fa-blog mr-2"></i>Blog
              </a>
              <a href="/about" class="block text-gray-300 hover:text-teal-400 transition-colors duration-300 hover:translate-x-1 transform">
                <i class="fas fa-info-circle mr-2"></i>Về chúng tôi
              </a>
              <a href="/contact" class="block text-gray-300 hover:text-teal-400 transition-colors duration-300 hover:translate-x-1 transform">
                <i class="fas fa-envelope mr-2"></i>Liên hệ
              </a>
            </div>
          </div>

          <!-- Categories -->
          <div class="space-y-6">
            <h4 class="text-xl font-bold text-white mb-4">Danh mục khóa học</h4>
            <div class="space-y-3">
              <a href="#" class="block text-gray-300 hover:text-teal-400 transition-colors duration-300 hover:translate-x-1 transform">
                <i class="fas fa-code mr-2"></i>Lập trình
              </a>
              <a href="#" class="block text-gray-300 hover:text-teal-400 transition-colors duration-300 hover:translate-x-1 transform">
                <i class="fas fa-paint-brush mr-2"></i>Thiết kế
              </a>
              <a href="#" class="block text-gray-300 hover:text-teal-400 transition-colors duration-300 hover:translate-x-1 transform">
                <i class="fas fa-chart-line mr-2"></i>Marketing
              </a>
              <a href="#" class="block text-gray-300 hover:text-teal-400 transition-colors duration-300 hover:translate-x-1 transform">
                <i class="fas fa-briefcase mr-2"></i>Kinh doanh
              </a>
              <a href="#" class="block text-gray-300 hover:text-teal-400 transition-colors duration-300 hover:translate-x-1 transform">
                <i class="fas fa-camera mr-2"></i>Nhiếp ảnh
              </a>
            </div>
          </div>

          <!-- Newsletter -->
          <div class="space-y-6">
            <h4 class="text-xl font-bold text-white mb-4">Đăng ký nhận tin</h4>
            <p class="text-gray-300 leading-relaxed">
              Nhận thông tin về các khóa học mới và ưu đãi đặc biệt qua email
            </p>
            
            <div class="relative">
              <input 
                v-model="email" 
                type="email" 
                placeholder="Nhập email của bạn"
                class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm text-white placeholder-gray-400 rounded-xl border border-white/20 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent transition-all duration-300"
              />
              <button 
                @click="subscribe"
                class="absolute right-2 top-2 bottom-2 px-4 bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-medium rounded-lg hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105 group"
              >
                <i class="fas fa-paper-plane group-hover:translate-x-1 transition-transform"></i>
              </button>
            </div>

            <!-- Contact Info -->
            <div class="space-y-3 pt-4">
              <div class="flex items-center space-x-3 text-gray-300">
                <i class="fas fa-map-marker-alt text-teal-400"></i>
                <span class="text-sm">123 Nguyễn Văn Cừ, Q.5, TP.HCM</span>
              </div>
              <div class="flex items-center space-x-3 text-gray-300">
                <i class="fas fa-phone text-teal-400"></i>
                <span class="text-sm">+84 123 456 789</span>
              </div>
              <div class="flex items-center space-x-3 text-gray-300">
                <i class="fas fa-envelope text-teal-400"></i>
                <span class="text-sm">info@learnsphere.vn</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 py-12 border-t border-white/10">
          <div class="text-center group">
            <div class="text-3xl font-bold text-teal-400 mb-2 group-hover:scale-110 transition-transform duration-300">50K+</div>
            <div class="text-gray-300 text-sm">Học viên</div>
          </div>
          <div class="text-center group">
            <div class="text-3xl font-bold text-cyan-400 mb-2 group-hover:scale-110 transition-transform duration-300">1000+</div>
            <div class="text-gray-300 text-sm">Khóa học</div>
          </div>
          <div class="text-center group">
            <div class="text-3xl font-bold text-purple-400 mb-2 group-hover:scale-110 transition-transform duration-300">200+</div>
            <div class="text-gray-300 text-sm">Giảng viên</div>
          </div>
          <div class="text-center group">
            <div class="text-3xl font-bold text-pink-400 mb-2 group-hover:scale-110 transition-transform duration-300">98%</div>
            <div class="text-gray-300 text-sm">Hài lòng</div>
          </div>
        </div>

        <!-- Bottom Section -->
        <div class="pt-8 border-t border-white/10">
          <div class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0">
            <!-- Copyright -->
            <p class="text-gray-400 text-sm">
              © 2024 LearnSphere. Tất cả quyền được bảo lưu.
            </p>

            <!-- Legal Links -->
            <div class="flex flex-wrap justify-center items-center space-x-6 text-sm">
              <a href="#" class="text-gray-400 hover:text-teal-400 transition-colors duration-300">
                Chính sách bảo mật
              </a>
              <span class="text-gray-600">•</span>
              <a href="#" class="text-gray-400 hover:text-teal-400 transition-colors duration-300">
                Điều khoản sử dụng
              </a>
              <span class="text-gray-600">•</span>
              <a href="#" class="text-gray-400 hover:text-teal-400 transition-colors duration-300">
                Cơ hội nghề nghiệp
              </a>
              <span class="text-gray-600">•</span>
              <a href="#" class="text-gray-400 hover:text-teal-400 transition-colors duration-300">
                Hỗ trợ
              </a>
            </div>

            <!-- Certifications -->
            <div class="flex items-center space-x-4">
              <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full">
                <i class="fas fa-shield-alt text-green-400"></i>
                <span class="text-xs text-gray-300">Chứng nhận ISO</span>
              </div>
              <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm px-3 py-1 rounded-full">
                <i class="fas fa-award text-yellow-400"></i>
                <span class="text-xs text-gray-300">Top Rated</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Floating elements -->
      <div class="absolute top-20 right-32 opacity-20">
        <div class="w-3 h-3 bg-teal-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
      </div>
      <div class="absolute bottom-32 left-20 opacity-20">
        <div class="w-2 h-2 bg-cyan-400 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
      </div>
      <div class="absolute top-1/3 right-1/4 opacity-20">
        <div class="w-4 h-4 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 2s;"></div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const email = ref('')

const emit = defineEmits<{
  (e: 'subscribe', email: string): void
}>()

function subscribe() {
  if (email.value.trim()) {
    // Basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(email.value)) {
      // Show error notification here
      console.error('Invalid email format')
      return
    }
    
    emit('subscribe', email.value)
    email.value = ''
    
    // Show success notification
    console.log('Successfully subscribed!')
  }
}
</script>
