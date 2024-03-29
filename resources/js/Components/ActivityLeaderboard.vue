<template>
  <div>
    <div v-if="canRedeem()" class="mb-6">
      <v-card color="primary" class="mb-3">
        <v-card-text>
          <v-btn v-if="!formOpen" @click="formOpen = true" block
            >Submit a {{ resultColumnName }}</v-btn
          >

          <v-card v-if="formOpen">
            <v-form
              @submit.prevent="submitComplete"
              ref="form"
              v-model="valid"
              lazy-validation
            >
              <v-card-text>
                <div v-if="activity.leaderboard_type_id == 1">
                  <v-text-field
                    v-model="form.result"
                    label="Score"
                    required
                    :rules="resultRules"
                    type="number"
                    min="0"
                    max="999"
                    step="1"
                  ></v-text-field>
                </div>

                <div v-else-if="activity.leaderboard_type_id == 2">
                  <v-row>
                    <v-col :cols="3">
                      <v-text-field
                        v-model="hours"
                        label="Hours"
                        type="number"
                        step="1"
                        min="0"
                      ></v-text-field>
                    </v-col>

                    <v-col :cols="3">
                      <v-text-field
                        v-model="minutes"
                        label="Minutes"
                        type="number"
                        step="1"
                        min="0"
                        max="59"
                      ></v-text-field>
                    </v-col>

                    <v-col :cols="3">
                      <v-text-field
                        v-model="seconds"
                        label="Seconds"
                        type="number"
                        step="1"
                        min="0"
                        max="59"
                      ></v-text-field>
                    </v-col>

                    <v-col :cols="3">
                      <v-text-field
                        v-model="milliseconds"
                        label="Milliseconds"
                        type="number"
                        step="1"
                        min="0"
                        max="999"
                      ></v-text-field>
                    </v-col>
                  </v-row>

                  <div v-if="invalidTime" class="error--text">
                    {{ invalidTime }}
                  </div>
                </div>

                <v-text-field
                  v-model="form.proof"
                  label="Vod/Screenshot"
                  required
                  :rules="proofRules"
                ></v-text-field>
              </v-card-text>

              <v-card-actions>
                <v-btn @click="submitComplete" color="primary">
                  Submit
                </v-btn>
                <v-btn @click="formOpen = false" color="grey">
                  Nevermind
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card>
        </v-card-text>
      </v-card>
    </div>

    <v-card>
      <v-card-title>Leaderboard</v-card-title>

      <v-simple-table>
        <thead>
          <tr>
            <th>Rank</th>
            <th>Player</th>
            <th class="text-right">{{ resultColumnName }}</th>
            <th>Tickets</th>
          </tr>
        </thead>
        <tbody v-if="activity.completions.length > 0">
          <router-link
            as="tr"
            v-for="(completion, i) in activity.completions"
            :key="i"
            :href="route('users.show', completion.user)"
            class="pointer"
            v-ripple
          >
            <td>{{ ordinal_number(completion.placement) }}</td>
            <td>
              <user-avatar
                :url="completion.user.id"
                :username="completion.user.username"
                :color="$page.props.teams[completion.user.team_id - 1].color"
              ></user-avatar>
            </td>
            <td class="text-right">
              <div v-if="activity.leaderboard_type_id == 1">
                {{ formatNumber(completion.result) }}
              </div>

              <div v-else-if="activity.leaderboard_type_id == 2">
                <span
                  v-for="part of formatTime(completion.result)"
                  :key="part.symbol"
                >
                  {{ part.number
                  }}<span class="leaderboard-time-symbol grey--text">{{
                    part.symbol
                  }}</span>
                </span>
              </div>
            </td>
            <td>{{ completion.tickets }}</td>
          </router-link>
        </tbody>

        <tbody v-else>
          <tr>
            <td colspan="4" class="text-center">
              No {{ resultColumnName.toLowerCase() }}s yet.
            </td>
          </tr>
        </tbody>
      </v-simple-table>
    </v-card>
  </div>
</template>

<script>
import * as util from "../util.js";

export default {
  methods: {
    ordinal_number: util.ordinal_number,
    formatNumber: util.formatNumber,
    formatTime: util.formatTime,

    submitComplete() {
      // parse time if it's a time attack
      if (this.activity.leaderboard_type_id == 2) {
        this.form.result =
          (parseInt(this.hours) * 3600 +
            parseInt(this.minutes) * 60 +
            parseInt(this.seconds) +
            parseInt(this.milliseconds) / 1000) *
          1000;

        console.log(this.form.result);

        if (this.form.result == 0) {
          this.invalidTime =
            "Look, I don't care how good you are, you didn't do this in 0 seconds.";
          return;
        }
      }

      if (this.$refs.form.validate()) {
        this.form.post(route("entries.store", this.activity.slug));
        this.formOpen = false;
        this.form.result = "";
        this.form.proof = "";
        this.invalidTime = false;
        this.hours = 0;
        this.minutes = 0;
        this.seconds = 0;
        this.milliseconds = 0;
      }
    },

    canRedeem() {
      return (
        this.$page.props.auth.user &&
        this.$page.props.started &&
        this.$page.props.auth.user.team_id
      );
    },
  },

  computed: {
    resultColumnName() {
      return this.activity.leaderboard_type_id == 1 ? "Score" : "Time";
    },
  },

  props: {
    activity: Object,
  },

  data() {
    return {
      valid: true,
      formOpen: false,
      resultRules: [(v) => !!v || "You gotta submit a score"],
      proofRules: [(v) => !!v || "You gotta submit a vod or something."],
      form: this.$inertia.form({
        proof: "",
        result: "",
      }),
      hours: 0,
      minutes: 0,
      seconds: 0,
      milliseconds: 0,
      invalidTime: false,
    };
  },
};
</script>
