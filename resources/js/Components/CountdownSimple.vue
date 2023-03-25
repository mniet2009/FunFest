<template>
  <div class="d-flex flex-wrap justify-center my-6 flip-flex">
    <div
      v-for="(value, i) in countdown"
      :key="i"
      class="d-flex flex-column align-center flip-part"
    >
      <div style="font-size: 6em; line-height: 1">
        {{ value.toString().padStart(2, 0) }}
      </div>

      <div>{{ units[i] }}</div>
    </div>
  </div>
</template>

<script>
import Tick from "@pqina/tick";

export default {
  props: {
    endDate: String,
  },

  created() {
    let counter = Tick.count.down(this.endDate);

    counter.onupdate = (value) => {
      this.countdown = value;
    };
  },

  data() {
    return {
      countdown: [],
      units: ["Days", "Hours", "Minutes", "Seconds"],
    };
  },
};
</script>
