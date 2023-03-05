<template>
  <div style="height: 600px">
    <canvas ref="chart"></canvas>
  </div>
</template>

<script>
import * as util from "../util.js";
import { Chart, registerables } from "chart.js";
import "chartjs-adapter-moment";
import zoomPlugin from "chartjs-plugin-zoom";

Chart.register(zoomPlugin);
Chart.register(...registerables);
Chart.defaults.backgroundColor = "rgba(0, 0, 0, 0)";
Chart.defaults.borderColor = "rgba(255, 255, 255, 0.1)";
Chart.defaults.color = "rgba(255, 255, 255, 1)";

export default {
  props: {
    activity: Object,
    chartData: Object,
  },

  mounted() {
    let ctx = this.$refs.chart.getContext("2d");

    let labelCallback;
    let axisCallback;

    if (this.activity.leaderboard_type_id == 1) {
      labelCallback = (context) => util.formatNumber(context.raw.y);
      axisCallback = (value) => util.formatNumber(value);
    } else {
      labelCallback = (context) => util.formatTimeString(context.raw.y);
      axisCallback = (value) => util.formatTimeString(value);
    }

    new Chart(ctx, {
      type: "line",
      data: this.chartData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            type: "time",
            min: "2023-03-31 00:00:00",
            max: "2023-04-23 23:59:00",
            time: {
              tooltipFormat: "LLL dd",
            },
          },

          y: {
            ticks: {
              callback: axisCallback,
            },
          },
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: labelCallback,
              title: (context) => {
                return (
                  context[0].dataset.user.username + " " + context[0].label
                );
              },
            },
          },
          zoom: {
            pan: {
              enabled: true,
            },

            zoom: {
              wheel: {
                enabled: true,
              },
              pinch: {
                enabled: true,
              },
              mode: "xy",
            },
          },
        },
      },
      plugins: [
        {
          id: "customPlugin",
          beforeDraw: (chart) => {
            const datasets = chart.config.data.datasets;
            if (datasets) {
              const ctx = chart.ctx;

              ctx.save();
              ctx.fillStyle = "white";
              ctx.font = "400 12px Open Sans, sans-serif";

              for (let i = 0; i < datasets.length; i++) {
                const ds = datasets[i];
                const label = ds.label;
                const meta = chart.getDatasetMeta(i);

                let x = meta.data[meta.data.length - 1].x;
                let y = meta.data[meta.data.length - 1].y;

                ctx.fillText(label, x + 5, y + 1);
              }
              ctx.restore();
            }
          },
        },
      ],
    });
  },
};
</script>
