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
      <div class="ticket-action-box">
        <p v-if="activity.limit == null">
          This activity can be redeemed for {{ activity.tickets }} tickets.
        </p>

        <p v-if="activity.limit != null">
          This activity can be redeemed for {{ activity.tickets }} tickets, up
          to {{ activity.limit }} times.
        </p>

        <p v-if="activity.completions.length > 0 && activity.limit != null">
          You have redeemed this activity {{ activity.completions.length }}
          {{ activity.completions.length == 1 ? "time" : "times" }}.
        </p>

        <p v-if="activity.completions.length > 0 && activity.limit == null">
          You have already redeemed this activity. Poggers.
        </p>

        <div
          v-if="
            activity.completions.length == 0 ||
              (activity.limit != null &&
                activity.completions.length < activity.limit)
          "
        >
          <v-btn v-if="!formOpen" block @click="formOpen = true"
            >Redeem now</v-btn
          >

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
        </div>
      </div>
      <h1>{{ activity.name }}</h1>

      <vue-markdown>{{ activity.description }}</vue-markdown>
    </v-container>
  </div>
</template>

<style scoped lang="scss">
.container {
  padding-top: 0px;
}

.ticket-action-box {
  background-color: #00bebe;
  border-radius: 0px 0px 10px 10px;
  padding: 10px;

  p {
    text-align: center;

    &:last-child {
      margin-bottom: 0px;
    }
  }
}
</style>

<script>
import VueMarkdown from "@adapttive/vue-markdown";

export default {
  components: {
    VueMarkdown,
  },

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
