<template>
    <DashboardLayout>
        <div class="px-5 sm:px-10 py-5 overflow-x-auto">
            <h1 class="font-dm-sans text-5xl">Cultural Inventory Status Report</h1>
            <p class="mt-2 text-lg font-normal italic text-midnight-express leading-4"> as of {{ currentDate }} </p>
            <!-- Component for displaying:
                * Number of Submitted LGUs
                * Submission Rate (%)
                * Total Number of LGUs in the Philippines (Provinces, Cities, Towns)
                * Number of LGUs Awaiting Compliance
             -->
             <div class="flex">
                <div class="flex flex-col h-fit">
                    <p class="mt-5 text-xl font-semibold text-midnight-express leading-4"> Quick Statistics </p>
                    <SubmittedLGU  />
                </div>

                <div class="flex flex-col ml-10 w-full"> 
                    <p class="my-5 text-xl font-semibold text-midnight-express leading-4">Graphs</p>
                    <div class="flex bg-midnight-express rounded-xl overflow-auto ">
                    
                        <div class="max-2xl:w-1/2 w-2/4">
                            <!-- Component for displaying Number of Users -->
                            <UserCount  class="flex justify-center items-center" />
                            <!-- Component for displaying Cumulative Number of Submission of Local Cultural Inventories -->
                            <CumulativeInventory class="flex justify-center items-center my-8" />
                        </div>
                
                        <div class="max-2xl:w-1/2 w-3/4"> 
                            <!-- Component for displaying Total Property per Region -->
                            <RegionProperty  class="flex max-2xl:justify-start max-2xl:items-start justify-center items-center" />                             
                            <!-- Component for displaying Property Status -->
                            <CulturalProperties class=" my-8" /> 
                        </div>
                    </div>
             </div>
                
                </div>
                
                
            
        </div>
    </DashboardLayout>
</template>

<script>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import CumulativeInventory from '@/Components/CumulativeInventory.vue';
import RegionProperty from '@/Components/RegionProperty.vue';
import CulturalProperties from '@/Components/CulturalProperties.vue';
import UserCount from '@/Components/UserCount.vue';
import SubmittedLGU from '@/Components/SubmittedLGU.vue';

export default {
    data() {
        return {
        currentDate: this.getCurrentDate(),
        };
    },
    methods: {
        getCurrentDate() {
            const now = new Date();
            const options = { month: 'long', day: 'numeric', year: 'numeric'};
            return now.toLocaleString(undefined, options);
        },
        updateDate() {
            this.currentDate = this.getCurrentDate();
        },
    },
    mounted() {
        // Update the date every day
        setInterval(this.updateDate, 86400000);
    },
    components: {
        DashboardLayout,
        CumulativeInventory,
        RegionProperty,
        CulturalProperties,
        UserCount,
        SubmittedLGU,
    },
};

</script>