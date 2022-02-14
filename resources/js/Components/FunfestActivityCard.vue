<template>
  <v-card class="activity-card">
    <v-img height="250" :src="activity.image"> </v-img>

    <div class="d-flex">
      <div class="ticket-box" :class="color">
        <div class="ticket-box-text">
          <div class="ticket-box-top">
            <v-icon>mdi-ticket</v-icon>
            <span class="ticket-box-count">{{ this.activity.tickets }}</span>
          </div>
          {{ this.activity.completions.length }} out of
          {{ this.activity.limit }}
        </div>
      </div>
      <div>
        <v-card-title>{{ activity.name }}</v-card-title>

        <v-card-text>{{ activity.excerpt }}</v-card-text>
      </div>
    </div>
  </v-card>
</template>

<script>
import * as util from "../util.js";

export default {
  computed: {
    activityState() {
      return util.getActivityState(this.activity);
    },

    color() {
      if (this.activityState.state == "incomplete") {
        return "primary";
      } else if (this.activityState.state == "partial") {
        return "warning";
      } else if (this.activityState.state == "complete") {
        return "success";
      }
    },
  },

  props: {
    activity: Object,
  },
};
</script>
