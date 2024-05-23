<!-- Component for displaying Number of Users -->
<template>
    <div>
        <!-- render Radial Bar to display number of all, verified, and active users -->
        <apexchart v-if="series && series.length" type="radialBar" :options="options" :series="series"></apexchart>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref } from 'vue';
import { usePage } from "@inertiajs/vue3";

let series = ref();
let options = ref();

// request all, verified, and active users count 
axios.get(`/api/${usePage().props.role}/dashboard/users_count`).then((response) => {
    
    let usersArr = [];
    usersArr.push(response.data['all']);
    usersArr.push(response.data['verified'] / response.data['all'] * 100);
    usersArr.push(response.data['active'] / response.data['all'] * 100);
    series.value = usersArr;

    const totalUsers = response.data['all'];

    options = {
        chart: {
            type: 'radialBar',
        },
        plotOptions: {
            radialBar: {
                offsetY: 0,
                startAngle: 0,
                endAngle: 360,
                hollow: {
                    margin: 5,
                    size: '40%',
                    background: 'transparent',
                    image: undefined,
                },
                dataLabels: {
                    name: {
                        show: false,
                    },
                    value: {
                        show: true,
                        style: {
                            color: '#FDFDFD',
                        },
                        formatter: function (val) {
                            // show the percentage of all/verified/active users  
                            if (val == totalUsers) {
                                return 100 + "%"
                            }
                            return (Number(val)).toFixed(1) + "%"
                        }
                    }
                }
            }
        },
        colors: ['#14BE81', '#F59211', '#E8B552'],
        labels: ['All', 'Verified', 'Active'],
        legend: {
            fontFamily: 'Public Sans, sans-serif',
            show: true,
            floating: true,
            fontSize: '14px',
            position: 'bottom',
            offsetX: 0,
            offsetY: 0,
            labels: {
                useSeriesColors: true,
            },
            markers: {
                size: 0
            },
            formatter: function (seriesName, opts) {
                // show the number of all/verified/active users  
                if (opts.w.globals.series[opts.seriesIndex] == totalUsers) {
                    return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
                }
                return seriesName + ":  " + Math.ceil(opts.w.globals.series[opts.seriesIndex] * totalUsers / 100)
            },
            itemMargin: {
                vertical: 3
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
                breakpoint: 1699,
                options: {
                    chart: {
                        width: 350, // Set width for breakpoint
                        height: 330 // Set height for breakpoint
                    }
                }
            },
            {
                breakpoint: 1700,
                options: {
                    chart: {
                        width: 350, // Set width for breakpoint
                        height: 300 // Set height for breakpoint
                    }
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
        title: {
            text: 'Number of Users',
            offsetX: 0,
            offsetY: 25,
            align: 'top',
            style: {
                fontFamily: 'DM Sans, sans-serif',
                color: '#FDFDFD',
                fontSize: '20px',
                cssClass: 'apexcharts-yaxis-title'
            }
        },
    };
});
</script>