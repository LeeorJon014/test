<script setup lang="ts">
import Sidebar from '@/Components/Sidebar.vue';
import Header from '@/Components/Header.vue';

</script>

<template>
  
  <div class="flex h-screen font-roboto">
    <Sidebar />

    <div class="flex-1 flex flex-col overflow-hidden">
      <Header />

      <main class="font-public-sans flex-1 overflow-x-hidden overflow-y-auto">
        <!-- <div class="container mx-auto"> -->
          <slot />
        <!-- </div> -->
      </main>
    </div>
  </div>
</template>