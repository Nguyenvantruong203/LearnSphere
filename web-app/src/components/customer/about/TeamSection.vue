<template>
  <section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
      <!-- Section Header -->
      <div
        v-motion
        class="text-center mb-16"
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }"
      >
        <h2 class="text-4xl lg:text-5xl font-bold text-[#2F327D] mb-6">
          Our Expert Team
        </h2>
        <p class="text-lg text-[#696984] max-w-3xl mx-auto">
          Talented and dedicated people shaping the future of online education
        </p>
      </div>

      <!-- 🔹 Team Grid -->
      <div v-if="loadingList" class="text-center py-16">
        <a-spin size="large" />
      </div>

      <div
        v-else
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12"
      >
        <div
          v-for="(member, index) in instructors"
          :key="member.id"
          v-motion
          :initial="{ opacity: 0, y: 50, scale: 0.9 }"
          :enter="{
            opacity: 1,
            y: 0,
            scale: 1,
            transition: { delay: index * 0.1, duration: 0.8, type: 'spring' },
          }"
          class="group flex flex-col"
        >
          <div
            class="relative bg-gradient-to-br from-gray-50 to-white rounded-3xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4 flex flex-col h-full"
          >
            <!-- Member Image -->
            <div class="relative mb-6">
              <div
                class="relative mx-auto w-32 h-32 rounded-full overflow-hidden shadow-lg group-hover:shadow-xl transition-shadow duration-300"
              >
                <img
                  :src="member.avatar_url || defaultAvatar"
                  :alt="member.name"
                  class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                />
              </div>
            </div>

            <!-- Member Info -->
            <div class="text-center flex-1 flex flex-col justify-between">
              <div>
                <h3 class="text-xl font-bold text-[#2F327D] mb-2">
                  {{ member.name }}
                </h3>
                <p class="text-teal-600 font-semibold mb-3">
                  {{ member.expertise || 'Instructor' }}
                </p>
                <p
                  class="text-[#696984] text-sm leading-relaxed mb-6 line-clamp-4"
                >
                  {{ member.bio || 'No description available.' }}
                </p>
              </div>

              <!-- LinkedIn -->
              <div v-if="member.linkedin_url" class="flex justify-center mt-auto">
                <a
                  :href="member.linkedin_url"
                  target="_blank"
                  rel="noopener"
                  class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white transition-all duration-300 transform hover:scale-110 shadow-md"
                >
                  <i class="fab fa-linkedin text-lg"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 🔸 Pagination -->
      <div v-if="pagination.total > pagination.per_page" class="flex justify-center mb-20">
        <a-pagination
          :current="pagination.page"
          :total="pagination.total"
          :page-size="pagination.per_page"
          show-less-items
          @change="onPageChange"
        />
      </div>

      <!-- ✅ Instructor Application Form -->
      <div
        v-motion
        :initial="{ opacity: 0, y: 40 }"
        :enter="{ opacity: 1, y: 0, transition: { duration: 0.8 } }"
        class="bg-gradient-to-br from-blue-50 via-cyan-50 to-teal-50 rounded-3xl p-10 shadow-xl border border-gray-100"
      >
        <h3 class="text-3xl font-bold text-[#2F327D] text-center mb-8">
          Become an Instructor
        </h3>
        <p class="text-center text-[#696984] max-w-2xl mx-auto mb-10">
          Join our growing network of passionate educators and make a real
          impact in shaping the future of learning.
        </p>

        <!-- Ant Design Form -->
        <a-form
          layout="vertical"
          :model="form"
          @finish="handleSubmit"
          class="max-w-3xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6"
        >
          <a-form-item label="Full Name" name="name" :rules="[{ required: true, message: 'Please enter your name' }]">
            <a-input v-model:value="form.name" placeholder="Enter your name" />
          </a-form-item>

          <a-form-item
            label="Email"
            name="email"
            :rules="[
              { required: true, message: 'Please enter your email' },
              { type: 'email', message: 'Invalid email format' },
            ]"
          >
            <a-input v-model:value="form.email" placeholder="Enter your email" />
          </a-form-item>

          <a-form-item label="Phone" name="phone">
            <a-input v-model:value="form.phone" placeholder="Enter your phone number" />
          </a-form-item>

          <a-form-item label="Password" name="password">
            <a-input-password
              v-model:value="form.password"
              placeholder="Enter a password (optional)"
            />
          </a-form-item>

          <a-form-item
            label="Expertise Area"
            name="expertise"
            :rules="[{ required: true, message: 'Please enter your expertise area' }]"
          >
            <a-input v-model:value="form.expertise" placeholder="e.g., Data Science, Marketing, Design" />
          </a-form-item>

          <a-form-item label="LinkedIn URL" name="linkedin_url">
            <a-input v-model:value="form.linkedin_url" placeholder="https://linkedin.com/in/..." />
          </a-form-item>

          <a-form-item label="Portfolio URL" name="portfolio_url">
            <a-input v-model:value="form.portfolio_url" placeholder="https://yourportfolio.com" />
          </a-form-item>

          <a-form-item label="Years of Experience" name="teaching_experience">
            <a-input-number v-model:value="form.teaching_experience" :min="0" :max="50" class="w-full" />
          </a-form-item>

          <a-form-item
            label="Short Bio"
            name="bio"
            :rules="[
              { required: true, message: 'Please tell us about yourself' },
              { min: 30, message: 'Bio should be at least 30 characters' },
            ]"
            class="md:col-span-2"
          >
            <a-textarea
              v-model:value="form.bio"
              rows="4"
              placeholder="Tell us about your experience and teaching passion..."
            />
          </a-form-item>

          <div class="md:col-span-2 flex justify-center mt-6">
            <a-button
              type="primary"
              html-type="submit"
              size="large"
              :loading="loading"
              class="px-8 py-3 rounded-full bg-gradient-to-r from-teal-500 to-blue-600 border-none text-white hover:from-teal-600 hover:to-blue-700"
            >
              Submit Application
            </a-button>
          </div>
        </a-form>

        <a-alert
          v-if="successMessage"
          type="success"
          show-icon
          class="mt-8 text-center max-w-2xl mx-auto"
          :message="successMessage"
        />
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { message } from 'ant-design-vue'
import { instructorApi } from '@/api/customer/instructorApi'
import type { ApplyInstructorPayload, User } from '@/types/User'

