<template>
  <div>
    <v-parallax
      :height="700"
      dark
      src="/img/header.jpg"
      class="d-flex align-center justify-center"
    >
      <v-sheet color="rgba(0, 0, 0, 0.7)" class="text-center px-8 py-3">
        <h1 class="text-h1 font-weight-bold text-uppercase mb-4">
          Mystery Fun Fest
        </h1>

        <h2 class="text-h5">
          Come one come all! This needs a better font!
        </h2>
      </v-sheet>
    </v-parallax>

    <v-container>
      Really long page here.
      <div style="margin-top: 2000px">yep.</div>
    </v-container>
  </div>
</template>

<script>
export default {};
</script>
