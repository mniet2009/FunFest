<template>
    <div>
        <v-toolbar>
            <v-toolbar-title>
                <router-link href="/" as="span">
                    Tetris Republic
                </router-link>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn to="/test">
                    <v-icon left>mdi-plus</v-icon>
                    Another page
                </v-btn>

                <v-menu offset-y v-if="$page.props.auth.user">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on">
                            <v-avatar>
                                <img :src="avatar" alt="John" />
                            </v-avatar>
                            {{ $page.props.auth.user.username }}
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item>
                            <v-list-item-action>
                                <router-link
                                    :href="route('logout')"
                                    method="post"
                                    as="v-btn"
                                >
                                    <v-icon left>mdi-login</v-icon>
                                    Log Out
                                </router-link>
                            </v-list-item-action>
                        </v-list-item>
                    </v-list>
                </v-menu>

                <v-btn
                    v-else
                    href="https://discord.com/oauth2/authorize?client_id=924044334792310785&redirect_uri=http://mysterywars.localhost/login&response_type=code&scope=identify%20email"
                >
                    <v-icon left>mdi-login</v-icon>
                    Log In
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
    </div>
</template>

<script>
export default {
    computed: {
        avatar() {
            return `https://cdn.discordapp.com/avatars/${this.$page.props.auth.user.id}/${this.$page.props.auth.user.avatar}`;
        }
    },

    data() {
        return {};
    }
};
</script>
