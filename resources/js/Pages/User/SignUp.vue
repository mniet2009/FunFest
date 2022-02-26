<template>
  <div>
    <Head title="Sign Up" />

    <v-parallax
      style="height: 300px"
      dark
      src="/img/signup.jpg"
      class="d-flex align-center justify-center top-image"
    >
      <h1>Sign Up</h1>
    </v-parallax>

    <v-container>
      <v-card class="mx-auto" style="max-width: 500px">
        <v-card-title>Sign Up Form</v-card-title>

        <v-card-text>
          <div v-if="!$page.props.auth.user">
            <p>Login first!</p>

            <v-btn block color="#5865F2" :href="route('login')">
              <v-icon>
                mdi-discord
              </v-icon>
              Discord Login
            </v-btn>
          </div>

          <div v-else>
            <div v-if="signedUp">
              <p>
                You have already signed up, but you can edit your data here!
              </p>
            </div>

            <v-form ref="form" @click.prevent="submit">
              <v-radio-group
                class="mb-3"
                v-model="form.likelihood"
                label="How likely are you to participate?"
                :rules="rules.likelihood"
              >
                <v-radio
                  v-for="option of likelihoodOptions"
                  :key="option.value"
                  :label="option.text"
                  :value="option.value"
                ></v-radio>
              </v-radio-group>

              <v-textarea
                outlined
                v-model="form.friends"
                label="Please list up to three friends you'd like to team up with"
                no-resize
                rows="3"
              ></v-textarea>

              <v-btn color="primary" @click="submit">
                {{ signedUp ? "Save" : "Sign Up" }}
              </v-btn>
            </v-form>
          </div>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>

<script>
export default {
  props: {
    initialLikelihood: Number,
    initialFriends: String,
  },

  created() {
    this.form.likelihood = this.initialLikelihood;
    this.form.friends = this.initialFriends;
  },

  methods: {
    submit() {
      if (this.$refs.form.validate()) {
        this.form.post(route("signUp"));
      }
    },
  },

  computed: {
    signedUp() {
      return this.$page.props.auth.user && this.$page.props.auth.user.signedUp;
    },
  },

  data() {
    return {
      form: this.$inertia.form({
        likelihood: null,
        friends: "",
      }),
      rules: {
        likelihood: [(v) => !!v || "Choose one of these."],
      },
      likelihoodOptions: [
        { text: "I'm all in!", value: 1 },
        { text: "50/50 - depends on my availability.", value: 2 },
        { text: "Unlikely - signing up just in case!", value: 3 },
      ],
    };
  },
};
</script>
