<template>
  <div class="d-flex flex-wrap justify-center my-6">
    <div
      v-for="(value, i) in countdown"
      :key="i"
      class="d-flex flex-column align-center flip-part"
    >
      <Flip :value="value.toString().padStart(2, 0)"></Flip>

      {{ units[i] }}
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
