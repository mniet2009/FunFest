<template>
  <div style="height: 300px">
    <canvas ref="chart"></canvas>
  </div>
</template>

<script>
import { Chart, registerables } from "chart.js";
import "chartjs-adapter-moment";

Chart.register(...registerables);
Chart.defaults.backgroundColor = "rgba(0, 0, 0, 0)";
Chart.defaults.borderColor = "rgba(255, 255, 255, 0.1)";
Chart.defaults.color = "rgba(255, 255, 255, 1)";

export default {
  props: {
    chartData: Object,
  },

  mounted() {
    let ctx = this.$refs.chart.getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: this.chartData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
      },
    });
  },
};
</script>
