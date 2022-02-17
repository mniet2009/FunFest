<template>
  <v-app>
    <Menu></Menu>

    <v-main>
      <slot></slot>
    </v-main>

    <v-snackbar
      v-if="this.$page.props.flash"
      v-model="snackbar"
      :color="this.$page.props.flash.type"
    >
      {{ this.$page.props.flash.text }}

      <template v-slot:action="{ attrs }">
        <v-btn text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </v-app>
</template>

<script>
export default {
  methods: {
    checkSnackbar() {
      if (this.$page.props.flash) {
        this.snackbar = true;
      }
    },
  },
  created() {
    this.checkSnackbar();
    this.$inertia.on("finish", this.checkSnackbar);
  },
  data() {
    return {
      snackbar: false,
    };
  },
};
</script>
