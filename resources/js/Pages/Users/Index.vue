<template>
  <Head :title="'Users Page ' + usersData.current_page" />
  <DashboardLayout>
    <div class="px-5 sm:px-10 py-5">
      <div>
        <Link :href="'/register'" class="float-right">
        <SecondaryButton>
          <span class="mx-4">Register Another User</span>
        </SecondaryButton>
        </Link>

        <h1 class="font-dm-sans text-5xl">Users Table</h1>
      </div>


      <form @submit.prevent="filterForm">
        <div class="relative mt-4 bg-midnight-express px-5 py-3 rounded-tl-xl rounded-tr-xl">
          <span class="font-dm-sans text-white text-lg font-bold">Filter Options</span>
        </div>


        <!-- Search Name Filter -->
        <div class="relative bg-hawkes-blue px-5 pt-3 pb-5 rounded-bl-xl rounded-br-xl">
          <div class="flex">
            <div class="w-4/5">
              <InputLabel>Search</InputLabel>

              <div class="absolute left-8 top-19 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                  <path fill-rule="evenodd"
                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                    clip-rule="evenodd" />
                </svg>
              </div>

              <input type="search" v-model="nameFilter"
                class="w-full bg-white pl-8 pr-3 py-[0.25rem] rounded-lg text-m border border-midnight-express-70 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow"
                placeholder="Type search keyword/s here" aria-label="Search" aria-describedby="button-addon1" />
            </div>

            <!-- Dropdown for Is Active filter -->
            <div class="w-3/12 ml-5">
              <InputLabel for="is_active">Account Activated</InputLabel>
              <select v-model="statusFilter" id="is_active"
                class="w-full bg-white pl-4 pr-3 py-[0.25rem] rounded-lg text-m border border-midnight-express-70 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow">
                <option class="bg-color-bg" value="" disabled selected>--choose an option--</option>
                <option value="">All</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
            </div>
          </div>

          <div class="flex items-center justify-end mt-5">
            <SecondaryButton class="mr-2" @click="clearFields">
              Clear Selection
            </SecondaryButton>

            <PrimaryButton @click="getResults">
              Apply Filter
            </PrimaryButton>
          </div>
        </div>

        <!-- <div class="my-3">
          <div class="relative mb-4 flex w-full flex-wrap items-stretch">
            <input
              type="search"
              v-model="nameFilter"
              class="relative m-0 -mr-0.5 block w-[1px] min-w-0 flex-auto rounded-l border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary"
              placeholder="Search Name"
              aria-label="Search"
              aria-describedby="button-addon1"
            />
          </div>
        </div> -->


      </form>

      <div class="relative overflow-x-auto pt-7">
        <div>
          <span class="text-2xl font-semibold leading-tight">Results</span>
          <span class="text-md float-right"> Showing {{}}-{{ }} of {{ }} results</span>
        </div>

        <!-- users table -->
        <div class="py-4 overflow-x-auto">
          <div
            class="inline-block w-full border border-midnight-express border-opacity-30 border-b-0 rounded-tl-xl rounded-tr-xl">
            <table>
              <thead class="bg-midnight-express text-ronchi">
                <tr>
                  <th scope="col" class="px-5 py-3 text-left text-sm">User Id</th>
                  <th scope="col" class="px-5 py-3 text-left text-sm">Name</th>
                  <th scope="col" class="px-5 py-3 text-left text-sm">Email</th>
                  <th scope="col" class="px-5 py-3 text-left text-sm">Email Verified At</th>
                  <th scope="col" class="w-1/12 px-5 py-3 text-left text-sm">Is Activated</th>
                  <th scope="col" class="px-5 py-3 text-left text-sm">Company</th>
                  <th scope="col" class="px-5 py-3 text-left text-sm">Role</th>
                  <th scope="col" class="w-1/12 px-5 py-3 text-left text-sm">Created At</th>
                  <th scope="col" class="w-1/12 px-5 py-3 text-left text-sm">Updated At</th>
                  <th scope="col" class="px-5 py-3 text-left text-sm">Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- display users data per page -->
                <tr v-if="usersData.data && usersData.data.length === 0"
                class="bg-white text-midnight-express even:bg-hawkes-blue text-sm text-left border-b border-midnight-express border-opacity-30">
                  <td colspan="10" class="px-5 py-3 text-center">
                      Nothing matches your search.
                    </td>
                </tr>          
                <tr
                  class="bg-white text-midnight-express even:bg-hawkes-blue text-sm text-left border-b border-midnight-express border-opacity-30"
                  v-for="(user, index) in usersData.data">
                  <th scope="row" class="px-5 py-3 font-medium whitespace-nowrap">
                    {{ user.id }}
                  </th>
                  <td class="px-5 py-3">
                    {{ user.name }}
                  </td>
                  <td class="px-5 py-3">
                    {{ user.email }}
                  </td>
                  <td class="px-5 py-3">
                    {{
                      user.email_verified_at === null
                      ? "Inactive"
                      : user.formatted_email_verified_at
                    }}
                  </td>
                  <td class="px-5 py-3">
                    {{ user.is_active }}
                  </td>
                  <td class="px-6 py-4">
                    {{ user?.company?.name }}
                  </td>
                  <td class="px-6 py-4">
                    {{ user?.roles[0]?.name }}
                  </td>
                  <td class="px-5 py-3">
                    {{ user.formatted_created_at }}
                  </td>
                  <td class="px-5 py-3">
                    {{ user.formatted_updated_at }}
                  </td>
                  <td class="px-5 py-3">
                    <SecondaryButton :key="index" :disabled="isDisabled[index]?.value"
                      @click="resendVerificationEmail(user.id, index)" v-if="user.email_verified_at === null">
                      {{
                        isDisabled[index]?.value
                        ? `Wait for 10 seconds...`
                        : "Resend"
                      }}
                    </SecondaryButton>

                    <Link v-else :href="'users/edit/' + user.id">
                    <SecondaryButton>
                      Edit User Profile
                    </SecondaryButton>
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Laravel Vue Pagination -->
      <div class="grid place-items-center pt-5">
        <TailwindPagination :data="usersData" @pagination-change-page="getResults" :limit="1" :keepLength="true" />
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { TailwindPagination } from "laravel-vue-pagination";
import { ref, defineProps } from "vue";
import axios from "axios";
import { useToast } from "vue-toast-notification";
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
  api_token: String,
  user: {
    type: Object,
  },
  isDisabled: Boolean,
});

const nameFilter = ref("");
const statusFilter = ref("");
const usersData = ref({});
const isDisabled = ref([]);
const toast = useToast();

//clear selection
const clearFields = () => {
  nameFilter.value = '';
  statusFilter.value = '';
  getResults();
};

const getResults = async (page = 1) => {
  // Ensure that page is a valid numeric value, default to 1 if not
  // page = parseInt(page, 10) || 1;
  // fetch users per page
  const response = await fetch(
    `/api/${usePage().props.role}/users?page=${page}&name=${nameFilter.value}&is_active=${statusFilter.value}`
  );
  usersData.value = await response.json();
  const buttons = usersData.value.data.map((user) => user.id);
  isDisabled.value = await buttons.map(() => ref(false));
};

getResults();

// For resending verification email to the user & toast notification
const resendVerificationEmail = async (userId, index) => {
  const formData = new FormData();
  formData.append("user_id", userId);

  isDisabled.value[index].value = true; // Disable the button before sending the email
  const response = await axios.post("email/verification-notification", formData);

  if (response.status === 200) {
    toast.success("Email Sent Successfully!");
  }

  setTimeout(() => {
    isDisabled.value[index].value = false;
  }, 10000);
};

</script>

