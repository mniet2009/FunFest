<template>
  <v-container>
    <h1>Activity Schedule</h1>

    <v-simple-table class="schedule-table">
      <thead>
        <tr>
          <th>Activity</th>
          <th>Time</th>
        </tr>
      </thead>

      <tbody>
        <router-link
          as="tr"
          v-for="activity of activities"
          :key="activity.id"
          :href="route('activities.show', activity)"
        >
          <td>{{ activity.name }}</td>
          <td>{{ new Date(activity.event_at).toLocaleString() }}</td>
        </router-link>
      </tbody>
    </v-simple-table>
  </v-container>
</template>

<script>
export default {
  props: {
    activities: Array,
  },
};
</script>
