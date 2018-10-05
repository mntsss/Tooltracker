<template>
<v-container fluid fill-height>
    <v-layout justify-center align-center >
        <v-flex shrink class="login-box">
            <div class="w-100 theme--dark v-toolbar text-center pa-2 mb-5">
                <span class="headline">Tool</span><span class="headline text-danger pr-5">Tracker</span>
            </div>
            <v-alert :value="true" type="error" class="mt-2 mb-2" v-if="errors">
              Neteisingi prisijungimo duomenys
            </v-alert>
            <v-form v-model="valid" class="pt-1 pb-1 px-3">
                <v-text-field prepend-icon="fa-user" v-model="email" :rules="[v => !!v || 'Neįvestas el. paštas']" label="El. paštas" required></v-text-field>
                <v-text-field prepend-icon="fa-lock" type="password" v-model="password" :rules="[v => !!v || 'Neįvestas slaptažodis']" label="Slaptažodis" required></v-text-field>
                <v-layout row mx-0 mx-0 align-center justify-center pa-3>
                    <v-flex shrink>
                        <v-btn outline @click="login"><v-icon class="primary--text mx-2">fa-sign-in-alt</v-icon>Prisijungti</v-btn>
                    </v-flex>
                </v-layout>
            </v-form>
        </v-flex>
    </v-layout>
</v-container>
</template>
<script>
export default{
  data(){
    return {
      email: null,
      password: null,
      errors: null,
      valid: false
    }
  },
  methods: {
      login(){
        var app = this
        this.$auth.login({
            params: {
              email: app.email,
              password: app.password
            },
            success: function () {
             location.reload()
            },
            error: function (data) {
              this.errors = data
            },
            rememberMe: true,
            redirect: '/',
            fetchUser: true,
        });
      },
    }
}
</script>
