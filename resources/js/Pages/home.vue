<template>
  <div>
    <Head title="Home" />

    <v-parallax
      :height="700"
      dark
      src="/img/header.jpg"
      class="d-flex align-center justify-center parallax-home"
    >
      <h1 class="text-uppercase">
        Mystery <span style="white-space: nowrap">Fun Fest</span>
      </h1>
    </v-parallax>

    <v-container>
      <div
        class="mx-auto my-5"
        style="max-width: 500px"
        v-if="$page.props.signupsOpen"
      >
        <v-btn color="primary" :to="route('signUpForm')" x-large block>
          <div v-if="signedUp">
            Edit signup data
          </div>
          <div v-else>
            Sign up now!
          </div>
        </v-btn>
      </div>
      <h1 class="text-center">Welcome to Mystery Fun Fest!</h1>

      <p class="text-center mb-6">
        This event will take place in the
        <a target="_blank" href="https://discord.gg/ZpdMFuX">
          Mystery community Discord
        </a>
        and on this website.
      </p>

      <p>
        Mystery Fun Fest is a cooperative event running from
        <strong>
          {{ new Date(Date.parse("2022-03-15T12:00:00")).toLocaleDateString() }}
          -
          {{ new Date(Date.parse("2022-04-03T12:00:00")).toLocaleDateString() }}
        </strong>
        in which two teams compete to accrue the most tickets throughout its
        17-day duration. Players will battle in competitive sub-events, complete
        both solo and team-based activities, and perform a variety of other
        tasks to accumulate tickets for their team. Above all else, however,
        this event is designed to give community members a myriad of reasons to
        play games and bond with one another and reward them for doing so!
        Whether you grind tickets to help your team win or casually play only
        one or two games that suit your interests, there's no wrong way to
        participate.
      </p>
      <h2 class="text-center mb-3" v-if="$page.props.signupsOpen">
        To participate in Mystery Fun Fest, you are required to
        <router-link :href="route('signUpForm')">register</router-link> on this
        website before <local-datetime :t="1647406740000"></local-datetime>.
      </h2>

      <p>
        You will be randomly assigned to a team, though you may request up to
        three other community members -- assuming they also register -- to be
        teamed up with. You also have absolutely no obligation to participate if
        you sign up, so we highly encourage you to do so even if you don't
        necessarily plan on participating. Please play it safe and register just
        in case!
      </p>

      <p>
        For general event questions and discussion, please use #mystery-fun-fest
        on the Discord. Each team will also have a dedicated channel for private
        discussions, planning, and strategizing. At least one event mod will
        also be present on each team to answer any private questions and pass
        any pertinent information on to other event moderators and/or
        participants.
      </p>

      <p>
        Please use this site to mark activities as complete and update personal
        best scores and times where applicable. Note that team-based activities
        must be marked by each player in the team. Proof must be linked for each
        submission in the form of a Twitch or Youtube video. Screenshot-based
        proof may be approved by an event moderator on a case-by-case basis.
      </p>

      <p>The categories of sub-events that will be featured are as follows:</p>

      <v-row class="mb-3 align-stretch">
        <v-col
          cols="12"
          md="6"
          lg="4"
          v-for="activityType of activityTypesFixed"
          :key="activityType.id"
        >
          <v-card class="text-center rounded-lg" style="height: 100%">
            <v-card-text>
              <v-icon :size="60" color="primary" class="mb-2">
                {{ activityType.icon }}
              </v-icon>
              <h2 class="text-center mb-3 white--text">
                {{ activityType.name }}
              </h2>
              <p class="mb-0">{{ activityType.description }}</p>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <p>
        All currently public games, activities, and their ticket distributions
        to be featured in Mystery Fun Fest can be viewed on the
        <a target="_blank" :href="route('activities.index')">activity page</a>.
        The
        <a target="_blank" :href="route('activities.schedule')">
          schedule page
        </a>
        will also show all currently public games that will take place at a
        specified time rather than being accessible throughout the entire event.
        Until the event begins, these pages will be incomplete - games will be
        added nearly daily up until the event starts.
      </p>

      <p>
        Steam games will be provided to three random participants with a minimum
        of 20 tickets on the winning team. Additionally, the player with the
        most tickets on each team will be given a Steam game as a reward for
        heavily supporting their team.
      </p>

      <p>
        Good luck to both teams and happy gaming! Also, shoutouts to
        iateyourpie.
      </p>
    </v-container>
  </div>
</template>

<script>
export default {
  props: {
    activityTypes: Array,
  },

  computed: {
    signedUp() {
      this.$page.props.auth.user && this.$page.props.auth.user.signedUp;
    },

    activityTypesFixed() {
      return this.activityTypes
        .filter((activityType) => {
          return activityType.id !== 3; // remove team based completion challenge
        })
        .map((activityType) => {
          if (activityType.id == 2) {
            return Object.assign(activityType, {
              // rename regular one
              name: "Completion Challenges",
            });
          }

          return activityType;
        });
    },
  },
};
</script>
