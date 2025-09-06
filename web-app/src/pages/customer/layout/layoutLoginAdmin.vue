<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-teal-900 to-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-8 shadow-2xl">
        <div class="text-center mb-8">
          <div class="w-16 h-16 bg-gradient-to-r from-teal-400 to-cyan-400 rounded-full mx-auto mb-4 flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-white mb-2">{{ title || 'Welcome Back' }}</h2>
          <p class="text-white/70">{{ subtitle || 'Sign in to your account' }}</p>
        </div>

        <slot />

        <div class="text-center mt-8 pt-6 border-t border-white/20" v-if="showFooter">
          <p class="text-white/70">
            {{ footerText }}
            <a href="#" class="text-teal-300 hover:text-teal-200 font-medium transition-colors ml-1">
              {{ footerLinkText }}
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  title?: string
  subtitle?: string
  showFooter?: boolean
  footerText?: string
  footerLinkText?: string
}

withDefaults(defineProps<Props>(), {
  title: 'Welcome Back',
  subtitle: 'Sign in to your account',
  showFooter: true,
  footerText: "Don't have an account?",
  footerLinkText: ''
})
</script>

<style scoped>
</style>