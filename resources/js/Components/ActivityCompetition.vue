<template>
  <div>
    <h1>Countdown goes here</h1>
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
              :url="completion.user.avatar"
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
export default {
  props: {
    activity: Object,
  },

  data() {
    return {};
  },
};
</script>
