<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useSidebar } from '../composables/useSidebar';
import { usePage } from "@inertiajs/vue3";
const role = usePage().props.role;

const { isOpen } = useSidebar()
const currentPage = ref('');
// for expand/collapse of view and upload inventory
const dropdownOpen = ref(false)

const activeClass = ref(
  'bg-gray-600 bg-opacity-25 text-gray-100 border-gray-100',
)

const inactiveClass = ref(
  'border-gray-900 text-gray-500 hover:bg-gray-600 hover:bg-opacity-25 hover:text-gray-100',
)

const checkURL = () => {
  const currentURL = window.location.href;

  if (currentURL.includes(`/${role}/dashboard`)) {
    currentPage.value = 'dashboard';
  }
  else if (currentURL.includes(`/${role}/announcement`)) {
    currentPage.value = 'announcement';
  }
  else if (currentURL.includes(`/${role}/company`)) {
    currentPage.value = 'company';
  }
  else if (currentURL.includes(`/${role}/cultural-property/upload-inventory`)) {
    currentPage.value = 'upload-inventory';
    dropdownOpen.value = true;
  }
  else if (currentURL.includes(`/${role}/cultural-property/view-inventory`)) {
    currentPage.value = 'view-inventory';
    dropdownOpen.value = true;
  }
  else if (currentURL.includes(`/${role}/users`)) {
    currentPage.value = 'users';
  }
  else if (currentURL.includes(`/${role}/announcement`)) {
    currentPage.value = 'announcement';
  }
};

const currentClass = (page) => {
  return computed(() => (currentPage.value === page ? activeClass.value : inactiveClass.value));
};

//active navigation state
onMounted(() => {
  checkURL();
});

</script>

