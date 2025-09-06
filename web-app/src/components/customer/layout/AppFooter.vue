<template>
  <footer class="bg-purple text-white p-8">
    <div class="container mx-auto px-6">
      <div class="flex flex-col lg:flex-row justify-center items-center max-w-4xl mx-auto">

        <div class="flex flex-col items-center mb-4 lg:mb-0 lg:mr-16">
          <img src="@/assets/images/logoFooter.png" alt="LearnSphere Logo" class="h-24 w-30 mb-4" />

          <h4 class="text-xl font-medium text-[#B2B3CF] mb-4">Subscribe to get our Newsletter</h4>
          <div class="flex max-w-md">
            <input v-model="email" type="email" placeholder="Your Email"
              class="flex-1 px-5 py-3 bg-[#4a4e6b] text-white placeholder-gray-400 rounded-l-full border-0 focus:outline-none focus:ring-2 focus:ring-teal-400" />
            <button @click="subscribe"
              class="px-6 py-3 bg-teal-400 text-white font-medium rounded-r-full hover:bg-teal-500 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-teal-400">
              Subscribe
            </button>
          </div>
        </div>
      </div>

      <div class="mt-8 pt-4 border-t border-gray-600">
        <div class="flex justify-center items-center space-x-3 mb-4 text-sm text-gray-400">
          <a href="#" class="hover:text-white transition-colors">Careers</a>
          <span class="mx-3 text-gray-600">|</span>
          <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
          <span class="mx-3 text-gray-600">|</span>
          <a href="#" class="hover:text-white transition-colors">Terms & Conditions</a>
        </div>

        <p class="text-center text-sm text-gray-500">
          © 2025 Class Technologies Inc.
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const email = ref('');

const emit = defineEmits<{
  (e: 'subscribe', email: string): void;
}>();

function subscribe() {
  if (email.value.trim()) {
    emit('subscribe', email.value);
    email.value = ''; 
  }
}
</script>