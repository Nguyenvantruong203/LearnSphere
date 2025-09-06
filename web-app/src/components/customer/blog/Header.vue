<template>
  <header class="bg-white shadow-sm">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <div class="flex-shrink-0 flex items-center">
            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-sm">L</span>
            </div>
            <span class="ml-2 text-xl font-bold text-gray-900">LearnSphere</span>
          </div>
        </div>

        <!-- Navigation Links -->
        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-8">
            <a href="#" class="text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium">Home</a>
            <a href="#" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium">Courses</a>
            <a href="#" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium">Blog</a>
            <a href="#" class="text-gray-500 hover:text-blue-600 px-3 py-2 text-sm font-medium">About Us</a>
          </div>
        </div>

        <!-- User Profile -->
        <div class="flex items-center">
          <div class="flex items-center space-x-2">
            <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="User">
            <span class="text-sm text-gray-700">Lina</span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </div>
        </div>
      </div>
    </nav>
  </header>
</template>

<script>
export default {
  name: 'Header'
}
</script>
