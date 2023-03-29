<template>
  <div class="d-flex">
    <span v-for="(value, i) in countdown" :key="i">
      {{ value.toString().padStart(2, 0) }}{{ units[i] }}&nbsp;
    </span>
  </div>
</template>

<script>
import Tick from "@pqina/tick";

export default {
  props: {
    endDate: Date,
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
      units: ["d", "h", "m", "s"],
    };
  },
};
</script>
