<template>
  <v-container>
    <div>
      <v-btn
        class="mr-3 mb-3"
        v-for="activityType of activityTypesPlusAll"
        :key="activityType.id"
        rounded
        :color="filter == activityType.id ? 'primary' : 'grey'"
        dark
        @click="filter = activityType.id"
        >{{ activityType.name }}</v-btn
      >
    </div>

    <div>
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

    <v-row>
      <v-col
        :cols="12"
        :md="4"
        :lg="3"
        v-for="activity of activitiesFiltered"
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

<script>
import * as util from "../../util.js";

export default {
  props: {
    activities: Array,
    activityTypes: Array,
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
