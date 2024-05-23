<!-- Component for displaying Cumulative Number of Submission of Local Cultural Inventories -->
<template>
    <div class="">
        <!-- render donut chart to display LGUs that submitted/not submitted to NCCA  -->
        <apexchart type="donut" :options="options" :series="series"></apexchart>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref } from 'vue';
import { usePage } from "@inertiajs/vue3";

const series = ref();

axios.get(`/api/${usePage().props.role}/dashboard/lgus`).then((response) => {
    const data = response.data;

    series.value = [data['submitted_lgu'], data['total_lgu']];
});


const options = {
    chart: {
        type: 'donut',
    },
    plotOptions: {
        pie: {
            donut: {
                size: '85%',
            }
        }
    },
    responsive: [
        {
            breakpoint: 1536,
            options: {
                chart: {
                    width: 300, // Set width for breakpoint
                    height: 300 // Set height for breakpoint
                }
            }
        },
        {
            breakpoint: 1550,
            options: {
                chart: {
                    width: 350, // Set width for breakpoint
                    height: 330 // Set height for breakpoint
                }
            }
        },
        {
            breakpoint: 1750,
            options: {
                chart: {
                    width: 350, // Set width for breakpoint
                    height: 300 // Set height for breakpoint
                },
            }
        },
        {
            breakpoint: 1970,
            options: {
                chart: {
                    width: 430, // Set width for breakpoint
                    height: 350 // Set height for breakpoint
                }
            }
        },
    ],
    legend: {
        position: 'bottom',
        fontFamily: 'Public Sans, sans-serif',
        labels: {
            useSeriesColors: true,
        },
    },
    colors: ['#E8B552', '#D6DCE9'],
    labels: ['Submitted to NCCA', 'No Submission to NCCA'],
    title: {
        text: ['Cumulative Number of Submission', 'of Local Cultural Inventories'],
        offsetX: 0,
        offsetY: 0,
        align: 'center',
        style: {
            fontFamily: 'DM Sans, sans-serif',
            color: '#FDFDFD',
            fontSize: '20px',
            cssClass: 'apexcharts-yaxis-title'
        }
    },
};

</script>