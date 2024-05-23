<!-- Component for displaying Property Status -->
<template>
    <div class="">
        <apexchart
            v-if="series && series.length"
            type="bar"
            :options="options"
            :series="series"
        ></apexchart>
    </div>
</template>
<script setup>
import axios from "axios";
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const series = ref();
const options = ref();

axios
    .get(`/api/${usePage().props.role}/dashboard/props_status`)
    .then((response) => {
        const culturalProps = response.data;

        options.value = {
            chart: {
                type: "bar",
                offsetY: 15,
                // offsetX: 6,
                stacked: true,
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                    dataLabels: {
                        total: {
                            enabled: true,
                            offsetX: 0,
                            style: {
                                fontSize: "13px",
                                fontWeight: 500,
                                color: "#FDFDFD",
                            },
                        },
                    },
                },
            },
            stroke: {
                width: 1,
                colors: ["#fff"],
            },
            responsive: [
                {
                    breakpoint: 1536,
                    options: {
                        chart: {
                            width: 300, // Set width for breakpoint
                            height: 300, // Set height for breakpoint
                        },
                    },
                },
                {
                    breakpoint: 1550,
                    options: {
                        chart: {
                            width: 350, // Set width for breakpoint
                            height: 330, // Set height for breakpoint
                        },
                    },
                },
                {
                    breakpoint: 1750,
                    options: {
                        chart: {
                            width: 400, // Set width for breakpoint
                            height: 300, // Set height for breakpoint
                        },
                    },
                },
                {
                    breakpoint: 1970,
                    options: {
                        chart: {
                            width: 600, // Set width for breakpoint
                            height: 330, // Set height for breakpoint
                        },
                    },
                },
            ],
            title: {
                text: "Cultural Property Status",
                align: "center",
                style: {
                    fontFamily: "DM Sans, sans-serif",
                    color: "#FDFDFD",
                    fontSize: "20px",
                    cssClass: "apexcharts-yaxis-title",
                },
                offsetX: 40,
                // offsetY: 10,
            },
            xaxis: {
                categories: ["All", "Status"],
                labels: {
                    formatter: function (val) {
                        return val;
                    },
                    style: {
                        colors: ["#FFFFFF"], // Set font colors for 'All' and 'Status'
                    },
                },
            },
            yaxis: {
                title: {
                    text: undefined,
                },
                labels: {
                    style: {
                        colors: ["#FFFFFF"], // Set font colors for 'All' and 'Status'
                    },
                },
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val;
                    },
                },
            },
            fill: {
                opacity: 1,
            },
            legend: {
                fontFamily: "Public Sans, sans-serif",
                fontSize: "14px",
                position: "top",
                horizontalAlign: "left",
                offsetX: 25,
                labels: {
                    useSeriesColors: true,
                },
            },
            colors: ["#e8b552", "#14BE81", "#F59211", "#D6DCE9", "#468B7E"],
        };

        const cultural_values = [];
        for (let i = 0; i < 4; i++) {
            cultural_values.push(Math.floor(Math.random() * 10000) + 100);
        }

        series.value = [
            {
                name: "Total",
                data: [culturalProps["all_props"], 0],
            },
            {
                name: "Published",
                data: [0, culturalProps["published"]],
            },
            {
                name: "For Validation",
                data: [0, culturalProps["validation"]],
            },
            {
                name: "For Review",
                data: [0, culturalProps["review"]],
            },
            {
                name: "For Compliance",
                data: [0, culturalProps["compliance"]],
            },
        ];
    });
</script>
