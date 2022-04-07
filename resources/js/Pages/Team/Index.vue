<template>
  <div>
    <Head title="Standings" />

    <v-parallax
      style="height: 300px;"
      dark
      src="/img/standings.jpg"
      class="d-flex align-center justify-center top-image"
    >
      <h1>Standings</h1>
    </v-parallax>
    <ActivityChart :chartData="chartData" class="pa-4" />

    <v-container>
      <v-row>
        <v-col :cols="6" v-for="team of teams" :key="team.id">
          <div class="team-top text-center" :style="{ background: team.color }">
            <h1>{{ team.name }}</h1>
            <h2>{{ formatNumber(team.completions_sum_tickets) }}</h2>
          </div>

          <v-simple-table>
            <thead>
              <tr>
                <th class="text-left">
                  Player
                </th>
                <th class="text-right">
                  Tickets
                </th>
              </tr>
            </thead>
            <tbody>
              <router-link
                as="tr"
                v-for="user in team.users"
                :key="user.username"
                :href="route('users.show', user)"
                class="pointer"
                v-ripple
              >
                <td>
                  <user-avatar
                    :url="user.id"
                    :username="user.username"
                  ></user-avatar>
                </td>
                <td class="text-right">
                  {{ formatNumber(user.completions_sum_tickets) }}
                </td>
              </router-link>
            </tbody>
          </v-simple-table>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<style scoped lang="scss">
.container {
  max-width: 700px !important;
}
.team-top {
  padding: 1rem;
  border-radius: 0.5em 0.5em 0 0;
  background-color: #fff;
}
</style>

<script>
import * as util from "../../util.js";

export default {
  methods: {
    formatNumber: util.formatNumber,
  },

  props: {
    teams: Array,
    activities: Array,
  },

  computed: {
    chartData() {
      let chartData = {
        labels: [],
        datasets: [],
      };

      for (let team of this.teams) {
        chartData.datasets.push({
          label: team.name,
          backgroundColor: team.color,
          data: [],
        });
      }

      // add up teams points for activities
      for (let activity of this.activities) {
        let points = [];
        for (let team of this.teams) {
          points[team.id] = 0;
        }

        for (let user of activity.users) {
          points[user.team_id] += parseInt(user.completions_sum_tickets);
        }

        chartData.labels.push(activity.name);

        for (let team of this.teams) {
          chartData.datasets[team.id - 1].data.push(points[team.id]);
        }
      }

      return chartData;
    },
  },
};
</script>
