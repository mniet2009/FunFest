<template>
  <div>
    <Head title="Schedule" />

    <v-parallax
      :height="300"
      dark
      src="/img/standings.jpg"
      class="d-flex align-center justify-center top-image"
    >
      <h1>Activity Schedule</h1>
    </v-parallax>

    <v-container>
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
            <td><local-datetime :t="activity.event_at"></local-datetime></td>
          </router-link>
        </tbody>
      </v-simple-table>
    </v-container>
  </div>
</template>

<script>
export default {
  props: {
    activities: Array,
  },
};
</script>
