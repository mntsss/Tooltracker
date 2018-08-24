<template>
<div class="row h-100 justify-content-center align-items-center">
  <div class="card">
    <div class="card-header theme--dark v-toolbar headline">
      Prisijungimas
    </div>
    <v-layout row mx-0 class="card-body bg-dark">
      <v-form v-model="valid">
        <v-text-field prepend-icon="fa-user" v-model="email" :rules="[v => !!v || 'Neįvestas el. paštas']" label="El. paštas" required></v-text-field>
        <v-text-field prepend-icon="fa-lock" type="password" v-model="password" :rules="[v => !!v || 'Neįvestas slaptažodis']" label="Slaptažodis" required></v-text-field>
        <v-layout row mx-0 align-center justify-center pa-3>
          <v-flex shrink>
            <v-btn outline @click="login"><v-icon class="text-danger mx-2">fa-sign-in-alt</v-icon>Prisijungti</v-btn>
          </v-flex>
        </v-layout>
      </v-form>
    </v-layout>
  </div>
</div>
</template>
<script>
export default{
  data(){
    return {
      email: null,
      password: null,
      errors: [],
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
