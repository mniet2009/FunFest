<template>
  <v-card color="primary">
    <v-card-text>
      <v-btn v-if="!formOpen" @click="formOpen = true" block>Redeem</v-btn>

      <v-card v-if="formOpen">
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-text-field
              v-model="form.proof"
              label="Vod/Screenshot"
              required
              :rules="proofRules"
            ></v-text-field>
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-btn @click="submitComplete" color="primary">
            Redeem
          </v-btn>
          <v-btn @click="formOpen = false" color="grey">
            Nevermind
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  methods: {
    submitComplete() {
      if (this.$refs.form.validate()) {
        this.form.post(route("activities.complete", this.activity.slug));
        this.formOpen = false;
        this.form.proof = "";
      }
    },
  },

  props: {
    activity: Object,
  },

  data() {
    return {
      valid: true,
      formOpen: false,
      proofRules: [(v) => !!v || "You gotta submit a vod or something."],
      form: this.$inertia.form({
        proof: "",
      }),
    };
  },
};
</script>
