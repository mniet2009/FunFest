<template>
  <v-card>
    <v-card-title>Ticket distribution</v-card-title>
    <v-simple-table>
      <thead>
        <tr>
          <th>Rank</th>
          <th>Tickets</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(tickets, rank) in activity.leaderboard_tickets" :key="rank">
          <td>{{ ordinal_number(rank + 1) }}</td>
          <td>{{ tickets }}</td>
        </tr>
      </tbody>
    </v-simple-table>
  </v-card>
</template>

<script>
import * as util from "../util.js";

export default {
  methods: {
    ordinal_number: util.ordinal_number,
  },

  props: {
    activity: Object,
  },
};
</script>
