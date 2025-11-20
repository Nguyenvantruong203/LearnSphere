<template>
    <a-modal
        v-model:open="open"
        centered
        width="900px"
        :footer="null"
        class="certificate-modal"
        :mask-style="{ backgroundColor: 'rgba(0, 0, 0, 0.7)' }"
    >
        <div class="relative">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-teal-600 text-white p-4 mx-6 rounded-t-xl">
                <div class="text-center">
                    <h2 class="text-3xl font-bold mb-2 animate-fade-in-up">Congratulations!</h2>
                    <p class="text-blue-100 text-lg animate-fade-in-up animation-delay-200">
                        You have successfully completed this course
                    </p>
                </div>
            </div>

            <!-- Certificate Preview -->
            <div class="bg-white p-4 md:p-8">

                <div
                    class="bg-gradient-to-br from-amber-50 to-orange-50 border-4 border-amber-400 rounded-2xl p-4 md:p-8 relative overflow-hidden shadow-2xl animate-scale-in">

                    <!-- Certificate Header -->
                    <div class="text-center relative z-10">

                        <div
                            class="inline-flex items-center justify-center w-16 h-16 md:w-24 md:h-24 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-full mb-6 shadow-lg animate-bounce-slow">
                            <svg class="w-8 h-8 md:w-12 md:h-12" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9,21H15L13.5,18.5L14.36,17H9.64L10.5,18.5M12,2L13.09,8.26L20,9L13.09,9.74L12,16L10.91,9.74L4,9L10.91,8.26L12,2Z" />
                            </svg>
                        </div>

                        <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 animate-fade-in-up">
                            Certificate of Completion
                        </h3>

                        <div class="w-24 h-1 bg-gradient-to-r from-amber-500 to-orange-500 mx-auto mb-6 animate-expand"></div>

                        <p class="text-gray-600 mb-2 animate-fade-in">This certifies that</p>

                        <h4
                            class="text-2xl md:text-4xl font-bold text-gray-800 mb-6 bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent animate-fade-in-up animation-delay-300">
                            {{ certificate?.user?.name }}
                        </h4>

                        <p class="text-gray-600 mb-4 animate-fade-in animation-delay-400">
                            has successfully completed the course
                        </p>

                        <div
                            class="bg-white bg-opacity-60 rounded-xl p-4 mb-6 border border-amber-300 animate-fade-in-up animation-delay-500">
                            <h5 class="text-lg md:text-2xl font-bold text-gray-800">
                                "{{ certificate?.course?.title }}"
                            </h5>
                        </div>

                        <!-- Certificate Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mt-8">

                            <div
                                class="bg-white bg-opacity-60 rounded-xl p-4 border border-amber-200 animate-slide-in-left animation-delay-600">
                                <p class="text-sm text-gray-500 mb-1">Certificate Code</p>
                                <p class="text-base md:text-lg font-bold text-gray-800 tracking-wider break-all">
                                    {{ certificate?.certificate_code }}
                                </p>
                            </div>

                            <div
                                class="bg-white bg-opacity-60 rounded-xl p-4 border border-amber-200 animate-slide-in-right animation-delay-700">
                                <p class="text-sm text-gray-500 mb-1">Issued Date</p>
                                <p class="text-base md:text-lg font-bold text-gray-800">
                                    {{ formatDate(certificate?.issued_at) }}
                                </p>
                            </div>
                        </div>

                        <!-- Signature -->
                        <div
                            class="flex flex-col md:flex-row justify-between items-center md:items-end mt-8 md:mt-12 gap-6 md:gap-0">

                            <div class="text-center md:text-left">
                                <div class="w-24 md:w-32 h-0.5 bg-gray-400 mb-2 mx-auto md:mx-0"></div>
                                <p class="text-sm text-gray-600 font-medium">LearnSphere</p>
                                <p class="text-xs text-gray-500">Online Learning Platform</p>
                            </div>

                            <div class="text-center order-first md:order-none">
                                <div
                                    class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center mb-3 mx-auto animate-spin-slow">
                                    <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12,2L15.09,8.26L22,9L17,14.14L18.18,21.02L12,17.77L5.82,21.02L7,14.14L2,9L8.91,8.26L12,2Z" />
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-500">Official Certification</p>
                            </div>

                            <div class="text-center md:text-right">
                                <div class="w-24 md:w-32 h-0.5 bg-gray-400 mb-2 mx-auto md:ml-auto"></div>
                                <p class="text-sm text-gray-600 font-medium">Instructor</p>
                                <p class="text-xs text-gray-500">
                                    {{ certificate?.instructor?.name || 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8 animate-fade-in-up animation-delay-800">

                    <a-button
                        size="large"
                        @click="open = false"
                        class="px-8 h-12 rounded-xl border-gray-300 hover:scale-105 transition-transform duration-200">
                        Close
                    </a-button>

                    <a-button
                        type="primary"
                        size="large"
                        @click="downloadPDF"
                        class="px-8 h-12 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 border-0 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 hover:-translate-y-1">
                        <div class="flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                            </svg>
                            Download PDF
                        </div>
                    </a-button>
                </div>

                <!-- Additional Note -->
                <div
                    class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200 animate-fade-in animation-delay-1000">
                    <div class="flex items-center text-blue-700 justify-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M13,9H11V7H13M13,17H11V11H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                        </svg>
                        <span class="text-sm font-medium text-center">
                            This certificate is valid and officially recognized by LearnSphere.
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </a-modal>
</template>

<script setup>
import { ref, watch } from "vue";
import { notification } from 'ant-design-vue';
import { certificationApi } from '@/api/customer/certificationApi'

const props = defineProps({
    open: Boolean,
    certificate: Object
})

const emit = defineEmits(['update:open'])

const open = ref(props.open)
const downloading = ref(false)

// Sync state
watch(() => props.open, v => open.value = v)
watch(open, v => emit("update:open", v))

// Format date
const formatDate = (date) => {
    if (!date) return ''
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric'
    })
}

// Download file
const downloadPDF = async () => {
    if (!props.certificate?.id) return

    downloading.value = true

    try {
        const token = JSON.parse(localStorage.getItem('client_auth') || '{}')?.token
        const url = certificationApi.getCertificateDownloadUrl(props.certificate.id)

        const res = await fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/pdf'
            }
        })

        if (!res.ok) {
            throw new Error(`HTTP ${res.status}: ${await res.text()}`)
        }

        const blob = await res.blob()
        const link = window.URL.createObjectURL(blob)

        const a = document.createElement('a')
        a.href = link
        a.download = `certificate-${props.certificate.certificate_code}.pdf`
        a.click()

        window.URL.revokeObjectURL(link)

        notification.success({
            message: 'Download successful',
            description: 'Your certificate has been downloaded.',
            duration: 3
        })

    } catch (error) {
        notification.error({
            message: 'Download failed',
            description: 'Unable to download certificate. Please try again later.',
            duration: 4
        })
    } finally {
        downloading.value = false
    }
}
</script>
