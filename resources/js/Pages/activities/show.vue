<template>
  <div>
    <v-parallax
      :height="300"
      dark
      :src="activity.image"
      class="d-flex align-center justify-center"
    >
      <!-- <v-sheet color="rgba(0, 0, 0, 0.7)" class="text-center px-8 py-3">
        <h1 class="text-h1 font-weight-bold text-uppercase mb-4">
          Mystery Fun Fest
        </h1>

        <h2 class="text-h5">
          Come one come all! This needs a better font!
        </h2>
      </v-sheet> -->
    </v-parallax>

    <v-container>
      <h1>{{ activity.name }}</h1>

      <p>{{ activity.description }}</p>

      <div v-if="isCompleted()">Complete!</div>

      <v-btn block color="primary" @click="complete">Redeem</v-btn>
    </v-container>
  </div>
</template>

<script>
export default {
  methods: {
    complete() {
      this.$inertia.post(route("activities.complete", this.activity.slug));
    },

    isCompleted() {
      return this.$page.props.auth.user.completions.find(
        (completion) => completion.activity_id === this.activity.id
      );
    },
  },

  props: {
    activity: Object,
  },
};
</script>