<template>
  <div class="flex">
    <!-- Backdrop -->
    <div :class="isOpen ? 'block' : 'hidden'" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"
      @click="isOpen = false" />
    <!-- End Backdrop -->

    <div :class="isOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
      class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-gray-900 lg:translate-x-0 lg:static lg:inset-0">
      <div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
          <a :href="`/${role}/dashboard`" class="mx-2 text-2xl font-semibold text-white cursor-pointer">
            <img
              class="w-54"
              src="/assets/img/talapamana_logo.png"
              alt=""
            />
          </a>
        </div>
      </div>

      <nav class="mt-10">
        <!-- Dashboard -->
        <div class="flex items-center px-6 py-2 mt-4 duration-200 border-l-4" :class="currentClass('dashboard').value"
          :to="`/${role}/dashboard`">

          <svg class="w-5 h-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 10C2 5.58172 5.58172 2 10 2V10H18C18 14.4183 14.4183 18 10 18C5.58172 18 2 14.4183 2 10Z"
              fill="currentColor" />
            <path d="M12 2.25195C14.8113 2.97552 17.0245 5.18877 17.748 8.00004H12V2.25195Z" fill="currentColor" />
          </svg>

          <a :href="`/${role}/dashboard`" class="mx-2 text-1xl font-semibold text-gray cursor-pointer">
            Dashboard
          </a>
        </div>
        <!-- Announcement -->
        <div class="flex items-center px-6 py-2 mt-4 duration-200 border-l-4" :class="currentClass('announcement').value"
          :to="`/${role}/announcement`">

          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-megaphone"
            viewBox="0 0 16 16">
            <path
              d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49a68.14 68.14 0 0 0-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 74.663 74.663 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199V2.5zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0zm-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233c.18.01.359.022.537.036 2.568.189 5.093.744 7.463 1.993V3.85zm-9 6.215v-4.13a95.09 95.09 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A60.49 60.49 0 0 1 4 10.065zm-.657.975 1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68.019 68.019 0 0 0-1.722-.082z" />
          </svg>
          <a :href="`/${role}/announcement`" class="mx-4">
            Announcements
          </a>
        </div>
        <!-- Company -->
        <div class="flex items-center px-6 py-2 mt-4 duration-200 border-l-4" v-if="role !== 'user'"
          :class="currentClass('company').value" :to="`/${role}/company`">

          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
          </svg>

          <a :href="`/${role}/company`" class="mx-4">
            Relevant Interested Party
          </a>
        </div>
        <!-- Cultural Properties -->
        <div class="flex items-center px-6 py-2 mt-4 duration-200 border-l-4 cursor-pointer"
          :class="currentPage == 'view-inventory' || currentPage == 'upload-inventory' ? activeClass : inactiveClass"
          @click="dropdownOpen = !dropdownOpen">

          <svg class="w-5 h-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M5 3C3.89543 3 3 3.89543 3 5V7C3 8.10457 3.89543 9 5 9H7C8.10457 9 9 8.10457 9 7V5C9 3.89543 8.10457 3 7 3H5Z"
              fill="currentColor" />
            <path
              d="M5 11C3.89543 11 3 11.8954 3 13V15C3 16.1046 3.89543 17 5 17H7C8.10457 17 9 16.1046 9 15V13C9 11.8954 8.10457 11 7 11H5Z"
              fill="currentColor" />
            <path
              d="M11 5C11 3.89543 11.8954 3 13 3H15C16.1046 3 17 3.89543 17 5V7C17 8.10457 16.1046 9 15 9H13C11.8954 9 11 8.10457 11 7V5Z"
              fill="currentColor" />
            <path
              d="M11 13C11 11.8954 11.8954 11 13 11H15C16.1046 11 17 11.8954 17 13V15C17 16.1046 16.1046 17 15 17H13C11.8954 17 11 16.1046 11 15V13Z"
              fill="currentColor" />
          </svg>

          <span class="ml-4 flex items-center space-x-5">

            <a :href="`/${role}/cultural-property/view-inventory`">
              <span>Cultural Properties</span>
            </a>

            <!-- dropdown icon -->
            <svg :class="dropdownOpen ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" height="1em"
              viewBox="0 0 320 512">
              <path
                d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"
                fill="currentColor" />
            </svg>
          </span>
        </div>


        <!-- View -->
        <div class="flex items-center pl-10 pr-2 py-2 mt-4 duration-200 border-l-4 cursor-pointer"
          :class="currentClass('view-inventory').value" to="`/${role}/culturalproperty/view-inventory`"
          v-show="dropdownOpen">

          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill"
            viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
          </svg>

          <a :href="`/${role}/cultural-property/view-inventory`" class="mx-4">
            View
          </a>
        </div>

        <!-- Upload Inventory -->
        <div class="flex items-center pl-10 pr-2 py-2 mt-4 duration-200 border-l-4 cursor-pointer"
          :class="currentClass('upload-inventory').value" to="`/${role}/cultural-property/upload-inventory`"
          v-show="dropdownOpen">

          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload"
            viewBox="0 0 16 16">
            <path
              d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
            <path
              d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z" />
          </svg>

          <a :href="`/${role}/cultural-property/upload-inventory`" class="mx-4">
            Upload Inventory
          </a>
        </div>

        <div class="flex items-center px-6 py-2 mt-4 duration-200 border-l-4 cursor-pointer" v-if="role !== 'user'"
          :class="currentClass('users').value" to="`/${role}/users`">

          <svg class="w-5 h-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M7 3C6.44772 3 6 3.44772 6 4C6 4.55228 6.44772 5 7 5H13C13.5523 5 14 4.55228 14 4C14 3.44772 13.5523 3 13 3H7Z"
              fill="currentColor" />
            <path
              d="M4 7C4 6.44772 4.44772 6 5 6H15C15.5523 6 16 6.44772 16 7C16 7.55228 15.5523 8 15 8H5C4.44772 8 4 7.55228 4 7Z"
              fill="currentColor" />
            <path
              d="M2 11C2 9.89543 2.89543 9 4 9H16C17.1046 9 18 9.89543 18 11V15C18 16.1046 17.1046 17 16 17H4C2.89543 17 2 16.1046 2 15V11Z"
              fill="currentColor" />
          </svg>

          <a :href="`/${role}/users`" class="mx-4">
            User
          </a>

        </div>

        <!-- <div class="flex items-center px-6 py-2 mt-4 duration-200 border-l-4 cursor-pointer"
          :class="[$route && $route.name === 'Forms' ? activeClass : inactiveClass]" to="`/${role}/forms`">

          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
            <path fill-rule="evenodd"
              d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
              clip-rule="evenodd" />
          </svg>

          <span class="mx-4">Settings</span>
        </div> -->
      </nav>
    </div>
  </div>
</template>
