<template>
  <div
    class="relative"
    @mouseenter="openDropdown"
    @mouseleave="closeDropdown"
  >
    <!-- Cart Icon -->
    <button
      class="relative flex items-center justify-center w-10 h-10 
             text-[#696984] hover:text-teal-600 hover:bg-gray-100 
             rounded-xl transition-all duration-300 group"
    >
      <i class="fas fa-shopping-cart text-lg group-hover:scale-110 transition-transform"></i>

      <!-- Badge -->
      <div
        v-if="cartCount > 0"
        class="absolute -top-1 -right-1 min-w-[18px] h-[18px] 
               bg-gradient-to-r from-teal-500 to-cyan-500 text-white
               text-xs rounded-full flex items-center justify-center font-semibold"
      >
        {{ cartCount }}
      </div>
    </button>

    <!-- DROPDOWN -->
    <transition name="fade-slide">
      <div
        v-if="open"
        class="cart-dropdown bg-white rounded-2xl shadow-2xl border-0 overflow-hidden"
        @mouseenter="cancelClose"
        @mouseleave="closeDropdown"
      >
        <!-- Header -->
        <div
          class="px-6 py-4 bg-gradient-to-r from-teal-50 to-cyan-50 border-b border-gray-100 flex items-center justify-between"
        >
          <h3 class="text-lg font-semibold text-gray-800">Your Cart</h3>

          <span class="text-sm text-gray-600">{{ cartCount }} courses</span>
        </div>

        <!-- LIST -->
        <div class="max-h-80 overflow-y-auto">
          <div v-if="cartItems.length === 0" class="p-8 text-center">
            <div class="text-gray-400 mb-3">
              <i class="fas fa-shopping-cart text-4xl"></i>
            </div>
            <p class="text-gray-500 mb-4">Your cart is empty.</p>
            <router-link
              to="/courses"
              class="inline-flex items-center px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition"
            >
              Browse courses
            </router-link>
          </div>

          <div v-else class="py-2">
            <div
              v-for="item in cartItems"
              :key="item.id"
              class="flex items-center gap-3 px-6 py-3 hover:bg-gray-50 transition cursor-pointer"
            >
              <div class="relative flex-shrink-0">
                <img
                  :src="item.thumbnail_url"
                  :alt="item.title"
                  class="w-16 h-12 object-cover rounded-lg"
                />
              </div>

              <div class="flex-1">
                <h4 class="text-sm font-medium text-gray-900 truncate">{{ item.title }}</h4>
                <p class="text-sm text-teal-600 font-semibold mt-1">
                  {{ item.price.toLocaleString() }}₫
                </p>
              </div>

              <button
                @click.stop="removeItem(item.id)"
                class="p-1 text-gray-400 hover:text-red-500 transition"
              >
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- FOOTER -->
        <div v-if="cartItems.length > 0" class="px-6 py-4 bg-gray-50 border-t border-gray-100">
          <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-600">Total:</span>
            <span class="text-lg font-bold text-teal-600">
              {{ cartTotal.toLocaleString() }}₫
            </span>
          </div>

          <router-link
            to="/cart"
            class="block w-full bg-gradient-to-r from-teal-500 to-cyan-500 text-white 
                   text-center font-semibold py-3 rounded-xl hover:from-teal-600 hover:to-cyan-600 
                   transition-all duration-300 transform hover:scale-105"
          >
            View Cart
          </router-link>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { CartStorage } from "@/helpers/cartStorage";

const props = defineProps({
  userId: {
    type: Number,
    required: true,
  },
});

const open = ref(false);
let closeTimer = null;

const cartItems = ref([]);

const loadCart = () => {
  cartItems.value = CartStorage.getCart(String(props.userId));
};

const cartCount = computed(() => cartItems.value.length);
const cartTotal = computed(() =>
  cartItems.value.reduce((t, i) => t + Number(i.price), 0)
);

const removeItem = (id) => {
  CartStorage.removeItem(String(props.userId), id);
  loadCart();
};

onMounted(loadCart);

// dropdown logic
const openDropdown = () => {
  clearTimeout(closeTimer);
  open.value = true;
  loadCart();
};

const closeDropdown = () => {
  closeTimer = setTimeout(() => {
    open.value = false;
  }, 200);
};

const cancelClose = () => clearTimeout(closeTimer);
</script>

<style scoped>
.cart-dropdown {
  position: fixed;
  top: 70px;
  right: 110px;
  width: 380px;
  max-height: 500px;
  z-index: 9999;
}

.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.15s ease;
}
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}
</style>
