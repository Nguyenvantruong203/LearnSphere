<template>
  <section class="py-16 px-8 relative bg-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto relative z-10">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <!-- Left Content -->
        <div class="space-y-8">
          <div class="flex items-center space-x-4 mb-8">
            <div
              class="w-16 h-16 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg">
              <i class="fas fa-play text-white text-2xl"></i>
            </div>
            <div class="w-12 h-12 bg-white/80 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-md">
              <i class="fas fa-graduation-cap text-teal-600 text-xl"></i>
            </div>
          </div>

          <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] leading-tight">
            Every learning experience you need,
            <span class="text-transparent bg-gradient-to-r from-teal-500 to-cyan-500 bg-clip-text">LearnSphere</span>
            brings it all together.
          </h2>

          <p class="text-xl text-[#696984] leading-relaxed">
            LearnSphere is a comprehensive online learning platform where students can explore courses across various
            fields, watch video lectures, take quizzes, earn certificates, and interact directly with instructors.
            Instructors can easily create courses, manage content, track student progress, and build an active learning
            community.
          </p>

          <div class="flex flex-wrap gap-4">
            <div class="flex items-center space-x-3 bg-white/80 backdrop-blur-sm rounded-2xl px-4 py-3 shadow-md">
              <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-green-600"></i>
              </div>
              <span class="text-[#2F327D] font-semibold">Flexible Learning</span>
            </div>
            <div class="flex items-center space-x-3 bg-white/80 backdrop-blur-sm rounded-2xl px-4 py-3 shadow-md">
              <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-blue-600"></i>
              </div>
              <span class="text-[#2F327D] font-semibold">24/7 Support</span>
            </div>
            <div class="flex items-center space-x-3 bg-white/80 backdrop-blur-sm rounded-2xl px-4 py-3 shadow-md">
              <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-certificate text-purple-600"></i>
              </div>
              <span class="text-[#2F327D] font-semibold">Accredited Certificates</span>
            </div>
          </div>
        </div>

        <!-- Right Content -->
        <div class="relative">
          <div class="relative z-10">
            <!-- Main feature image -->
            <div class="bg-white rounded-3xl p-8 shadow-2xl border border-gray-100 mb-6">
              <img
                src="https://static.codia.ai/image/2025-10-02/VUBKJjj07n.png"
                alt="Learning Platform"
                class="w-full h-64 object-cover rounded-2xl"
              />

              <!-- Feature badges -->
              <div class="grid grid-cols-2 gap-4 mt-6">
                <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-2xl p-4 text-center">
                  <div class="text-2xl font-bold text-teal-600 mb-1">10K+</div>
                  <div class="text-sm text-[#696984]">Students</div>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-4 text-center">
                  <div class="text-2xl font-bold text-purple-600 mb-1">500+</div>
                  <div class="text-sm text-[#696984]">Courses</div>
                </div>
              </div>
            </div>

            <!-- Floating elements -->
            <div
              class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-2xl shadow-lg flex items-center justify-center transform rotate-12">
              <i class="fas fa-star text-white text-2xl"></i>
            </div>

            <div
              class="absolute -bottom-4 -left-4 w-20 h-20 bg-gradient-to-r from-green-400 to-teal-400 rounded-2xl shadow-lg flex items-center justify-center transform -rotate-12">
              <i class="fas fa-rocket text-white text-xl"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>