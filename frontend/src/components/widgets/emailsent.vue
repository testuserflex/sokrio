<script>
import Loader from "./loader";

/**
 * Email-sent component
 */
export default {
  components: {
    Loader,
  },
  data() {
    return {
      isActive: "year",
      series: [
        {
          name: "Products",
          data: [11, 17, 15, 15, 21, 14, 11, 18, 17, 12, 20, 18],
        },
      ],
      chartOptions: {
        chart: {
          stacked: true,
          toolbar: {
            show: false,
          },
          zoom: {
            enabled: true,
          },
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: "15%",
            endingShape: "rounded",
          },
        },
        dataLabels: {
          enabled: false,
        },
        xaxis: {
          categories: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
        },
        colors: ["#556ee6", "#556ee6", "#556ee6"],
        legend: {
          position: "bottom",
        },
        fill: {
          opacity: 1,
        },
      },
    };
  },
  props: {
    updating: {
      type: Boolean,
    },
  },
  methods: {
  },
};
</script>
<template>
  <Loader :loading="updating">
    <div class="card">
      <div class="card-body">
        <div class="d-sm-flex flex-wrap">
          <h4 class="card-title mb-4">Most Demanded Products</h4>
        </div>

        <apexchart
          class="apex-charts"
          type="bar"
          dir="ltr"
          height="360"
          :series="series"
          :options="chartOptions"
        ></apexchart>
      </div>
    </div>
  </Loader>
</template>
