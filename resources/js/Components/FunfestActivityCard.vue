<template>
  <v-card class="activity-card">
    <v-img height="250" :src="activity.image">
      <div
        class="tickets"
        :class="{
          complete:
            this.completions.length == this.activity.limit ||
            (this.completions.length == 1 && !this.activity.limit),
        }"
      >
        <v-icon>mdi-ticket</v-icon>

        <span
          v-if="this.completions.length == 0 && this.activity.limit == null"
        >
          Redeem for {{ activity.tickets }} tickets
        </span>

        <span
          v-if="this.completions.length == 0 && this.activity.limit != null"
        >
          Redeem for {{ activity.tickets }} tickets, up to
          {{ this.activity.limit }} times
        </span>

        <span v-if="this.completions.length > 0 && this.activity.limit != null">
          Completed {{ this.completions.length }} /
          {{ this.activity.limit }} times, {{ activity.tickets }} tickets each
        </span>

        <span v-if="this.completions.length > 0 && this.activity.limit == null">
          Completed
        </span>
      </div>
    </v-img>

    <v-card-title>{{ activity.name }}</v-card-title>

    <v-card-text>{{ activity.excerpt }}</v-card-text>
  </v-card>
</template>

<style scoped lang="scss">
.tickets {
  display: inline-block;
  padding: 5px 10px;
  background-color: rgba(0, 0, 0, 0.8);
  border-bottom-right-radius: 5px;

  &.complete {
    background-color: rgba(0, 190, 190, 0.8);
  }
}
</style>

<script>
export default {
  props: {
    completions: Array,
    activity: Object,
  },
};
</script>
