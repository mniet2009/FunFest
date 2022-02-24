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
                v-for="completion in team.completions"
                :key="completion.user.username"
                :href="route('users.show', completion.user)"
                class="pointer"
                v-ripple
              >
                <td>
                  <user-avatar
                    :url="completion.user.id"
                    :username="completion.user.username"
                  ></user-avatar>
                </td>
                <td class="text-right">
                  {{ formatNumber(completion.tickets) }}
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
  },
};
</script>
