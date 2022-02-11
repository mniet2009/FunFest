<template>
  <v-container>
    <h1>Activites wow fun</h1>
    <v-row>
      <v-col
        :cols="12"
        :md="4"
        :lg="3"
        v-for="activity of activities"
        :key="activity.id"
      >
        <router-link
          :href="route('activities.show', { activity: activity })"
          as="div"
        >
          <funfest-activity-card :activity="activity"></funfest-activity-card>
        </router-link>
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped lang="scss">
.activity-card {
  transition: transform 0.3s, box-shadow 0.3s;
  transform-origin: 100% 100%;

  &:hover {
    cursor: pointer;
    transform: translateY(-1em) rotate(2deg);
    box-shadow: 0 5px 10px 2px rgba(0, 190, 190, 0.8);
  }
}
</style>

<script>
export default {
  props: {
    activities: Array,
  },
};
</script>
