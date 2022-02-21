<template>
  <abbr :title="`Your local time (${timezone})`">{{ timeString }}</abbr>
</template>

<script>
export default {
  computed: {
    timeString() {
      return new Date(this.t).toLocaleString();
    },

    timezone() {
      return Intl.DateTimeFormat().resolvedOptions().timeZone;
    },
  },
  props: ["t"],
};
</script>
