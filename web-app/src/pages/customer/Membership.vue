<template>
  <LayoutHomepage>
    <!-- Pricing Header -->
    <div class="text-center py-16">
      <h1 class="text-6xl font-extrabold text-teal-500 mb-8">Affordable pricing</h1>
    </div>

    <!-- Pricing Plans -->
    <div class="max-w-7xl mx-auto px-8 pb-16">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Free Plan -->
        <div class="bg-white rounded-lg border border-gray-200 p-8 shadow-sm">
          <div class="mb-6">
            <h3 class="text-lg font-bold text-teal-500 mb-2">Like a pussy</h3>
            <div class="text-4xl font-bold text-gray-800">
              <span class="text-5xl">Free</span>
              <span class="text-lg font-normal text-gray-600"> / forever</span>
            </div>
          </div>
          
          <div class="space-y-4 mb-8">
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center opacity-50">
                <i class="fas fa-check text-white text-sm"></i>
              </div>
              <span class="text-gray-800">Components-driven system</span>
            </div>
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center opacity-50">
                <i class="fas fa-check text-white text-sm"></i>
              </div>
              <span class="text-gray-800">Sales-boosting landing pages</span>
            </div>
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center opacity-50">
                <i class="fas fa-check text-white text-sm"></i>
              </div>
              <span class="text-gray-800">Awesome Feather icons pack</span>
            </div>
          </div>
          
          <a-button class="w-full h-16 border-2 border-gray-300 text-teal-500 font-bold text-lg rounded-2xl hover:bg-gray-50">
            Try for free
          </a-button>
        </div>

        <!-- Individual Plan -->
        <div class="bg-white rounded-2xl p-8 shadow-xl border-2 border-gray-100 relative">
          <div class="absolute top-4 right-4">
            <span class="bg-white border border-purple-500 text-gray-800 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
              best!
            </span>
          </div>
          
          <div class="mb-6">
            <h3 class="text-lg font-bold text-teal-500 mb-2">👤 Individual</h3>
            <div class="text-4xl font-bold text-gray-800">
              <span class="text-5xl">$24</span>
              <span class="text-lg font-normal text-gray-600"> / month</span>
            </div>
          </div>
          
          <div class="space-y-4 mb-8">
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-white text-sm"></i>
              </div>
              <span class="text-gray-800">Components-driven system</span>
            </div>
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-white text-sm"></i>
              </div>
              <span class="text-gray-800">Sales-boosting landing pages</span>
            </div>
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-white text-sm"></i>
              </div>
              <span class="text-gray-800">Awesome Feather icons pack</span>
            </div>
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-white text-sm"></i>
              </div>
              <span class="text-gray-800">Themed into 3 different styles</span>
            </div>
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-white text-sm"></i>
              </div>
              <span class="text-gray-800">Will help to learn Figma</span>
            </div>
          </div>
          
          <a-button type="primary" class="w-full h-16 bg-teal-500 border-teal-500 text-white font-bold text-xl rounded-2xl hover:bg-teal-600">
            Regular license
          </a-button>
        </div>

        <!-- Corporate Plan -->
        <div class="bg-white rounded-lg border border-gray-200 p-8 shadow-sm">
          <div class="mb-6">
            <h3 class="text-lg font-bold text-teal-500 mb-2">👥 Corporate</h3>
            <div class="text-4xl font-bold text-gray-800">
              <span class="text-5xl">$12</span>
              <span class="text-lg font-normal text-gray-600"> / editor</span>
            </div>
          </div>
          
          <div class="space-y-4 mb-8">
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-green-300 rounded-full flex items-center justify-center opacity-50">
                <i class="fas fa-check text-white text-sm"></i>
              </div>
              <span class="text-gray-800">Components-driven system</span>
            </div>
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-green-300 rounded-full flex items-center justify-center opacity-50">
                <i class="fas fa-check text-white text-sm"></i>
              </div>
              <span class="text-gray-800">Sales-boosting landing pages</span>
            </div>
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-green-300 rounded-full flex items-center justify-center opacity-50">
                <i class="fas fa-check text-white text-sm"></i>
              </div>
              <span class="text-gray-800">Awesome Feather icons pack</span>
            </div>
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-green-300 rounded-full flex items-center justify-center opacity-50">
                <i class="fas fa-check text-white text-sm"></i>
              </div>
              <span class="text-gray-800">Themed into 3 different styles</span>
            </div>
          </div>
          
          <a-button class="w-full h-16 border-2 border-gray-300 text-teal-500 font-bold text-lg rounded-2xl hover:bg-gray-50">
            Extended license
          </a-button>
        </div>
      </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gray-800 rounded-3xl mx-8 mb-16 px-16 py-16 text-center">
      <h2 class="text-4xl font-semibold text-white mb-6">Online coaching lessons for remote learning.</h2>
      <p class="text-white text-xl mb-8 max-w-4xl mx-auto leading-relaxed">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempos Lorem ipsum dolor sitamet, consectetur adipiscing elit, sed do eiusmod tempor
      </p>
      <a-button type="primary" class="bg-teal-500 border-teal-500 text-white font-bold px-8 py-6 h-auto text-lg rounded-xl hover:bg-teal-600">
        Start learning now
      </a-button>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-8 mb-16">
      <h2 class="text-4xl font-semibold text-gray-800 mb-12">Online coaching lessons for remote learning</h2>
      
      <div class="space-y-8">
        <div class="flex items-center space-x-4 py-4 border-b border-gray-200">
          <div class="w-5 h-5 bg-green-300 rounded-full opacity-50"></div>
          <span class="text-lg text-gray-800">Lorem ipsum dolor sit amet</span>
          <div class="flex-1"></div>
          <i class="fas fa-chevron-down text-gray-500"></i>
        </div>
        
        <div class="flex items-start space-x-4 py-4 border-b border-gray-200">
          <div class="w-5 h-5 bg-green-300 rounded-full opacity-50 mt-2"></div>
          <div class="flex-1">
            <span class="text-lg text-gray-800 block mb-4">Consectetur adipiscing elit, sed do</span>
            <p class="text-gray-600 text-sm leading-relaxed">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempos Lorem ipsum dolor sitamet, consectetur adipiscing elit, sed do eiusmod temporLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempos Lorem ipsum dolor sitamet, consectetur adipiscing elit, sed do eiusmod temporLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempos Lorem ipsum dolor sitamet, consectetur adipiscing elit, sed do eiusmod temporLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempos Lorem ipsum dolor sitamet, consectetur adipiscing elit, sed do eiusmod tempor
            </p>
          </div>
          <i class="fas fa-chevron-down text-gray-500 mt-2"></i>
        </div>
        
        <div class="flex items-center space-x-4 py-4 border-b border-gray-200">
          <div class="w-5 h-5 bg-green-300 rounded-full opacity-50"></div>
          <span class="text-lg text-gray-800">Eiusmod tempos Lorem ipsum</span>
          <div class="flex-1"></div>
          <i class="fas fa-chevron-down text-gray-500"></i>
        </div>
        
        <div class="flex items-center space-x-4 py-4 border-b border-gray-200">
          <div class="w-5 h-5 bg-green-300 rounded-full opacity-50"></div>
          <span class="text-lg text-gray-800">Lorem ipsum dolor sit amet</span>
          <div class="flex-1"></div>
          <i class="fas fa-chevron-down text-gray-500"></i>
        </div>
        
        <div class="flex items-center space-x-4 py-4">
          <div class="w-5 h-5 bg-green-300 rounded-full opacity-50"></div>
          <span class="text-lg text-gray-800">Lorem ipsum dolor sit amet</span>
          <div class="flex-1"></div>
          <i class="fas fa-chevron-down text-gray-500"></i>
        </div>
      </div>
    </div>

    <!-- Testimonials Section -->
    <div class="bg-blue-50 py-16 mb-16">
      <div class="max-w-7xl mx-auto px-8">
        <h2 class="text-4xl font-semibold text-gray-800 mb-12">What our students have to say</h2>
        
        <div class="relative">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-8 shadow-sm">
              <div class="flex justify-center mb-6">
                <img src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/PcgOKFC9vK.png" alt="Student" class="w-24 h-24 rounded-lg object-cover" />
              </div>
              <h4 class="text-xl font-semibold text-gray-800 text-center mb-4">Bulkin Simons</h4>
              <p class="text-gray-600 text-center leading-relaxed">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmodadipiscing elit, sed do eiusmod
              </p>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-sm">
              <div class="flex justify-center mb-6">
                <img src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/16QD4AT7OP.png" alt="Student" class="w-24 h-24 rounded-lg object-cover" />
              </div>
              <h4 class="text-xl font-semibold text-gray-800 text-center mb-4">Bulkin Simons</h4>
              <p class="text-gray-600 text-center leading-relaxed">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmodadipiscing elit, sed do eiusmod
              </p>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-sm">
              <div class="flex justify-center mb-6">
                <img src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/aWdLjg0dNC.png" alt="Student" class="w-24 h-24 rounded-lg object-cover" />
              </div>
              <h4 class="text-xl font-semibold text-gray-800 text-center mb-4">Bulkin Simons</h4>
              <p class="text-gray-600 text-center leading-relaxed">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmodadipiscing elit, sed do eiusmod
              </p>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-sm">
              <div class="flex justify-center mb-6">
                <img src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/YAifZSBgCC.png" alt="Student" class="w-24 h-24 rounded-lg object-cover" />
              </div>
              <h4 class="text-xl font-semibold text-gray-800 text-center mb-4">Bulkin Simons</h4>
              <p class="text-gray-600 text-center leading-relaxed">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmodadipiscing elit, sed do eiusmod
              </p>
            </div>
          </div>
          
          <!-- Navigation arrows -->
          <button class="absolute left-0 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center text-white hover:bg-teal-600">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="absolute right-0 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center text-white hover:bg-teal-600">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- App Download Section -->
    <div class="bg-gray-800 rounded-3xl mx-8 mb-16 px-16 py-12 flex items-center justify-between">
      <div>
        <h2 class="text-4xl font-semibold text-white mb-4">APP is available for free</h2>
      </div>
      <div class="flex space-x-4">
        <a-button class="bg-blue-500 border-blue-500 text-white font-semibold px-6 py-6 h-auto rounded-xl hover:bg-blue-600 flex items-center space-x-3">
          <i class="fab fa-android text-2xl"></i>
          <span class="text-lg">Android APP</span>
        </a-button>
        <a-button class="bg-teal-500 border-teal-500 text-white font-semibold px-6 py-6 h-auto rounded-xl hover:bg-teal-600 flex items-center space-x-3">
          <i class="fab fa-apple text-2xl"></i>
          <span class="text-lg">IOS APP</span>
        </a-button>
      </div>
    </div>

    <!-- Career Cards Section -->
    <div class="max-w-7xl mx-auto px-8 mb-16">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Become a Teacher -->
        <div class="bg-white rounded-2xl p-8 shadow-lg">
          <div class="mb-6">
            <img src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/iboFa7UpPu.png" alt="Teacher" class="w-full h-64 object-cover rounded-2xl mb-6" />
            <h3 class="text-2xl font-medium text-gray-800 mb-4">Become a Teacher</h3>
            <p class="text-gray-600 leading-relaxed mb-6">
              Class, launched less than a year ago by Blackboard co-founder Michael Chasen, integrates exclusively...
            </p>
          </div>
          <a-button type="primary" class="bg-teal-500 border-teal-500 text-white font-medium px-8 py-4 h-auto rounded-xl hover:bg-teal-600">
            Apply a Teacher
          </a-button>
        </div>

        <!-- Become a Coursector -->
        <div class="bg-white rounded-2xl p-8 shadow-lg">
          <div class="mb-6">
            <img src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/b3qfiENiPw.png" alt="Coursector" class="w-full h-64 object-cover rounded-2xl mb-6" />
            <h3 class="text-2xl font-medium text-gray-800 mb-4">Become a Coursector</h3>
            <p class="text-gray-600 leading-relaxed mb-6">
              Class, launched less than a year ago by Blackboard co-founder Michael Chasen, integrates exclusively...
            </p>
          </div>
          <a-button type="primary" class="bg-teal-500 border-teal-500 text-white font-medium px-8 py-4 h-auto rounded-xl hover:bg-teal-600">
            Apply a Coursector
          </a-button>
        </div>
      </div>
    </div>
</LayoutHomepage>
</template>

<script setup lang="ts">
// Author: Truong
import LayoutHomepage from '@/pages/customer/layout/layoutHomepage.vue';
</script>

<style scoped>
/* Font Awesome icons - you may need to include Font Awesome CSS */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

/* Custom styles for better visual appearance */
.ant-btn-primary {
  background-color: #14b8a6;
  border-color: #14b8a6;
}

.ant-btn-primary:hover {
  background-color: #0d9488;
  border-color: #0d9488;
}
</style>
