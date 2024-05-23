<script setup>
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
  userId: {
    type: Number,
  }
});
</script>

<template>
  <Head title="Edit User Profile" />
  <DashboardLayout>
    <div class="px-5 sm:px-10 py-5">
      <h1 class="font-dm-sans text-5xl">Edit User Profile</h1>

      <div class="max-w-3xl space-y-6">
        <UpdateProfileInformationForm class="mt-4" />
      </div>
    </div>
  </DashboardLayout>
</template>
