<template>
  <section class="py-24 bg-gradient-to-br from-gray-50 to-white">
    <div class="container mx-auto px-6 flex flex-col lg:flex-row items-center justify-between gap-16">
      <!-- Left Content -->
      <div
        v-motion
        class="lg:w-1/2"
        :initial="{ opacity: 0, x: -60 }"
        :enter="{ opacity: 1, x: 0, transition: { duration: 0.8 } }"
      >
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] leading-tight mb-6">
          Everything you can do in a real classroom,
          <span class="text-[#49BBBD]">you can do with LearnSphere</span>
        </h2>

        <p class="text-lg text-[#696984] leading-relaxed mb-8">
          LearnSphere is an all-in-one online learning platform that brings together course purchasing, interactive
          learning, assessments, and community engagement in one unified system. Learners can enroll in diverse courses,
          join video lessons, take quizzes, earn certificates, and chat directly with instructors for instant support.
          <br /><br />
          More than just a learning tool, LearnSphere creates an open learning ecosystem — where users can share
          knowledge, write blogs, comment, and discuss topics within each course community.
        </p>

        <div class="flex items-center space-x-4">
          <a
            href="#"
            class="inline-flex items-center text-[#49BBBD] font-semibold hover:text-[#2F327D] transition-colors group"
          >
            <span>Learn more</span>
            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
          </a>
        </div>
      </div>

      <!-- Right Video Embed -->
      <div
        v-motion
        class="lg:w-1/2 relative"
        :initial="{ opacity: 0, x: 60 }"
        :enter="{ opacity: 1, x: 0, transition: { delay: 0.2, duration: 0.8 } }"
      >
        <div class="relative rounded-3xl overflow-hidden shadow-2xl group">
          <iframe
            width="100%"
            height="400"
            class="rounded-3xl"
            src="https://www.youtube.com/embed/00_E0jFyTms?autoplay=1&mute=1&loop=1&playlist=00_E0jFyTms&controls=0&modestbranding=1&rel=0"
            title="LearnSphere Demo Video"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen
          ></iframe>

          <!-- Decorative elements -->
          <div class="absolute top-4 right-4 w-12 h-12 border-2 border-white/30 rounded-full"></div>
          <div class="absolute bottom-4 left-4 w-8 h-8 bg-white/20 rounded-full"></div>
        </div>

        <!-- Floating stats cards -->
        <div
          v-motion
          :initial="{ opacity: 0, y: 40 }"
          :enter="{ opacity: 1, y: 0, transition: { delay: 0.6, duration: 0.8 } }"
          class="absolute -bottom-6 -left-6 bg-white rounded-2xl p-4 shadow-xl border border-gray-100"
        >
          <div class="flex items-center space-x-3">
            <div
              class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center"
            >
              <i class="fas fa-users text-white text-lg"></i>
            </div>
            <div>
              <p class="font-bold text-gray-800">10K+</p>
              <p class="text-sm text-gray-600">Active Students</p>
            </div>
          </div>
        </div>

        <div
          v-motion
          :initial="{ opacity: 0, y: -40 }"
          :enter="{ opacity: 1, y: 0, transition: { delay: 0.8, duration: 0.8 } }"
          class="absolute -top-6 -right-6 bg-white rounded-2xl p-4 shadow-xl border border-gray-100"
        >
          <div class="flex items-center space-x-3">
            <div
              class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center"
            >
              <i class="fas fa-clock text-white text-lg"></i>
            </div>
            <div>
              <p class="font-bold text-gray-800">24/7</p>
              <p class="text-sm text-gray-600">Support</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
