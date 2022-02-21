<template>
  <div>
    <v-tabs
      v-if="activities.length > 1"
      v-model="tab"
      grow
      dark
      slider-color="white"
    >
      <v-tab
        v-for="(activity, i) in activities"
        :key="activity.id"
        class="white--text"
        :class="getStateColor(activityState.states[i])"
      >
        {{ activity.name }}
      </v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <v-tab-item v-for="(activity, i) in activities" :key="activity.id">
        <v-card>
          <v-card-text>
            <div class="mb-10" v-if="canRedeem(i)">
              <p>
                You can redeem this activity for {{ activity.tickets }} tickets.
              </p>

              <v-btn
                v-if="!formOpen"
                @click="formOpen = true"
                color="primary"
                block
                >Redeem</v-btn
              >

              <v-form
                v-if="formOpen"
                ref="form"
                v-model="valid"
                lazy-validation
              >
                <v-text-field
                  v-model="form.proof"
                  label="Vod/Screenshot"
                  required
                  :rules="proofRules"
                ></v-text-field>
                <v-btn @click="submitComplete" color="primary">
                  Redeem
                </v-btn>
                <v-btn @click="formOpen = false" color="grey">
                  Nevermind
                </v-btn>
              </v-form>
            </div>

            <h2>Completions</h2>
            <div
              v-if="activity.completions.length > 0"
              class="completion-list mt-3"
            >
              <user-avatar
                v-for="completion in activity.completions"
                :key="completion.id"
                :url="completion.user.id"
                :username="completion.user.username"
                :color="$page.props.teams[completion.user.team_id - 1].color"
                tooltip
                :number="activity.limit > 1 ? completion.count : null"
              ></user-avatar>
            </div>

            <div v-else>
              <p class="grey--text mb-0 mt-3">
                No one has completed this activity yet.
              </p>
            </div>
          </v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
  </div>
</template>

<script>
import * as util from "../util.js";

export default {
  methods: {
    getStateColor: util.getStateColor,

    submitComplete() {
      if (this.$refs.form[0].validate()) {
        this.form.post(route("activities.complete", this.activities[this.tab]));
        this.formOpen = false;
        this.form.proof = "";
      }
    },

    canRedeem(i) {
      return (
        this.$page.props.auth.user &&
        this.$page.props.started &&
        this.activityState.states[i] != "complete"
      );
    },
  },

  computed: {
    activityState() {
      return util.getActivityState(
        this.activity,
        this.$page.props.auth.user ? this.$page.props.auth.user.id : null
      );
    },

    activities() {
      if (this.activity.children.length == 0) {
        return [this.activity];
      } else {
        return this.activity.children;
      }
    },
  },

  props: {
    activity: Object,
  },

  data() {
    return {
      tab: 0,
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
