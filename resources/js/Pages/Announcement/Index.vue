<template>
    <Head :title="'Announcements Page ' + announcementsData.current_page" />
    <DashboardLayout>
        <div class="px-5 sm:px-10 py-5">
            <div>
                <div v-if="$page.props.permissions.includes('create')">
                    <Link :href="route(`${$page.props.role}.create.announcement`)" class="float-right">
                    <SecondaryButton>
                        <span class="mx-4">Create Announcement</span>
                    </SecondaryButton>
                    </Link>
                </div>

                <h1 class="font-dm-sans text-5xl">Announcement Table</h1>
            </div>

            <form @submit.prevent="applyFilter">
                <div class="relative mt-4 bg-midnight-express px-5 py-3 rounded-tl-xl rounded-tr-xl">
                    <span class="font-dm-sans text-white text-lg font-bold">Filter Options</span>
                </div>

                <div class="relative items-center bg-hawkes-blue px-5 pt-3 pb-5 rounded-bl-xl rounded-br-xl">
                    <div class="flex">
                        <div class="w-4/5 mr-2">
                            <InputLabel>Search</InputLabel>
                            <div class="absolute left-8 top-19 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="h-4 w-4">
                                    <path fill-rule="evenodd"
                                        d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>

                            <input type="text" name="title_filter" v-model="filter.title_filter"
                                class="w-full bg-white pl-8 pr-3 py-[0.25rem] rounded-lg text-m border border-midnight-express-70 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow"
                                placeholder="Type search keyword/s here">
                        </div>

                        <div class="w-1/4 mr-2">
                            <InputLabel>Start Date</InputLabel>
                            <input type="date" name="start_date" v-model="filter.start_date"
                                class="w-full bg-white pl-3 pr-3 py-[0.25rem] rounded-lg text-m border border-midnight-express-70 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow">
                        </div>

                        <div class="w-1/4">
                            <InputLabel>End Date</InputLabel>
                            <input type="date" name="end_date" v-model="filter.end_date"
                                class="w-full bg-white pl-3 pr-3 py-[0.25rem] rounded-lg text-m border border-midnight-express-70 focus:outline-none focus:border-mountain-meadow focus:ring-mountain-meadow">
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
            </form>

            <div class="relative overflow-x-auto pt-7">
                <div>
                    <span class="text-2xl font-semibold leading-tight">Results</span>
                    <!-- <span class="text-md float-right"> Showing {{}}-{{  }} of {{  }} results</span> -->
                </div>

                <!-- announcements table -->
                <div class="py-4 overflow-x-auto">
                    <div
                        class="inline-block w-full border border-midnight-express border-opacity-30 border-b-0 rounded-tl-xl rounded-tr-xl overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-midnight-express text-ronchi">
                                <tr>
                                    <th scope="col" class="w-1/12 px-5 py-3 text-left text-sm"> ID </th>
                                    <th scope="col" class="w-1/3 px-5 py-3 text-left text-sm"> Title </th>
                                    <th scope="col" class="px-5 py-3 text-left text-sm"> Created by </th>
                                    <th scope="col" class="ppx-5 py-3 text-left text-sm"> Created at </th>
                                    <th scope=" col" class="px-5 py-3 text-left text-sm"> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- display announcements data per page -->
                                <template v-if="announcementsData.data && announcementsData.data.length === 0">
                                    <tr class="bg-white text-midnight-express even:bg-hawkes-blue text-sm text-left border-b border-midnight-express border-opacity-30">
                                        <td colspan="5" class="px-5 py-3 text-center">
                                            Nothing matches your search.
                                        </td>
                                    </tr>
                                </template>
                                <tr class="bg-white text-midnight-express even:bg-hawkes-blue text-sm text-left border-b border-midnight-express border-opacity-30"
                                    v-for="announcement in  announcementsData.data ">
                                    <th scope="row" class="px-5 py-3 font-medium whitespace-nowrap">
                                        {{ announcement.id }}
                                    </th>
                                    <td class="px-5 py-3">
                                        {{ announcement.title }}
                                    </td>
                                    <td class="px-5 py-3">
                                        {{ announcement.creator?.name ?? '' }}
                                    </td>
                                    <td class="px-5 py-3">
                                        {{ announcement.created_at_formatted }}
                                    </td>
                                    <td class="px-5 py-3" v-if="$page.props.role === 'admin' &&
                                        $page.props.permissions.includes('update')">
                                        <Link :href="route(`${$page.props.role}.edit.announcement`, {
                                            id:
                                                announcement.id
                                        })">
                                        <SecondaryButton>
                                            Edit Announcement
                                        </SecondaryButton>
                                        </Link>
                                    </td>
                                    <td class=" px-5 py-3" v-else-if="['user', 'admin'].includes($page.props.role)">
                                        <Link
                                            :href="route(`${$page.props.role}.view.announcement`, { id: announcement.id })">
                                        <SecondaryButton>
                                            View Announcement
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
                <TailwindPagination :data="announcementsData" @pagination-change-page="getResults" :limit="1"
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
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    api_token: String,
    permissions: Array,
});

const announcementsData = ref({});

const filter = ref({
    title_filter: "",
    start_date: "",
    end_date: "",
});

//clear selection
const clearFields = () => {
    filter.value.title_filter = '';
    filter.value.start_date = '';
    filter.value.end_date = '';
    getResults();
};


const getResults = async (page = 1) => {
    const queryParams = new URLSearchParams();
    queryParams.append('page', page);

    if (filter.value.title_filter) {
        queryParams.append('title_filter', filter.value.title_filter);
    }
    if (filter.value.start_date) {
        queryParams.append('start_date', filter.value.start_date);
    }
    if (filter.value.end_date) {
        queryParams.append('end_date', filter.value.end_date);
    }

    const response = await fetch(`/api/${usePage().props.role}/announcement?${queryParams.toString()}`);
    announcementsData.value = await response.json();
};

getResults();

const applyFilter = () => {
    getResults();
}

</script>
