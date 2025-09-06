<template>
  <section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold text-gray-900 mb-8">Reading blog list</h2>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div 
          v-for="item in blogItems" 
          :key="item.id"
          class="relative rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300"
        >
          <img 
            :src="item.image" 
            :alt="item.title" 
            class="w-full h-48 object-cover"
          >
          <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
            <div class="p-4">
              <span class="bg-white text-gray-900 px-3 py-1 rounded-full text-sm font-medium">
                {{ item.title }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'BlogList',
  data() {
    return {
      blogItems: [
        {
          id: 1,
          title: 'UX/UI',
          image: 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'
        },
        {
          id: 2,
          title: 'React',
          image: 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'
        },
        {
          id: 3,
          title: 'PHP',
          image: 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'
        },
        {
          id: 4,
          title: 'JavaScript',
          image: 'https://images.unsplash.com/photo-1627398242454-45a1465c2479?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'
        }
      ]
    }
  }
}
</script>
