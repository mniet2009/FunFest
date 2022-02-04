<template>
  <v-card class="activity-card">
    <v-img height="250" :src="activity.image">
      <div class="tickets" :class="{ complete: this.completion }">
        <v-icon>mdi-star</v-icon>
        {{ getTickets() }}
        /
        {{ activity.tickets }}
      </div>
    </v-img>

    <v-card-title>{{ activity.name }}</v-card-title>

    <v-card-text>{{ activity.description }}</v-card-text>
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
  methods: {
    getTickets() {
      return this.completion ? this.activity.tickets : 0;
    },
  },

  props: {
    completion: Object,
    activity: Object,
  },
};
</script>
