<template>
  <section class="relative overflow-hidden bg-client-ellipse pt-10">
    <!-- Background gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-black/10 to-transparent"></div>

    <div class="container mx-auto flex flex-col lg:flex-row items-center relative z-10">
      <!-- Left Content -->
      <div
        v-motion
        class="lg:w-1/2 text-white space-y-6 lg:mb-0"
        :initial="{ opacity: 0, x: -80 }"
        :enter="{ opacity: 1, x: 0, transition: { duration: 0.8, ease: 'easeOut' } }"
      >
        <!-- Badge -->
        <div
          v-motion
          class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-sm font-medium text-white/90 border border-white/20"
          :initial="{ opacity: 0, y: -20 }"
          :enter="{ opacity: 1, y: 0, transition: { delay: 0.2, duration: 0.6 } }"
        >
          🎓 Leading Online Learning Platform
        </div>

        <!-- Heading -->
        <h1 class="text-5xl lg:text-7xl font-bold leading-tight tracking-tight">
          <span class="text-orange-400 drop-shadow-lg">Online</span> Learning<br />
          <span
            class="bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent"
          >
            has never been easier
          </span>
        </h1>

        <!-- Description -->
        <p
          class="text-xl lg:text-2xl text-white/90 max-w-2xl leading-relaxed font-light"
        >
          LearnSphere is an engaging learning platform that helps you study in a
          more interactive and effective way than ever before.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 pt-4">
          <router-link to="/courses">
            <a-button
              type="primary"
              shape="round"
              size="large"
              class="!h-14 !px-8 !text-lg font-semibold shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 bg-gradient-to-r from-orange-400 to-orange-500 border-0"
            >
              Join for Free
            </a-button>
          </router-link>
        </div>

        <!-- Stats -->
        <div
          v-motion
          class="flex items-center space-x-8 pt-8"
          :initial="{ opacity: 0, y: 20 }"
          :enter="{ opacity: 1, y: 0, transition: { delay: 0.4, duration: 0.6 } }"
        >
          <div class="text-center">
            <div class="text-3xl font-bold text-orange-400">50K+</div>
            <div class="text-sm text-white/70">Students</div>
          </div>
          <div class="w-px h-12 bg-white/20"></div>
          <div class="text-center">
            <div class="text-3xl font-bold text-orange-400">1,200+</div>
            <div class="text-sm text-white/70">Courses</div>
          </div>
          <div class="w-px h-12 bg-white/20"></div>
          <div class="text-center">
            <div class="text-3xl font-bold text-orange-400">95%</div>
            <div class="text-sm text-white/70">Satisfaction</div>
          </div>
        </div>
      </div>

      <!-- Right Image Section -->
      <div
        v-motion
        class="relative lg:w-1/2 flex justify-center items-center"
        :initial="{ opacity: 0, scale: 0.8 }"
        :enter="{ opacity: 1, scale: 1, transition: { duration: 1.2, ease: 'easeOut' } }"
      >
        <!-- Main Image -->
        <div class="relative">
          <div
            class="absolute inset-0 bg-gradient-to-t from-teal-600/20 to-transparent rounded-3xl"
          ></div>
          <img
            src="@/assets/images/teacher.png"
            alt="Instructor"
            class="max-h-[600px] lg:max-h-[700px] relative z-10 drop-shadow-2xl"
          />
        </div>

        <!-- Students Card -->
        <div
          v-motion
          class="absolute top-8 left-4 lg:left-12 bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl px-5 py-4 flex items-center space-x-4 border border-white/20 hover:shadow-3xl transition-all duration-300"
          :initial="{ opacity: 0, y: -40, rotate: -5 }"
          :enter="{ opacity: 1, y: 0, rotate: 0, transition: { delay: 0.6, duration: 0.8, type: 'spring' } }"
        >
          <div
            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg"
          >
            📊
          </div>
          <div>
            <p class="text-lg font-bold text-gray-800">250K+</p>
            <p class="text-sm text-gray-600 font-medium">Students Supported</p>
          </div>
        </div>

        <!-- Achievement Card -->
        <div
          v-motion
          class="absolute top-16 right-2 lg:right-8 bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl px-5 py-4 flex items-center space-x-3 border border-white/20 hover:shadow-3xl transition-all duration-300 max-w-xs"
          :initial="{ opacity: 0, x: 60, rotate: 5 }"
          :enter="{ opacity: 1, x: 0, rotate: 0, transition: { delay: 0.9, duration: 0.8, type: 'spring' } }"
        >
          <div
            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg"
          >
            🎉
          </div>
          <div>
            <p class="text-base font-bold text-gray-800">Congratulations!</p>
            <p class="text-sm text-gray-600 font-medium">Registration Completed</p>
          </div>
        </div>

        <!-- Live Class Card -->
        <div
          v-motion
          class="absolute bottom-8 left-0 lg:left-4 bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl px-5 py-4 flex items-center space-x-4 border border-white/20 hover:shadow-3xl transition-all duration-300 max-w-sm"
          :initial="{ opacity: 0, y: 40, rotate: -3 }"
          :enter="{ opacity: 1, y: 0, rotate: 0, transition: { delay: 1.2, duration: 0.8, type: 'spring' } }"
        >
          <img
            src="https://i.pravatar.cc/48"
            class="w-12 h-12 rounded-xl shadow-md"
            alt="Instructor Avatar"
          />
          <div class="flex-1">
            <p class="text-base font-bold text-gray-800">UX Design Class</p>
            <p class="text-sm text-gray-600 font-medium">Today at 12:00</p>
            <a-button
              type="primary"
              size="small"
              class="mt-2 !h-8 !px-4 !text-xs font-semibold shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200"
            >
              Join Now
            </a-button>
          </div>
        </div>

        <!-- Rating Card -->
        <div
          v-motion
          class="absolute bottom-32 right-4 lg:right-12 bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl px-5 py-4 flex items-center space-x-3 border border-white/20 hover:shadow-3xl transition-all duration-300"
          :initial="{ opacity: 0, scale: 0.8, rotate: 8 }"
          :enter="{ opacity: 1, scale: 1, rotate: 0, transition: { delay: 1.5, duration: 0.8, type: 'spring' } }"
        >
          <div
            class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center text-white text-xl shadow-lg"
          >
            ⭐
          </div>
          <div>
            <p class="text-lg font-bold text-gray-800">4.9/5</p>
            <p class="text-sm text-gray-600 font-medium">Excellent Rating</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
