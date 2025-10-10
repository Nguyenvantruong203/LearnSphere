<template>
  <section class="py-24 bg-white">
    <div class="container mx-auto px-6 text-center">
      <div v-motion
        class="mb-20"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }">
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          Our Key Features
        </h2>
        <p class="text-xl text-[#696984] max-w-4xl mx-auto leading-relaxed">
          These powerful tools make teaching, learning, and managing courses easier, faster, and more engaging.
        </p>
      </div>

      <div class="space-y-32">
        <!-- Feature 1 - Modern Course Dashboard -->
        <div v-motion
          :initial="{ opacity: 0, x: 60 }"
          :enter="{ opacity: 1, x: 0, transition: { duration: 0.8 } }"
          class="flex flex-col lg:flex-row-reverse items-center gap-16">
          
          <div class="lg:w-1/2 text-left">
            <div class="space-y-6">
              <h3 class="text-3xl lg:text-4xl font-bold text-[#2F327D] leading-tight">
                A <span class="text-[#F48C06]">Modern Interface</span> built for learning
              </h3>
              <p class="text-lg text-[#696984] leading-relaxed">
                LearnSphere’s intuitive dashboard lets educators and learners navigate effortlessly between lessons, 
                quizzes, progress tracking, and discussions — all in a clean, responsive interface.
              </p>
              
              <!-- Feature highlights -->
              <div class="space-y-4 mt-8">
                <FeatureItem color="from-orange-400 to-orange-500" text="Comprehensive course dashboard" />
                <FeatureItem color="from-orange-400 to-orange-500" text="Real-time learning progress tracking" />
                <FeatureItem color="from-orange-400 to-orange-500" text="Fully responsive on all devices" />
              </div>
            </div>
          </div>
          
          <div class="lg:w-1/2">
            <div class="relative">
              <div class="bg-gradient-to-br from-orange-100 to-orange-200 rounded-3xl p-8 shadow-xl">
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                  <div class="space-y-4">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-orange-500 rounded-full flex items-center justify-center">
                          <i class="fas fa-chart-bar text-white"></i>
                        </div>
                        <span class="font-semibold text-gray-800">Dashboard</span>
                      </div>
                      <span class="text-sm text-gray-500">Today</span>
                    </div>
                    <div class="h-32 bg-gradient-to-r from-orange-200 to-yellow-200 rounded-xl flex items-center justify-center">
                      <i class="fas fa-laptop-code text-orange-600 text-4xl"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-star text-white text-xl"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Feature 2 - Learning Tools -->
        <div v-motion
          :initial="{ opacity: 0, x: -60 }"
          :enter="{ opacity: 1, x: 0, transition: { duration: 0.8 } }"
          class="flex flex-col lg:flex-row items-center gap-16">
          
          <div class="lg:w-1/2 text-left">
            <div class="space-y-6">
              <h3 class="text-3xl lg:text-4xl font-bold text-[#2F327D] leading-tight">
                <span class="text-[#2F327D]">Smart Tools</span> for instructors and learners
              </h3>
              <p class="text-lg text-[#696984] leading-relaxed">
                Build engaging learning experiences with interactive videos, document sharing, and automated notifications.
                Teachers can assign tasks, post updates, and manage lessons seamlessly.
              </p>
              
              <div class="space-y-4 mt-8">
                <FeatureItem color="from-blue-500 to-blue-600" text="Interactive content editor" />
                <FeatureItem color="from-blue-500 to-blue-600" text="Seamless file & resource sharing" />
                <FeatureItem color="from-blue-500 to-blue-600" text="Smart real-time notifications" />
              </div>
            </div>
          </div>
          
          <div class="lg:w-1/2">
            <div class="relative">
              <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl p-8 shadow-xl">
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                  <div class="space-y-4">
                    <div class="flex items-center justify-between">
                      <span class="font-semibold text-gray-800">Instructor Tools</span>
                      <i class="fas fa-tools text-blue-600"></i>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                      <div class="h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                      </div>
                      <div class="h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-video text-blue-600 text-xl"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="absolute -top-4 -left-4 w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-cog text-white text-xl"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Feature 3 - Assessments -->
        <div v-motion
          :initial="{ opacity: 0, x: 60 }"
          :enter="{ opacity: 1, x: 0, transition: { duration: 0.8 } }"
          class="flex flex-col lg:flex-row-reverse items-center gap-16">
          
          <div class="lg:w-1/2 text-left">
            <div class="space-y-6">
              <h3 class="text-3xl lg:text-4xl font-bold text-[#2F327D] leading-tight">
                <span class="text-[#23BDEE]">Quizzes, Tests,</span> and Performance Analytics
              </h3>
              <p class="text-lg text-[#696984] leading-relaxed">
                Create interactive quizzes, exams, and real-time assessments with automatic grading. 
                Get detailed insights into learner performance with smart analytics.
              </p>
              
              <div class="space-y-4 mt-8">
                <FeatureItem color="from-cyan-500 to-blue-500" text="Dynamic quiz and exam builder" />
                <FeatureItem color="from-cyan-500 to-blue-500" text="Auto-grading with instant feedback" />
                <FeatureItem color="from-cyan-500 to-blue-500" text="Detailed performance analytics" />
              </div>
            </div>
          </div>
          
          <div class="lg:w-1/2">
            <div class="relative">
              <div class="bg-gradient-to-br from-cyan-100 to-blue-200 rounded-3xl p-8 shadow-xl">
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                  <div class="space-y-4">
                    <div class="flex items-center justify-between">
                      <span class="font-semibold text-gray-800">Quiz & Tests</span>
                      <div class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-question text-white text-sm"></i>
                      </div>
                    </div>
                    <div class="space-y-3">
                      <div class="h-4 bg-cyan-100 rounded-full"></div>
                      <div class="h-4 bg-cyan-100 rounded-full w-3/4"></div>
                      <div class="h-4 bg-cyan-100 rounded-full w-1/2"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-chart-line text-white text-xl"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { defineComponent } from 'vue'

// Small reusable feature item component (optional for cleaner code)
const FeatureItem = defineComponent({
  props: {
    color: String,
    text: String
  },
  template: `
    <div class="flex items-center space-x-3">
      <div :class="'w-6 h-6 bg-gradient-to-r ' + color + ' rounded-full flex items-center justify-center'">
        <i class="fas fa-check text-white text-xs"></i>
      </div>
      <span class="text-[#696984]">{{ text }}</span>
    </div>
  `
})
</script>
