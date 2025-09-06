<template>
  <section class="py-16 px-6 lg:px-20">
    <div class="max-w-7xl mx-auto">
      <h2 class="text-secondary font-poppins text-4xl font-semibold mb-12 text-center lg:text-left">
        Choice favourite course from top category
      </h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <CategoryCard 
          v-for="category in categories" 
          :key="category.id"
          :category="category"
        />
      </div>
    </div>
  </section>
</template>

<script>
import CategoryCard from './CategoryCard.vue'

export default {
  name: 'CategoriesSection',
  components: {
    CategoryCard
  },
  data() {
    return {
      categories: [
        {
          id: 1,
          title: 'Design',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod adipiscing elit, sed do eiusmod',
          icon: 'fas fa-palette',
          bgColor: 'bg-primary/30',
          iconColor: 'text-primary'
        },
        {
          id: 2,
          title: 'Development',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod adipiscing elit, sed do eiusmod',
          icon: 'fas fa-code',
          bgColor: 'bg-accent/30',
          iconColor: 'text-accent'
        },
        {
          id: 3,
          title: 'Development',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod adipiscing elit, sed do eiusmod',
          icon: 'fas fa-laptop-code',
          bgColor: 'bg-blue-300/30',
          iconColor: 'text-blue-400'
        },
        {
          id: 4,
          title: 'Business',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod adipiscing elit, sed do eiusmod',
          icon: 'fas fa-briefcase',
          bgColor: 'bg-teal-300/30',
          iconColor: 'text-teal-500'
        },
        {
          id: 5,
          title: 'Marketing',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod adipiscing elit, sed do eiusmod',
          icon: 'fas fa-bullhorn',
          bgColor: 'bg-orange-300/30',
          iconColor: 'text-orange-500'
        },
        {
          id: 6,
          title: 'Photography',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod adipiscing elit, sed do eiusmod',
          icon: 'fas fa-camera',
          bgColor: 'bg-red-300/30',
          iconColor: 'text-red-500'
        },
        {
          id: 7,
          title: 'Acting',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod adipiscing elit, sed do eiusmod',
          icon: 'fas fa-theater-masks',
          bgColor: 'bg-secondary/30',
          iconColor: 'text-secondary'
        },
        {
          id: 8,
          title: 'Business',
          description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod adipiscing elit, sed do eiusmod',
          icon: 'fas fa-chart-line',
          bgColor: 'bg-teal-300/30',
          iconColor: 'text-teal-500'
        }
      ]
    }
  }
}
</script>
