<template>
  <v-container>
    <v-row>
      <v-col :cols="6" v-for="team of teams" :key="team.id">
        <div class="team-top text-center" :style="{ background: team.color }">
          <h1>{{ team.name }}</h1>
          <h2>{{ formatNumber(team.completions_sum_tickets) }}</h2>
        </div>

        <v-simple-table>
          <template v-slot:default>
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
              <tr
                v-for="completion in team.completions"
                :key="completion.user.username"
              >
                <td>{{ completion.user.username }}</td>
                <td class="text-right">
                  {{ formatNumber(completion.tickets) }}
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-col>
    </v-row>
  </v-container>
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
export default {
  methods: {
    formatNumber(number) {
      return new Intl.NumberFormat("en-US", {}).format(number);
    },
  },

  props: {
    teams: Array,
  },
};
</script>
