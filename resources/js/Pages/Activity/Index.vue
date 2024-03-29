<template>
  <div>
    <Head :title="heading" />

    <v-parallax
      style="height: 300px;"
      dark
      src="/img/activities.jpg"
      class="d-flex align-center justify-center top-image"
    >
      <h1>{{ heading }}</h1>
    </v-parallax>

    <v-container>
      <div class="d-flex justify-center flex-wrap">
        <v-btn
          class="mr-3 mb-3"
          v-for="activityType of activityTypesPlusAll"
          :key="activityType.id"
          rounded
          :color="filter == activityType.id ? 'primary' : 'grey'"
          dark
          @click="filter = activityType.id"
          ><v-icon v-if="activityType.icon" class="mr-2">{{
            activityType.icon
          }}</v-icon>
          {{ activityType.name }}</v-btn
        >
      </div>

      <div class="d-flex justify-center flex-wrap" v-if="$page.props.auth.user">
        <v-btn
          class="mr-3 mb-3"
          v-for="filter of completionFilters"
          :key="filter"
          rounded
          :color="filter == completionFilter ? 'primary' : 'grey'"
          dark
          @click="completionFilter = filter"
          >{{ filter }}</v-btn
        >
      </div>

      <v-row class="align-stretch">
        <v-col
          :cols="12"
          :md="6"
          :lg="4"
          :xl="3"
          v-for="activity of activitiesFiltered"
          :key="activity.id"
        >
          <router-link
            :href="route('activities.show', { activity: activity })"
            as="div"
            style="height: 100%"
          >
            <funfest-activity-card
              :activity="activity"
              style="height: 100%"
            ></funfest-activity-card>
          </router-link>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import * as util from "../../util.js";

export default {
  remember: ["filter", "completionFilter"],

  props: {
    activities: Array,
    activityTypes: Array,
    user: Object,
  },

  computed: {
    activityTypesPlusAll() {
      return [{ id: 0, name: "All" }].concat(this.activityTypes);
    },

    activitiesFiltered() {
      let filtered = this.activities;

      if (this.filter != 0) {
        filtered = filtered.filter(
          (activity) => activity.activity_type_id == this.filter
        );
      }

      if (this.completionFilter != "All") {
        filtered = filtered.filter(
          (activity) =>
            util.getActivityState(activity).state ==
            this.completionFilter.toLowerCase()
        );
      }

      return filtered;
    },

    heading() {
      if (this.user) {
        return `${this.user.username}'s Activities`;
      } else {
        return "Activities";
      }
    },
  },

  data() {
    return {
      filter: 0,
      completionFilter: "All",
      completionFilters: ["All", "Incomplete", "Partial", "Complete"],
    };
  },
};
</script>
