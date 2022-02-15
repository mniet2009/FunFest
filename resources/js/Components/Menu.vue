<template>
  <v-navigation-drawer permanent app expand-on-hover v-model="drawer">
    <v-sheet color="primary">
      <v-list>
        <v-list-item class="px-2" v-if="$page.props.auth.user">
          <v-list-item-avatar>
            <img :src="$page.props.auth.user.avatar" />
          </v-list-item-avatar>

          <v-list-item-title>
            <div class="logged-in-as">Logged in as:</div>
            <strong>{{ $page.props.auth.user.username }}</strong>
          </v-list-item-title>

          <v-tooltip left>
            <template v-slot:activator="{ on, attrs }">
              <div v-bind="attrs" v-on="on">
                <v-btn icon :to="route('logout')">
                  <v-icon>mdi-logout</v-icon>
                </v-btn>
              </div>
            </template>
            <span>Logout</span>
          </v-tooltip>
        </v-list-item>

        <v-list-item v-else>
          <v-list-item-icon>
            <v-icon>mdi-discord</v-icon>
          </v-list-item-icon>

          <v-list-item-title>
            <v-btn block color="#5865F2" :href="route('login')">
              Discord Login
            </v-btn>
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </v-sheet>

    <v-list>
      <router-link
        v-for="item in items"
        :key="item.title"
        :href="route(item.route)"
        as="div"
      >
        <v-list-item link>
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </router-link>
    </v-list>

    <template v-slot:append>
      <v-sheet color="rgba(0, 0, 0, 0.5)" class="pa-1 text-center grey--text">
        <span class="by">made by Maurice</span>
      </v-sheet>
    </template>
  </v-navigation-drawer>
</template>

<style scoped lang="scss">
.user-box {
  line-height: 1;
  .logged-in-as {
    font-size: 0.8em;
  }

  img {
    border: 3px solid white;
  }
}

.by {
  opacity: 0;
  white-space: nowrap;
  transition: 0.2s opacity;
}

.v-navigation-drawer--is-mouseover .by {
  opacity: 1;
}
</style>

<script>
export default {
  data() {
    return {
      drawer: true,
      items: [
        {
          title: "Home",
          icon: "mdi-home",
          route: "home",
        },
        {
          title: "Activities",
          icon: "mdi-ticket",
          route: "activities.index",
        },
        {
          title: "Standings",
          icon: "mdi-podium",
          route: "teams.index",
        },
        {
          title: "Schedule",
          icon: "mdi-table",
          route: "activities.schedule",
        },
        {
          title: "About",
          icon: "mdi-information",
          route: "about",
        },
      ],
    };
  },
};
</script>
