<template>
  <v-card class="activity-card">
    <v-img height="250" :src="activity.image"> </v-img>

    <div class="d-flex">
      <div class="ticket-box" :class="color(activityState.state)">
        <div>
          <div class="ticket-box-icon">
            <v-icon :size="40">{{ activity.activity_type.icon }}</v-icon>
          </div>
          <div v-if="activityTypeBoxes[activity.activity_type_id - 1].subtitle">
            {{ activityTypeBoxes[activity.activity_type_id - 1].subtitle() }}
          </div>
        </div>
      </div>
      <div class="flex-grow-1">
        <v-card-title>{{ activity.name }}</v-card-title>
        <div class="d-flex align-stretch">
          <div
            v-if="activityState.states.length > 1"
            v-ripple
            class="activity-card-arrow"
            @click.stop="previousExcerpt"
          >
            <v-icon>mdi-chevron-left</v-icon>
          </div>

          <div class="flex-grow-1">
            <v-card-text>
              <v-tabs-items v-model="activeExcerpt">
                <v-tab-item v-for="(excerpt, i) in excerpts" :key="i">
                  {{ excerpt }}
                </v-tab-item>
              </v-tabs-items>
            </v-card-text>
          </div>

          <div
            v-if="activityState.states.length > 1"
            v-ripple
            class="activity-card-arrow"
            @click.stop="nextExcerpt"
          >
            <v-icon>mdi-chevron-right</v-icon>
          </div>
        </div>

        <div class="activity-card-progress-markers d-flex">
          <div
            class="activity-card-progress-marker"
            :class="[{ active: i === activeExcerpt }, color(state)]"
            v-for="(state, i) in activityState.states"
            :key="i"
          ></div>
        </div>
      </div>
    </div>
  </v-card>
</template>

<script>
import * as util from "../util.js";

export default {
  methods: {
    color(state) {
      if (state == "incomplete") {
        return "error";
      } else if (state == "partial") {
        return "warning";
      } else if (state == "complete") {
        return "success";
      }
    },

    nextExcerpt() {
      this.activeExcerpt = (this.activeExcerpt + 1) % this.excerpts.length;
    },

    previousExcerpt() {
      this.activeExcerpt =
        (this.activeExcerpt - 1 + this.excerpts.length) % this.excerpts.length;
    },

    eventSubtitle() {
      if (this.activity.completions[0]) {
        return util.ordinal_number(this.activity.completions[0].placement);
      }

      let eventDate = new Date(this.activity.event_at);

      if (eventDate > new Date()) {
        return eventDate.toLocaleDateString(undefined, {
          month: "short",
          day: "numeric",
        });
      } else {
        return "DNF";
      }
    },
  },

  computed: {
    activityState() {
      return util.getActivityState(this.activity);
    },

    excerpts() {
      let excerpts = [this.activity.excerpt];

      for (let activity of this.activity.children) {
        excerpts.push(activity.excerpt);
      }

      return excerpts;
    },
  },

  props: {
    activity: Object,
  },

  data() {
    return {
      activeExcerpt: 0,
      activityTypeBoxes: [
        {
          subtitle: () => {
            return this.activity.completions[0]
              ? util.ordinal_number(this.activity.completions[0].placement)
              : "-";
          },
        },
        {
          subtitle: () =>
            `${this.activityState.tickets} / ${this.activityState.availableTickets}`,
        },
        {
          subtitle: () =>
            `${this.activityState.tickets} / ${this.activityState.availableTickets}`,
        },
        {
          subtitle: () =>
            `${this.activityState.tickets} / ${this.activityState.availableTickets}`,
        },
        {
          subtitle: this.eventSubtitle,
        },
        {
          subtitle: this.eventSubtitle,
        },
        {
          subtitle: this.eventSubtitle,
        },
      ],
    };
  },
};
</script>
