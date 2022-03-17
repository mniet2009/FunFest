<template>
  <div>
    <Countdown v-if="countdownActive" :endDate="activity.event_at"></Countdown>

    <v-card v-else>
      <v-card-title>
        Results
        <v-btn
          class="ml-4"
          v-if="$page.props.can['assign points']"
          :to="route('activities.pointsForm', activity)"
          color="primary"
        >
          <v-icon>mdi-crown</v-icon> Assign Points
        </v-btn>
      </v-card-title>

      <v-simple-table>
        <thead>
          <tr>
            <th>Rank</th>
            <th>Player</th>
            <th>Tickets</th>
          </tr>
        </thead>
        <tbody>
          <router-link
            as="tr"
            v-for="(completion, i) in activity.completions"
            :key="i"
            :href="route('users.show', completion.user)"
            class="pointer"
            v-ripple
          >
            <td>{{ ordinal_number(completion.placement) }}</td>
            <td>
              <user-avatar
                :url="completion.user.id"
                :username="completion.user.username"
                :color="$page.props.teams[completion.user.team_id - 1].color"
              ></user-avatar>
            </td>
            <td>{{ completion.tickets }}</td>
          </router-link>
        </tbody>
      </v-simple-table>
    </v-card>
  </div>
</template>

<script>
import * as util from "../util.js";

export default {
  methods: {
    ordinal_number: util.ordinal_number,
  },

  computed: {
    countdownActive() {
      return (
        this.activity.event_at && new Date(this.activity.event_at) > Date.now()
      );
    },
  },

  props: {
    activity: Object,
  },

  data() {
    return {};
  },
};
</script>
