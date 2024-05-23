<script setup lang="ts">
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});
</script>

<template>
  <Head title="Profile" />
  <DashboardLayout>
    <div class="px-5 sm:px-10 py-5">
      <h1 class="font-dm-sans text-5xl">Profile Information</h1>

      <div class="w-full">
        <UpdateProfileInformationForm class="mt-4"
          :must-verify-email="mustVerifyEmail"
          :status="status"/>
      </div>

      <div class="w-full">
        <UpdatePasswordForm class="mt-4" />
      </div>

      <div class="w-full mt-4">
        <DeleteUserForm />
      </div>
    </div>
  </DashboardLayout>
</template>

