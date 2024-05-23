<!-- Component for displaying Total Property per Region -->
<template>
  <div class="">
    <!-- render Treemap for total properties per region  -->
    <apexchart v-if="series && series.length" type="treemap" :options="options" :series="series"></apexchart>
  </div>
</template>
<style scoped>
  .apexcharts-treemap {
    display: flex !important;
    justify-content: center !important;
  }

</style>

<script setup>
import axios from 'axios';
import { ref } from 'vue';
import { usePage } from "@inertiajs/vue3";

let series = ref();
let options = ref();

axios.get(`/api/${usePage().props.role}/dashboard/region_props`).then((response) => {
  const regData = response.data;

  //console.log(response.data);

  options.value = {
    labels: {
      show: false
    },
    chart: {
      type: 'treemap',
      offsetY: 15,
      offsetX: 6,
      toolbar: {
        show: false,
      },
    },
    plotOptions: {
      treemap: {
        enableShades: false,
        shadeIntensity: 0.5,
        reverseNegativeShade: true,
        distributed: true,
        colorScale: {
          ranges: [
            {
              from: 0,
              to: 10,
              color: '#14BE81'
            },
            {
              from: 11,
              to: 20,
              color: '#F59211'
            },
            {
              from: 21,
              to: 30,
              color: '#D6DCE9'
            },
          ],
          // inverse: false,
          min: 0,
          max: 1000,
        },
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
                        width: 400, // Set width for breakpoint
                        height: 300 // Set height for breakpoint
                    }
                }
            },
            {
                breakpoint: 1970,
                options: {
                    chart: {
                        width: 550, // Set width for breakpoint
                        height: 330 // Set height for breakpoint
                    }
                }
            },
        ],
    title: {
      text: 'Total Property per Region',
      // margin: 0,
      style: {
        fontFamily: 'DM Sans, sans-serif',
        color: '#FDFDFD',
        fontSize: '20px',
        cssClass: 'apexcharts-yaxis-title',
      },
      align: 'top',
      offsetX: 0,
      offsetY: 10,
    },
    dataLabels: {
      enabled: true, 
      style:{
        color: '#FDFDFD',
      },
      
    }
  }

   // extract relevant data from regData and create series array
  const seriesData = [];
    for (const region in regData) {
      if (regData[region] > 0) {
        seriesData.push({
          x: region,
          y: regData[region],
        });
      }
  }

  // assign series data to the series property
  series.value = [
    {
      data: seriesData,
    },
  ];
});
</script>