const instructors = ref<User[]>([])
const loadingList = ref(false)
const defaultAvatar = 'https://static.codia.ai/image/2025-10-02/4dOSBEkxQr.png'

// Pagination
const pagination = ref({
  page: 1,
  per_page: 9,
  total: 0,
})

// Load instructors
const fetchInstructors = async (page = 1) => {
  loadingList.value = true
  try {
    const res = await instructorApi.getList({ page, per_page: pagination.value.per_page })
    if (res.success) {
      instructors.value = res.data.data
      pagination.value = {
        page: res.data.current_page,
        per_page: res.data.per_page || 9,
        total: res.data.total,
      }
    }
  } catch (err) {
    message.error('Failed to load instructors.')
  } finally {
    loadingList.value = false
  }
}

const onPageChange = (page: number) => {
  fetchInstructors(page)
}

// Load initial
onMounted(() => {
  fetchInstructors(1)
})

// Apply form
const form = ref<ApplyInstructorPayload>({
  name: '',
  email: '',
  phone: '',
  password: '',
  expertise: '',
  bio: '',
  linkedin_url: '',
  portfolio_url: '',
  teaching_experience: 0,
})

const loading = ref(false)
const successMessage = ref('')

// Submit handler
const handleSubmit = async () => {
  loading.value = true
  try {
    const res = await instructorApi.apply(form.value)
    if (res.success) {
      message.success(res.message)
      successMessage.value = res.message
      fetchInstructors(1) // reload list sau khi apply thành công
      form.value = {
        name: '',
        email: '',
        phone: '',
        password: '',
        expertise: '',
        bio: '',
        linkedin_url: '',
        portfolio_url: '',
        teaching_experience: 0,
      }
    } else {
      message.error(res.message || 'Failed to submit application.')
    }
  } catch (err: any) {
    message.error(err?.response?.data?.message || 'Something went wrong.')
  } finally {
    loading.value = false
    setTimeout(() => (successMessage.value = ''), 5000)
  }
}
</script>

<style scoped>
.ant-form-item-label label {
  font-weight: 600;
  color: #2f327d;
}
.line-clamp-4 {
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
