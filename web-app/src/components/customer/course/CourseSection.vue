<template>
  <div>
    <!-- Recommended Courses -->
    <section class="relative py-16 px-6 lg:px-20">
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-20">
        <img 
          src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/5isVbV31ju.svg" 
          alt="Background Pattern" 
          class="w-full h-full object-cover"
        />
      </div>
      
      <div class="relative max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-12">
          <h2 class="text-black font-poppins text-4xl font-medium">Recommended for you</h2>
          <a href="#" class="text-primary font-poppins text-xl font-bold hover:underline">See all</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
          <CourseCard 
            v-for="course in recommendedCourses" 
            :key="course.id"
            :course="course"
            :showPrice="true"
          />
        </div>
        
        <!-- Navigation Arrows -->
        <div class="flex justify-end space-x-4">
          <button class="w-12 h-12 bg-primary/50 rounded flex items-center justify-center hover:bg-primary/70 transition-colors">
            <i class="fas fa-chevron-left text-white text-lg"></i>
          </button>
          <button class="w-12 h-12 bg-primary rounded flex items-center justify-center hover:bg-primary/80 transition-colors">
            <i class="fas fa-chevron-right text-white text-lg"></i>
          </button>
        </div>
      </div>
    </section>
    
    <!-- Get Choice Courses -->
    <section class="py-16 px-6 lg:px-20">
      <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-12">
          <h2 class="text-black font-poppins text-4xl font-medium">Get choice of your course</h2>
          <a href="#" class="text-primary font-poppins text-xl font-bold hover:underline">See all</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <CourseCard 
            v-for="course in choiceCourses" 
            :key="course.id"
            :course="course"
            :showPrice="true"
          />
        </div>
      </div>
    </section>
    
    <!-- Personal Development -->
    <section class="py-16 px-6 lg:px-20">
      <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-12">
          <h2 class="text-black font-poppins text-4xl font-medium">The course in personal development</h2>
          <a href="#" class="text-primary font-poppins text-xl font-bold hover:underline">See all</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <CourseCard 
            v-for="course in personalCourses" 
            :key="course.id"
            :course="course"
            :showPrice="true"
          />
        </div>
      </div>
    </section>
    
    <!-- Student Viewing -->
    <section class="relative py-16 px-6 lg:px-20">
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-20">
        <img 
          src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/vNhjtMSvDv.svg" 
          alt="Background Pattern" 
          class="w-full h-full object-cover"
        />
      </div>
      
      <div class="relative max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-12">
          <h2 class="text-black font-poppins text-4xl font-medium">Student are viewing</h2>
          <a href="#" class="text-primary font-poppins text-xl font-bold hover:underline">See all</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <CourseCard 
            v-for="course in studentCourses" 
            :key="course.id"
            :course="course"
            :showPrice="true"
          />
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import CourseCard from './CourseCard.vue'

export default {
  name: 'CourseSections',
  components: {
    CourseCard
  },
  data() {
    return {
      recommendedCourses: [
        {
          id: 1,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/9eRRfPvnBF.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/vKrXEuwtJc.png',
          category: 'Design',
          duration: '3 Month'
        },
        {
          id: 2,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/9kmyN1T7LL.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/07QdZx2H2X.png',
          category: 'Design',
          duration: '3 Month'
        },
        {
          id: 3,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/fo0qk6bVR6.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/wOgB0ddBey.png',
          category: 'Design',
          duration: '3 Month'
        },
        {
          id: 4,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/K3DP4zHm6n.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/pWGv4wy3i7.png',
          category: 'Design',
          duration: '3 Month'
        }
      ],
      choiceCourses: [
        {
          id: 5,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/6JQCEDiJZG.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/iAnsHmPEQo.png',
          category: 'Design',
          duration: '3 Month'
        },
        {
          id: 6,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/GULpG9CdNY.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/SWrH36CmU4.png',
          category: 'Design',
          duration: '3 Month'
        },
        {
          id: 7,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/wGeBwdBjo5.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/G4wCVBy5vq.png',
          category: 'Design',
          duration: '3 Month'
        },
        {
          id: 8,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/zoz3JXDdr0.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/fpdGFXj0iY.png',
          category: 'Design',
          duration: '3 Month'
        }
      ],
      personalCourses: [
        {
          id: 9,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/v1kPY9zYTb.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/c7GqZaG3O8.png',
          category: 'Design',
          duration: '3 Month'
        },
        {
          id: 10,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/fkN3zy9WRr.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/zSakkGiwYS.png',
          category: 'Design',
          duration: '3 Month'
        },
        {
          id: 11,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/MHzaLVa2Y0.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/xbpC9Gw5yg.png',
          category: 'Design',
          duration: '3 Month'
        },
        {
          id: 12,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/LodOiwothd.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/BWLm2QpsNk.png',
          category: 'Design',
          duration: '3 Month'
        }
      ],
      studentCourses: [
        {
          id: 13,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/kY2aUeOubN.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/2nLpng',
          category: 'Design',
          duration: '3 Month'
        },
        {
          id: 14,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/kY2aUeOubN.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/2nLpng',
          category: 'Design',
          duration: '3 Month'
        },
        {
          id: 15,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/kY2aUeOubN.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/2nLpng',
          category: 'Design',
          duration: '3 Month'
        },
        {
          id: 16,
          title: 'AWS Certified solutions Architect',
          description: 'Lorem ipsum dolor sit amet, consectetur adipising elit, sed do eiusmod tempor',
          instructor: 'Lina',
          originalPrice: '$100',
          price: '$80',
          image: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/kY2aUeOubN.png',
          avatar: 'https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-08-26/2nLpng',
          category: 'Design',
          duration: '3 Month'
        }
      ]
    }
  }
}
</script>