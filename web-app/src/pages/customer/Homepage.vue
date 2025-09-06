<template>
  <LayoutHomepage>
    <main>
      <!-- ========= All-in-one Cloud Software Section ========= -->
      <section class="py-24">
        <div class="container mx-auto px-6 text-center">
          <h2 class="text-4xl font-bold text-[#2F327D]">All-In-One Cloud Software.</h2>
          <p class="mt-4 text-xl text-[#696984] max-w-3xl mx-auto">
            TOTC is one powerful online software suite that combines all the tools needed to run a successful school or
            office.
          </p>
          <div class="mt-16 grid md:grid-cols-3 gap-10">
            <div v-for="feature in allInOneFeatures" :key="feature.title"
              class="bg-white p-8 rounded-2xl shadow-lg text-center">
              <div class="w-24 h-24 rounded-full mx-auto flex items-center justify-center" :class="feature.bgColor">
                <img :src="feature.icon" :alt="feature.title" class="h-12 w-12">
              </div>
              <h3 class="mt-6 text-2xl font-semibold text-[#2F327D]">{{ feature.title }}</h3>
              <p class="mt-4 text-[#696984]">{{ feature.description }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- ========= What is TOTC Section ========= -->
      <section class="py-24 bg-gray-50">
        <div class="container mx-auto px-6 text-center">
          <h2 class="text-4xl font-bold text-[#2F327D]">What is TOTC?</h2>
          <p class="mt-4 text-xl text-[#696984] max-w-4xl mx-auto">
            TOTC is a platform that allows educators to create online classes whereby they can store the course
            materials online; manage assignments, quizzes and exams; monitor due dates; grade results and provide
            students with feedback all in one place.
          </p>
          <div class="mt-16 grid lg:grid-cols-2 gap-10">
            <div class="relative rounded-2xl overflow-hidden h-96">
              <img src="https://placehold.co/600x400/E4C727/FFFFFF" alt="For Instructors"
                class="w-full h-full object-cover">
              <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col items-center justify-center p-8">
                <h3 class="text-3xl font-bold text-white">FOR INSTRUCTORS</h3>
                <button
                  class="mt-6 px-8 py-3 rounded-full border-2 border-white text-white font-medium hover:bg-white hover:text-black transition-colors">Start
                  a class today</button>
              </div>
            </div>
            <div class="relative rounded-2xl overflow-hidden h-96">
              <img src="https://placehold.co/600x400/9EFE4A/FFFFFF" alt="For Students"
                class="w-full h-full object-cover">
              <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col items-center justify-center p-8">
                <h3 class="text-3xl font-bold text-white">FOR STUDENTS</h3>
                <button
                  class="mt-6 px-8 py-3 rounded-full bg-[#23BDEE] bg-opacity-90 text-white font-medium hover:bg-opacity-100 transition-opacity">Enter
                  access code</button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ========= Our Success Section ========= -->
      <section class="py-24">
        <div class="container mx-auto px-6 flex flex-col lg:flex-row items-center justify-between gap-16">
          <div class="lg:w-1/2">
            <h2 class="text-4xl font-bold text-[#2F327D]">Everything you can do in a physical classroom, <span
                class="text-[#49BBBD]">you can do with TOTC</span></h2>
            <p class="mt-6 text-lg text-[#696984]">
              TOTC’s school management software helps traditional and online schools manage scheduling, attendance,
              payments and virtual classrooms all in one secure cloud-based system.
            </p>
            <a href="#" class="mt-6 inline-block text-[#696984] underline">Learn more</a>
          </div>
          <div class="lg:w-1/2 relative">
            <img src="https://placehold.co/700x470/8RMIVJ/FFFFFF" alt="Classroom video" class="rounded-2xl w-full">
            <div class="absolute inset-0 flex items-center justify-center">
              <button class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-xl">
                <svg class="w-10 h-10 text-[#23BDEE]" viewBox="0 0 32 34" fill="none"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M30.1429 14.3693C32.1429 15.5224 32.1429 18.4776 30.1429 19.6307L5.14286 33.5738C3.14286 34.7269 0.642856 33.2493 0.642856 30.9431L0.642857 3.05692C0.642857 0.750716 3.14286 -0.726885 5.14286 0.426216L30.1429 14.3693Z"
                    fill="#23BDEE" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- ========= Features Section ========= -->
      <section class="py-24 bg-gray-50">
        <div class="container mx-auto px-6 text-center">
          <h2 class="text-4xl font-bold text-[#2F327D]">Our Features</h2>
          <p class="mt-4 text-xl text-[#696984] max-w-3xl mx-auto">
            This very extraordinary feature, can make learning activities more efficient
          </p>

          <div class="mt-20 space-y-20">
            <!-- Feature 1 -->
            <div class="flex flex-col lg:flex-row-reverse items-center gap-16">
              <div class="lg:w-1/2 text-left">
                <h3 class="text-3xl font-bold text-[#2F327D]"><span class="text-[#F48C06]">A user interface</span>
                  designed for the classroom</h3>
                <p class="mt-6 text-lg text-[#696984]">Teachers can easily see all students and class data at one time.
                </p>
              </div>
              <div class="lg:w-1/2">
                <img src="https://i.imgur.com/Y2YjYyM.png" alt="UI Feature" class="w-full">
              </div>
            </div>

            <!-- Feature 2 -->
            <div class="flex flex-col lg:flex-row items-center gap-16">
              <div class="lg:w-1/2 text-left">
                <h3 class="text-3xl font-bold text-[#2F327D]"><span class="text-[#2F327D]">Tools</span> For Teachers And
                  Learners</h3>
                <p class="mt-6 text-lg text-[#696984]">Class has a dynamic set of teaching tools built to be deployed
                  and used during class. Teachers can handout assignments in real-time for students to complete and
                  submit.</p>
              </div>
              <div class="lg:w-1/2">
                <img src="https://i.imgur.com/2a95s0Y.png" alt="Tools Feature" class="w-full">
              </div>
            </div>

            <!-- Feature 3 -->
            <div class="flex flex-col lg:flex-row-reverse items-center gap-16">
              <div class="lg:w-1/2 text-left">
                <h3 class="text-3xl font-bold text-[#2F327D]">Assessments, <span class="text-[#23BDEE]">Quizzes,</span>
                  Tests</h3>
                <p class="mt-6 text-lg text-[#696984]">Easily launch live assignments, quizzes, and tests. Student
                  results are automatically entered in the online gradebook.</p>
              </div>
              <div class="lg:w-1/2">
                <img src="https://i.imgur.com/9iLh2mR.png" alt="Assessments Feature" class="w-full">
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ========= Explore Course Section ========= -->
      <section class="py-24">
        <div class="container mx-auto px-6">
          <div class="flex justify-between items-center">
            <h2 class="text-4xl font-bold text-[#2F327D]">Explore Course</h2>
            <a href="#" class="text-[#49BBBD] font-semibold">See all</a>
          </div>
          <div class="mt-10 grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <div v-for="course in courses" :key="course.title"
              class="bg-white rounded-2xl shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
              <div class="h-48" :class="course.bgColor">
                <img :src="course.image" class="w-full h-full object-contain p-4">
              </div>
              <div class="p-6">
                <h3 class="text-xl font-bold text-[#2F327D]">{{ course.title }}</h3>
                <p class="text-gray-500 mt-2">{{ course.lessons }} lessons</p>
                <div class="mt-4 flex justify-between items-center">
                  <div class="flex items-center">
                    <img src="https://placehold.co/32x32" alt="author" class="w-8 h-8 rounded-full">
                    <span class="ml-2 font-semibold">{{ course.author }}</span>
                  </div>
                  <span class="text-xl font-bold text-[#49BBBD]">{{ course.price }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ========= Testimonials Section ========= -->
      <section class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
          <div class="text-center">
            <p class="text-[#525596] font-semibold tracking-widest">TESTIMONIAL</p>
            <h2 class="mt-2 text-4xl font-bold text-[#2F327D]">What They Say?</h2>
            <p class="mt-4 text-lg text-[#696984] max-w-2xl mx-auto">
              TOTC has got more than 100k positive ratings from our users around the world.
            </p>
          </div>
          <div class="mt-16 relative">
            <!-- This would be a carousel/slider in a real app -->
            <div class="flex items-center justify-center">
              <div
                class="bg-white p-8 rounded-2xl shadow-lg max-w-4xl mx-auto flex flex-col md:flex-row items-center gap-8">
                <div class="md:w-1/3 relative">
                  <div class="absolute -top-4 -left-4 w-full h-full border-4 border-[#49BBBD] rounded-2xl"></div>
                  <img src="https://placehold.co/280x350/4c6498/FFFFFF" alt="Gloria Rose"
                    class="rounded-2xl relative z-10 w-full">
                </div>
                <div class="md:w-2/3 text-left">
                  <p class="text-xl text-[#5F5F7E] leading-relaxed">
                    "Thank you so much for your help. It's exactly what I've been looking for. You won't regret it. It
                    really saves me time and effort. TOTC is exactly what our business has been lacking."
                  </p>
                  <h4 class="mt-6 text-2xl font-bold text-[#5F5F7E]">Gloria Rose</h4>
                  <div class="mt-2 flex items-center justify-between">
                    <div class="flex items-center space-x-1">
                      <span v-for="i in 5" :key="i" class="text-yellow-400">★</span>
                    </div>
                    <p class="text-[#80819A]">12 reviews at Yelp</p>
                  </div>
                </div>
              </div>
            </div>
            <!-- Slider controls -->
            <button
              class="absolute top-1/2 -translate-y-1/2 left-0 w-12 h-12 bg-white rounded-full shadow-md flex items-center justify-center text-2xl text-[#49BBBD]">&lt;</button>
            <button
              class="absolute top-1/2 -translate-y-1/2 right-0 w-12 h-12 bg-white rounded-full shadow-md flex items-center justify-center text-2xl text-[#49BBBD]">&gt;</button>
          </div>
        </div>
      </section>

      <!-- ========= News Section ========= -->
      <section class="py-24">
        <div class="container mx-auto px-6">
          <div class="text-center">
            <h2 class="text-4xl font-bold text-[#2F327D]">Lastest News and Resources</h2>
            <p class="mt-4 text-lg text-[#696984] max-w-2xl mx-auto">
              See the developments that have occurred to TOTC in the world
            </p>
          </div>
          <div class="mt-16 grid lg:grid-cols-2 gap-12 items-start">
            <div class="space-y-6">
              <img src="https://placehold.co/640x340/K8ETJE/FFFFFF" alt="Main news" class="rounded-2xl w-full">
              <span class="inline-block px-4 py-1 bg-[#49BBBD] text-white rounded-full text-sm">NEWS</span>
              <h3 class="text-2xl font-semibold text-[#252641]">Class adds $30 million to its balance sheet for a
                Zoom-friendly edtech solution</h3>
              <p class="text-[#696984]">Class, launched less than a year ago by Blackboard co-founder Michael Chasen,
                integrates exclusively...</p>
              <a href="#" class="text-[#696984] underline">Read more</a>
            </div>
            <div class="space-y-8">
              <div v-for="article in newsArticles" :key="article.title" class="flex items-start gap-6">
                <img :src="article.image" :alt="article.title" class="w-48 h-32 object-cover rounded-2xl">
                <div>
                  <span class="inline-block px-3 py-1 bg-[#49BBBD] text-white rounded-full text-xs">{{ article.category
                  }}</span>
                  <h4 class="mt-2 text-lg font-semibold text-[#252641]">{{ article.title }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </LayoutHomepage>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import LayoutHomepage from './layout/layoutHomepage.vue';

const router = useRouter();

const allInOneFeatures = ref([
  {
    title: 'Online Billing, Invoicing, & Contracts',
    description: 'Simple and secure control of your organization’s financial and legal transactions. Send customized invoices and contracts',
    icon: 'https://api.iconify.design/solar:bill-list-bold-duotone.svg',
    bgColor: 'bg-blue-100'
  },
  {
    title: 'Easy Scheduling & Attendance Tracking',
    description: 'Schedule and reserve classrooms at one campus or multiple campuses. Keep detailed records of student attendance',
    icon: 'https://api.iconify.design/solar:calendar-bold-duotone.svg',
    bgColor: 'bg-red-100'
  },
  {
    title: 'Customer Tracking',
    description: 'Automate and track emails to individuals or groups. Skilline’s built-in system helps organize your organization',
    icon: 'https://api.iconify.design/solar:users-group-rounded-bold-duotone.svg',
    bgColor: 'bg-green-100'
  }
]);

const courses = ref([
  { title: 'UI/UX Design', lessons: 25, author: 'Jane Doe', price: '$150', image: 'https://placehold.co/150x150/FF6F00/FFFFFF?text=UI/UX', bgColor: 'bg-orange-100' },
  { title: 'Illustration', lessons: 30, author: 'John Smith', price: '$200', image: 'https://placehold.co/150x150/FF8374/FFFFFF?text=Art', bgColor: 'bg-red-100' },
  { title: 'Photography', lessons: 22, author: 'Emily White', price: '$120', image: 'https://placehold.co/150x150/B45A1B/FFFFFF?text=Photo', bgColor: 'bg-yellow-800' },
  { title: 'Web Development', lessons: 40, author: 'Chris Green', price: '$250', image: 'https://placehold.co/150x150/FFB300/FFFFFF?text=Code', bgColor: 'bg-yellow-200' },
]);

const newsArticles = ref([
  {
    title: 'Class Technologies Inc. Closes $30 Million Series A Financing',
    category: 'PRESS RELEASE',
    image: 'https://placehold.co/280x200/OLBLJD/FFFFFF'
  },
  {
    title: 'Zoom’s earliest investors are betting millions on a better Zoom for schools',
    category: 'NEWS',
    image: 'https://placehold.co/280x200/R5I590/FFFFFF'
  },
  {
    title: 'Former Blackboard CEO Raises $16M to Bring LMS Features to Zoom Classrooms',
    category: 'NEWS',
    image: 'https://placehold.co/280x200/U5ZYKT/FFFFFF'
  }
]);
</script>

<style scoped>
.bg-gradient-blue-teal {
  background: linear-gradient(90deg, #136CB5 0%, #49BBBD 100%);
}
</style>
