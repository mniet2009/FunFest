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
      <v-row>
        <v-col
          v-for="activity of activities"
          :key="activity.id"
          :cols="12"
          :md="6"
          :lg="6"
          :xl="3"
        >
          <router-link
            as="div"
            class="pointer"
            :href="route('activities.show', activity)"
          >
            <v-card>
              <v-img
                :src="activity.image"
                class="white--text align-end"
                gradient="to bottom, rgba(0,0,0,0), rgba(0,0,0,.5)"
                height="400px"
              >
                <v-card-title v-text="activity.name"></v-card-title>
              </v-img>

              <v-card-text class="text-center">
                <local-datetime
                  :t="activity.event_at"
                  class="text-h6"
                ></local-datetime>

                <CountdownSimple
                  class="mx-auto"
                  :endDate="activity.event_at"
                  style="font-size: 0.8em"
                ></CountdownSimple>
              </v-card-text>
            </v-card>
          </router-link>
        </v-col>
      </v-row>
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
