<!-- Component for displaying:
    * Number of Submitted LGUs
    * Submission Rate (%)
    * Total Number of LGUs in the Philippines (Provinces, Cities, Towns)
    * Number of LGUs Awaiting Compliance
-->
<template>
    <!-- <div class="flex items-center justify-start my-8 gap-5"> -->
    <div class="flex flex-col gap-y-4 mt-5">
        <!-- <div class="mt-5 text-xl font-semibold text-midnight-express leading-4"> Quick Statistics </div> -->

        <!-- Number of submitted LGUs -->
        <div class="xl:h-40 2xl:h-44 max-w-md rounded-xl overflow-hidden bg-ronchi border border-midnight-express border-opacity-50">
            <div class="flex m-auto h-44 py-10 px-5">
                <!-- <div v-if="lguData" class="w-full flex justify-end font-bold text-7xl mr-5">{{ lguData.submitted_lgu }}</div> -->
                <div v-if="lguData" class="w-full flex justify-end font-bold text-7xl mr-3 py-5">{{ lguData.lgu_to_comply }}</div>
                <p class="w-11/12 text-midnight-express text-xl">
                    Number of <span class="font-semibold">Submitted LGUs</span>
                </p>
            </div>
        </div>

        <!-- Submission Rate -->
        <div class="xl:h-40 2xl:h-44 max-w-md rounded-xl overflow-hidden bg-carrot-orange border border-midnight-express border-opacity-50">
            <div class="flex m-auto h-44 py-10 px-5">
                <div v-if="lguData" class="w-full flex justify-end font-bold text-5xl mr-3 py-4"><span>{{ lguData.submission_rate }}%</span></div>
                <p class="w-11/12 text-midnight-express text-2xl py-2">
                    <span class="font-semibold">Submission Rate (%)</span>
                </p>
            </div>
        </div>

        <!-- Total Number of LGUs in the Philippines (Provinces, Cities, Towns) -->
        <div class="xl:h-40 2xl:h-44 max-w-md rounded-xl overflow-hidden bg-mountain-meadow border border-midnight-express border-opacity-50">
            <div class="flex m-auto h-44 py-5 px-5">
                <div v-if="lguData" class="w-full flex justify-end font-bold text-7xl mr-3 py-5">{{ lguData.total_lgu }}</div>
                <p class="w-11/12 text-midnight-express text-base py-2">
                    Total Number of <span class="font-semibold">LGUs in the Philippines</span> (Provinces, Cities, Towns)
                </p>
            </div>
        </div>

        <!-- Number of LGUs Awaiting Compliance -->
        <div class="xl:h-40 2xl:h-44 max-w-md rounded-xl overflow-hidden bg-hawkes-blue border border-midnight-express border-opacity-50">
            <div class="flex m-auto h-44 py-7 px-5">
                <!-- <div v-if="lguData" class="w-full flex justify-end font-bold text-7xl mr-3 py-5">{{ lguData.lgu_to_comply }}</div> -->
                <div v-if="lguData" class="w-full flex justify-end font-bold text-7xl mr-5">{{ lguData.submitted_lgu }}</div>
                <p class="w-11/12 text-midnight-express text-xl">
                    Number of <span class="font-semibold">LGUs Awaiting Compliance</span>
                </p>
            </div>
        </div>
    </div>
</template>
<script setup>
import axios from 'axios';
import { ref } from 'vue';
import { usePage } from "@inertiajs/vue3";

const lguData = ref();

axios.get(`/api/${usePage().props.role}/dashboard/lgus`).then((response) => {
    lguData.value = response.data;

    console.log();
});

</script>