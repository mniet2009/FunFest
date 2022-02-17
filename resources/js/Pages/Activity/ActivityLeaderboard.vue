<template>
  <div>
    <v-card color="primary" class="mb-3">
      <v-card-text>
        <v-btn v-if="!formOpen" @click="formOpen = true" block
          >Submit a score</v-btn
        >

        <v-card v-if="formOpen">
          <v-card-text>
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-text-field
                v-model="form.result"
                label="Score"
                required
                :rules="resultRules"
                type="number"
                min="0"
                step="1"
              ></v-text-field>

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
          <th>User</th>
          <th>Score</th>
          <th>Tickets</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(completion, i) in activity.completions" :key="i">
          <td>{{ i + 1 }}</td>
          <td>{{ completion.user.username }}</td>
          <td>{{ formatNumber(completion.result) }}</td>
          <td>{{ completion.tickets }}</td>
        </tr>
      </tbody>
    </v-simple-table>
  </div>
</template>

<script>
import * as util from "../../util.js";

export default {
  methods: {
    formatNumber: util.formatNumber,

    submitComplete() {
      if (this.$refs.form.validate()) {
        this.form.post(route("entries.store", this.activity.slug));
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
      resultRules: [(v) => !!v || "You gotta submit a score"],
      form: this.$inertia.form({
        proof: "",
        result: "",
      }),
    };
  },
};
</script>
