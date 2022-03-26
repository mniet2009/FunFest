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
            <v-card-title>Task</v-card-title>
            <v-card-text class="activity-description">
              <vue-markdown>{{ activity.description }}</vue-markdown>
            </v-card-text>
          </v-card>

          <Leaderboard-Chart
            v-if="activity.activity_type_id == 1"
            :chart-data="chartData"
            :activity="activity"
          />

          <Ticket-Distribution
            v-if="[1, 5, 6, 7].includes(activity.activity_type_id)"
            class="mt-6"
            :activity="activity"
          ></Ticket-Distribution>
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
    entries: Array,
  },

  computed: {
    chartData() {
      let chartData = {
        datasets: [],
      };

      for (let user of this.entries) {
        let data = [];

        for (let entry of user.entries) {
          data.push({
            x: entry.created_at,
            y: entry.result,
          });
        }

        chartData.datasets.push({
          stepped: true,
          label: user.username,
          user: user,
          data: data,
          fill: false,
          backgroundColor: this.$page.props.teams[user.team_id - 1].color,
        });
      }

      return chartData;
    },
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
