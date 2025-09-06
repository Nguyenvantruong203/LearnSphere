<template>
  <section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Related Blog</h2>
        <a href="#" class="text-cyan-500 hover:text-cyan-600 font-medium">See all</a>
      </div>
      
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <article 
          v-for="post in blogPosts" 
          :key="post.id"
          class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300"
        >
          <img 
            :src="post.image" 
            :alt="post.title" 
            class="w-full h-48 object-cover"
          >
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ post.title }}</h3>
            
            <div class="flex items-center mb-4">
              <img 
                :src="post.author.avatar" 
                :alt="post.author.name" 
                class="w-8 h-8 rounded-full mr-3"
              >
              <span class="text-sm text-gray-600">{{ post.author.name }}</span>
            </div>
            
            <p class="text-gray-600 text-sm mb-4">{{ post.excerpt }}</p>
            
            <div class="flex justify-between items-center">
              <a href="#" class="text-cyan-500 hover:text-cyan-600 font-medium text-sm">Read more</a>
              <div class="flex items-center text-gray-400 text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                {{ post.views }}
              </div>
            </div>
          </div>
        </article>
      </div>
      
      <!-- Pagination -->
      <div class="flex justify-center mt-8">
        <div class="flex space-x-2">
          <button class="w-8 h-8 rounded-full bg-cyan-500 text-white flex items-center justify-center">1</button>
          <button class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center hover:bg-gray-300">2</button>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'RelatedBlog',
  data() {
    return {
      blogPosts: [
        {
          id: 1,
          title: 'Class adds $30 million to its balance sheet for a Zoom-friendly edtech solution',
          image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
          author: {
            name: 'Lina',
            avatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'
          },
          excerpt: 'Class launched less than a year ago by Blackboard co-founder Michael Chasen, integrates exclusively.',
          views: '251,232'
        },
        {
          id: 2,
          title: 'Class adds $30 million to its balance sheet for a Zoom-friendly edtech solution',
          image: 'https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
          author: {
            name: 'Lina',
            avatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'
          },
          excerpt: 'Class launched less than a year ago by Blackboard co-founder Michael Chasen, integrates exclusively.',
          views: '251,232'
        }
      ]
    }
  }
}
</script>
