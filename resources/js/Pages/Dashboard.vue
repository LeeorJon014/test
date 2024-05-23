<script setup lang="ts">
import Sidebar from '@/Components/Sidebar.vue';
import Header from '@/Components/Header.vue';

</script>

<template>
  <div class="flex h-screen bg-gray-600 font-roboto">
    <Sidebar />

    <div class="flex-1 flex flex-col overflow-hidden">
      <Header />

      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
        <div class="container mx-auto px-6 py-8">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>
