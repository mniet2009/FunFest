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
      <v-card>
        <v-card-title>Assign placements</v-card-title>
        <v-card-text>
          <p>
            This will overwrite any previous placements assigned for this
            activity in the past.
          </p>
          <v-autocomplete
            label="Select or search for a player to add them"
            :items="users"
            item-value="id"
            item-text="username"
            @change="addPlacement"
            v-model="placementSelector"
          ></v-autocomplete>
        </v-card-text>

        <v-simple-table class="mb-5">
          <thead>
            <tr>
              <th></th>
              <th>Rank</th>
              <th>Player</th>
            </tr>
          </thead>
          <draggable tag="tbody" v-model="placements">
            <tr v-for="(user, rank) in placements" :key="user.id">
              <td>
                <v-btn color="error" @click="removePlacement(user)">
                  <v-icon>mdi-delete</v-icon>
                </v-btn>
              </td>
              <td>{{ ordinal_number(rank + 1) }}</td>
              <td>{{ user.username }}</td>
            </tr>
          </draggable>
        </v-simple-table>

        <v-card-actions>
          <v-btn color="primary" @click="savePlacements">
            Save placements and distribute tickets
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-container>
  </div>
</template>

<style scoped>
tr {
  cursor: ns-resize;
}
</style>

<script>
import draggable from "vuedraggable";
import * as util from "../../util.js";

export default {
  components: {
    draggable,
  },

  methods: {
    ordinal_number: util.ordinal_number,

    removePlacement(user) {
      this.placements.splice(this.placements.indexOf(user), 1);
    },

    addPlacement() {
      let user = this.users.find((u) => u.id == this.placementSelector);
      this.placements.push(user);
      this.$nextTick(() => {
        this.placementSelector = "";
      });
    },

    savePlacements() {
      let userArray = this.placements.map((u) => u.id);

      this.$inertia.post(route("activities.assignPoints", this.activity), {
        users: userArray,
      });
    },
  },

  props: {
    activity: Object,
    users: Array,
  },

  data() {
    return {
      placementSelector: null,
      placements: [],
    };
  },
};
</script>
