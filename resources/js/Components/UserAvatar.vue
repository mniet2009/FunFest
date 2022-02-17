<template>
  <div>
    <v-avatar class="mr-3">
      <img :src="user.avatar" />
    </v-avatar>
    {{ user.username }}
  </div>
</template>

<script>
export default {
  props: {
    user: Object,
  },
};
</script>
