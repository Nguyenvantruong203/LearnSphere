<template>
  <section class="bg-gradient-to-r from-blue-50 to-indigo-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <!-- Left Content -->
        <div>
          <div class="text-sm text-blue-600 font-medium mb-4">
            By Themadbrains in <span class="text-cyan-500">Inspiration</span>
          </div>
          
          <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
            Why Swift UI Should Be on the Radar of Every Mobile Developer
          </h1>
          
          <p class="text-lg text-gray-600 mb-8 leading-relaxed">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
          </p>
          
          <button class="bg-cyan-500 hover:bg-cyan-600 text-white px-8 py-3 rounded-lg font-medium transition-colors duration-200">
            Start learning now
          </button>
        </div>

        <!-- Right Image -->
        <div class="relative">
          <div class="rounded-2xl overflow-hidden shadow-2xl">
            <img 
              src="https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
              alt="Video conference on laptop" 
              class="w-full h-auto"
            >
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'HeroSection'
}
</script>
