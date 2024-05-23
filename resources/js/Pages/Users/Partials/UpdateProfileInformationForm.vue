<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, usePage, Link } from "@inertiajs/vue3";
import { useToast } from "vue-toast-notification";
import { watch } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

import vSelect from 'vue-select';

defineProps({
  userId: {
    type: Number,
  },
  name: {
    type: String,
  },
  email: {
    type: String,
  },
  contactPerson: {
    type: String,
  },
  contactNumber: {
    type: String,
  },
  email_verified_at: {
    type: String,
  },
  companyId: {
    type: Number,
  },
  companies: {
    type: Array,
  },
});

const userId = usePage().props.userId;
const name = usePage().props.name;
const email = usePage().props.email;
const contactPerson = usePage().props.contact_person;
const contactNumber = usePage().props.contact_number;
const companyId = usePage().props.company_id;
const companies = usePage().props.companies;
const user_roles = usePage().props.user_roles;
const roles = usePage().props.roles;

console.log(user_roles)

let form = useForm({
  id: userId,
  name: name,
  email: email,
  contact_person: contactPerson,
  contact_number: contactNumber,
  company_id: companyId,
  role_id: user_roles ? user_roles[0]?.id : null,
});


const toast = useToast()

watch(() => usePage().props.status, flash => {
  if (flash) {
    toast.success(flash);
  }
  usePage().props.status = '';
});
</script>

<template>
  <form @submit.prevent="form.put(route(`${usePage().props.role}.userprofile.update`, { id: userId }))">
    <header
      class="px-5 py-3 bg-midnight-express rounded-tl-xl rounded-tr-xl border border-midnight-express border-opacity-30">
      <p class="text-2xl font-semibold text-white"> Update your account's profile information and email address.</p>
      <p class="text-sm italic text-ronchi">Make sure that the details you enter are correct.</p>
    </header>

    <div
      class="px-5 pt-3 pb-5 bg-hawkes-blue rounded-bl-xl rounded-br-xl border border-midnight-express border-opacity-30">
      <div>
        <InputLabel for="name" value="Name" />

        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />

        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <div class="mt-4">
        <InputLabel for="email" value="Email" />

        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <!-- Added Company, Address, Email, Contact Person, Contact Number -->
      <div>
        <InputLabel for="company_name" value="Company" />
        <select v-model="form.company_id"
          class="mt-3 py-2 block w-full bg-white border border-solid border-midnight-express rounded-md bg-hawkes-blue focus:border-mountain-meadow focus:ring-mountain-meadow">
          <option value="" disabled>Select a Company</option>
          <option v-for="company in companies" :key="company.id" :value="company.id">
            {{ company.name }}
          </option>
        </select>

        <InputError class="mt-2" :message="form.errors.company_id" />
      </div>

      <div>
        <InputLabel for="roles" value="Role" />
        <select v-model="form.role_id"
          class="mt-3 py-2 block w-full bg-white border border-solid border-midnight-express rounded-md bg-hawkes-blue focus:border-mountain-meadow focus:ring-mountain-meadow">
          <option value="" disabled>Select a role</option>
          <option v-for="role in roles" :key="role.id" :value="role.id">
            {{ role.name }}
          </option>
        </select>

        <InputError class="mt-2" :message="form.errors.role_id" />
      </div>

      <div class="mt-4">
        <InputLabel for="contact_person" value="Name of Contact Person" />

        <TextInput id="contact_person" type="text" class="mt-1 block w-full" v-model="form.contact_person"
          placeholder="Type here" />

        <InputError class="mt-2" :message="form.errors.contact_person" />
      </div>

      <div class="mt-4">
        <InputLabel for="contact_number" value="Contact Number" />

        <TextInput id="contact_number" type="text" class="mt-1 block w-full" v-model="form.contact_number"
          placeholder="Type here" />

        <InputError class="mt-2" :message="form.errors.contact_number" />
      </div>

      <div class="flex items-center justify-start mt-10">
        <PrimaryButton :disabled="form.processing">
          Save
        </PrimaryButton>

        <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
          <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">
            Saved.
          </p>
        </Transition>

        <Link :href="`/${$page.props.role}/users`">
        <SecondaryButton class="ml-4 text-opacity-70">
          Back to User Page
        </SecondaryButton>
        </Link>

      </div>
    </div>
  </form>
</template>

<style>
/* main.scss */
@import 'vue-select/dist/vue-select.css';
</style>
