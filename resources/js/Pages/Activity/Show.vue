<template>
  <div>
    <Head :title="activity.name" />

    <v-parallax
      style="height: 300px"
      dark
      :src="activity.image"
      class="d-flex align-center justify-center top-image"
    >
      <h1>{{ activity.name }}</h1>
    </v-parallax>

    <v-container class="pt-3">
      <v-row>
        <v-col cols="12" lg="6">
          <v-card>
            <v-card-text>
              <vue-markdown>{{ activity.description }}</vue-markdown>
            </v-card-text>
          </v-card>

          <v-card
            v-if="[1, 5, 6, 7].includes(activity.activity_type_id)"
            class="mt-10"
          >
            <v-card-title>Ticket distribution</v-card-title>
            <v-simple-table>
              <thead>
                <tr>
                  <th>Rank</th>
                  <th>Tickets</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(tickets, rank) in activity.leaderboard_tickets"
                  :key="rank"
                >
                  <td>{{ ordinal_number(rank + 1) }}</td>
                  <td>{{ tickets }}</td>
                </tr>
              </tbody>
            </v-simple-table>
          </v-card>
        </v-col>

        <v-col cols="12" lg="6">
          <activity-leaderboard
            v-if="activity.activity_type_id == 1"
            :activity="activity"
          ></activity-leaderboard>

          <activity-completion
            v-if="[2, 3, 4].includes(activity.activity_type_id)"
            :activity="activity"
          ></activity-completion>

          <activity-competition
            v-if="[5, 6, 7].includes(activity.activity_type_id)"
            :activity="activity"
          ></activity-competition>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<style scoped lang="scss">
.container {
  padding-top: 0px;
}
</style>

<script>
import * as util from "../../util.js";
import VueMarkdown from "@adapttive/vue-markdown";

export default {
  components: {
    VueMarkdown,
  },

  methods: {
    ordinal_number: util.ordinal_number,
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
