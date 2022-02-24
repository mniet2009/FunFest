<template>
  <div>
    <div v-if="countdownActive" class="d-flex flex-wrap justify-center my-6">
      <div
        v-for="(value, i) in countdown"
        :key="i"
        class="d-flex flex-column align-center mr-3"
      >
        <Flip :value="value.toString().padStart(2, 0)"></Flip>

        {{ units[i] }}
      </div>
    </div>

    <v-simple-table>
      <thead>
        <tr>
          <th>Rank</th>
          <th>Player</th>
          <th>Tickets</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(completion, i) in activity.completions" :key="i">
          <td>{{ completion.placement }}</td>
          <td>
            <user-avatar
              :url="completion.user.id"
              :username="completion.user.username"
              :color="$page.props.teams[completion.user.team_id - 1].color"
            ></user-avatar>
          </td>
          <td>{{ completion.tickets }}</td>
        </tr>
      </tbody>
    </v-simple-table>
  </div>
</template>

<script>
import Tick from "@pqina/tick";

export default {
  created() {
    let counter = Tick.count.down(this.activity.event_at);

    counter.onupdate = (value) => {
      this.countdown = value;
    };
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
    return {
      units: ["Days", "Hours", "Minutes", "Seconds"],
      countdown: 0,
      counter: null,
    };
  },
};
</script>
