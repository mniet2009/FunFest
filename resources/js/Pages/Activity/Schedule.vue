<template>
  <div>
    <Head title="Schedule" />

    <v-parallax
      style="height: 300px"
      dark
      src="/img/schedule.jpg"
      class="d-flex align-center justify-center top-image"
    >
      <h1>Activity Schedule</h1>
    </v-parallax>

    <v-container>
      Fuck this page. fix it before release
      <v-simple-table>
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
            class="pointer"
            v-ripple
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
