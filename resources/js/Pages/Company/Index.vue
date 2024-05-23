<template>
    <Head :title="'Company Page ' + companyData.current_page" />
    <DashboardLayout>
        <div class="px-5 sm:px-10 py-5">
            <div>
                <Link :href="route(`${$page.props.role}.create.company`)" class="float-right">
                <SecondaryButton v-if="`${$page.props.permissions}`.includes('create')">
                    <span class="mx-4">Create Relevant Interested Party</span>
                </SecondaryButton>
                </Link>

                <h1 class="font-dm-sans text-5xl">Relevant Interested Party</h1>
            </div>

            <form @submit.prevent="applyFilter">
                <div class="relative mt-4 bg-midnight-express px-5 py-3 rounded-tl-xl rounded-tr-xl">
                    <span class="font-dm-sans text-white text-lg font-bold">Filter Options</span>
                </div>

                <div class="flex relative bg-hawkes-blue px-5 pt-3 pb-5 rounded-bl-xl rounded-br-xl">
                    <div class="absolute left-8 top-0 mt-6">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path fill-rule="evenodd"
                                d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    <input type="search" name="title_filter" v-model="filter.company_filter"
                        class="w-full bg-white pl-8 pr-3 py-[0.25rem] rounded-lg text-m border border-midnight-express-70 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow"
                        placeholder="Type search keyword/s here" aria-label="Search" aria-describedby="button-addon1" />

                    <!--Search button-->
                    <FormButton class="ml-5">
                        Search
                    </FormButton>
                </div>

                <!-- <div>
                    <input type="text" name="title_filter" v-model="filter.company_filter">
                </div>
                <div class="px-1 py-3">
                    <button class="bg-blue-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Apply Filter</button>
                </div> -->
            </form>

            <div class="relative overflow-x-auto pt-7">
                <div>
                    <span class="text-2xl font-semibold leading-tight">Results</span>
                    <!-- <span class="text-md float-right"> Showing {{}}-{{  }} of {{  }} results</span> -->
                </div>
            </div>

            <!-- announcements table -->
            <div class="py-4 overflow-x-auto">
                <div
                    class="inline-block w-full border border-midnight-express border-opacity-30 border-b-0 rounded-tl-xl rounded-tr-xl overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-midnight-express text-ronchi">
                            <tr>
                                <th scope="col" class="w-1/12 px-5 py-3 text-left text-sm"> ID </th>
                                <th scope="col" class="w-1/2 px-5 py-3 text-left text-sm"> Relevant Interested Party </th>
                                <th scope="col" class="w-1/5 px-5 py-3 text-left text-sm"> Created at </th>
                                <th scope="col" class="w-1/6 px-5 py-3 text-left text-sm"> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- display announcements data per page -->
                            <tr v-if="companyData.data && companyData.data.length === 0"
                            class="bg-white text-midnight-express even:bg-hawkes-blue text-sm text-left border-b border-midnight-express border-opacity-30">
                            <td colspan="10" class="px-5 py-3 text-center">
                                Nothing matches your search.
                                </td>
                            </tr>                              
                            <tr class="bg-white text-midnight-express even:bg-hawkes-blue text-sm text-left border-b border-midnight-express border-opacity-30"
                                v-for=" company  in  companyData.data ">
                                <th scope="row" class="px-5 py-3 font-medium whitespace-nowrap">
                                    {{ company.id }}
                                </th>
                                <td class="px-5 py-3">
                                    {{ company.name }}
                                </td>
                                <!-- 
                                <td class="px-6 py-4">
                                    {{ company.officeAddress }}
                                </td>
                                -->
                                <td class="px-5 py-3">
                                    {{ company.created_at_formatted }}
                                </td>
                                <td class="px-5 py-3">
                                    <Link :href="route(`${$page.props.role}.edit.company`, { id: company.id })">
                                    <SecondaryButton v-if="`${$page.props.permissions}`.includes('update')">
                                        Edit
                                    </SecondaryButton>
                                    <SecondaryButton v-else>View Company</SecondaryButton>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Laravel Vue Pagination -->
            <div class="grid place-items-center pt-5">
                <TailwindPagination :data="companyData" @pagination-change-page="getResults" :limit="1"
                    :keepLength="true" />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { TailwindPagination } from 'laravel-vue-pagination';
import { ref } from 'vue';
import { defineProps } from "vue";
import SecondaryButton from '@/Components/SecondaryButton.vue';
import FormButton from '@/Components/FormButton.vue';

const props = defineProps({
    api_token: String,
    permissions: Array,
});

const companyData = ref({});
const filter = ref({
    company_filter: ''
});

const getResults = async (page = 1) => {
    //fetch announcements per page
    const queryParams = new URLSearchParams();
    queryParams.append('page', page);

    if (filter.value.company_filter) {
        queryParams.append('company_filter', filter.value.company_filter);
    }
    const response = await fetch(`/api/${usePage().props.role}/company?${queryParams.toString()}`);
    companyData.value = await response.json();
};

getResults();

const applyFilter = () => {
    getResults();
}

</script>
