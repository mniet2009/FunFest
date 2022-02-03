<template>
  <v-card class="activity-card">
    <v-img height="250" :src="activity.image"></v-img>

    <v-card-title>{{ activity.name }}</v-card-title>

    <v-card-text>{{ activity.description }}</v-card-text>
  </v-card>
</template>

<script>
export default {
  props: {
    activity: Object,
  },
};
</script>
