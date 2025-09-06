<template>
  <section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Marketing Articles</h2>
        <a href="#" class="text-cyan-500 hover:text-cyan-600 font-medium">See all</a>
      </div>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <article 
          v-for="article in articles" 
          :key="article.id"
          class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300"
        >
          <div class="relative">
            <img 
              :src="article.image" 
              :alt="article.title" 
              class="w-full h-32 object-cover"
            >
            <div class="absolute top-2 left-2">
              <span class="bg-white text-xs px-2 py-1 rounded-full text-gray-600">{{ article.category }}</span>
            </div>
            <div class="absolute top-2 right-2">
              <span class="bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded-full">{{ article.duration }}</span>
            </div>
          </div>
          
          <div class="p-4">
            <h3 class="font-bold text-gray-900 mb-2 text-sm">{{ article.title }}</h3>
            <p class="text-gray-600 text-xs mb-3">{{ article.description }}</p>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <img 
                  :src="article.author.avatar" 
                  :alt="article.author.name" 
                  class="w-6 h-6 rounded-full mr-2"
                >
                <span class="text-xs text-gray-600">{{ article.author.name }}</span>
              </div>
              <div class="flex items-center">
                <span class="text-xs text-gray-400 mr-1">{{ article.rating }}</span>
                <span class="text-cyan-500 font-bold text-sm">${{ article.price }}</span>
              </div>
            </div>
          </div>
        </article>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'MarketingArticles',
  data() {
    return {
      articles: [
        {
          id: 1,
          title: 'AWS Certified solutions Architect',
          category: 'Design',
          duration: '3 Month',
          image: 'https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor',
          author: {
            name: 'Lina',
            avatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'
          },
          rating: '4.9',
          price: '80'
        },
        {
          id: 2,
          title: 'AWS Certified solutions Architect',
          category: 'Design',
          duration: '3 Month',
          image: 'https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor',
          author: {
            name: 'Lina',
            avatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'
          },
          rating: '4.9',
          price: '80'
        },
        {
          id: 3,
          title: 'AWS Certified solutions Architect',
          category: 'Design',
          duration: '3 Month',
          image: 'https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor',
          author: {
            name: 'Lina',
            avatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'
          },
          rating: '4.9',
          price: '80'
        },
        {
          id: 4,
          title: 'AWS Certified solutions Architect',
          category: 'Design',
          duration: '3 Month',
          image: 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor',
          author: {
            name: 'Lina',
            avatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'
          },
          rating: '4.9',
          price: '80'
        }
      ]
    }
  }
}
</script>
