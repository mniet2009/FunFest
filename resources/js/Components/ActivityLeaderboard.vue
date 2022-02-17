<template>
  <div>
    <v-card color="primary" class="mb-3">
      <v-card-text>
        <v-btn v-if="!formOpen" @click="formOpen = true" block
          >Submit a {{ resultColumnName }}</v-btn
        >

        <v-card v-if="formOpen">
          <v-card-text>
            <v-form ref="form" v-model="valid" lazy-validation>
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
            </v-form>
          </v-card-text>

          <v-card-actions>
            <v-btn @click="submitComplete" color="primary">
              Submit
            </v-btn>
            <v-btn @click="formOpen = false" color="grey">
              Nevermind
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-card-text>
    </v-card>

    <v-simple-table>
      <thead>
        <tr>
          <th>Rank</th>
          <th>Player</th>
          <th class="text-right">{{ resultColumnName }}</th>
          <th>Tickets</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(completion, i) in activity.completions" :key="i">
          <td>{{ completion.placement }}</td>
          <td><user-avatar :user="completion.user"></user-avatar></td>
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
        </tr>
      </tbody>
    </v-simple-table>
  </div>
</template>

<script>
import * as util from "../util.js";

export default {
  methods: {
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
