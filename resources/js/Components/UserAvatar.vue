<template>
  <span>
    <v-tooltip top v-if="tooltip" :color="color">
      <template v-slot:activator="{ on, attrs }">
        <div
          class="avatar avatar-bordered mr-3 mb-3"
          size="100"
          v-bind="attrs"
          v-on="on"
          style="width: 100px; height: 100px; border-width: 5px;"
          :style="{ 'border-color': color }"
        >
          <img :src="url" />

          <div class="avatar-number" v-if="number">
            {{ number }}
          </div>
        </div>
      </template>
      {{ username }}
    </v-tooltip>

    <span v-else>
      <div
        class="avatar mr-3"
        :class="{ 'avatar-bordered': color }"
        v-bind="attrs"
        v-on="on"
        style="width: 50px; height: 50px; border-width: 3px;"
        :style="{ 'border-color': color }"
      >
        <img :src="url" />
      </div>
      {{ username }}
    </span>
  </span>
</template>

<script>
export default {
  props: {
    url: String,
    username: String,
    tooltip: Boolean,
    color: String,
    number: Number,
  },
};
</script>
