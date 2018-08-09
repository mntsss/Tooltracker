<template>
<div class="row h-100 justify-content-center align-items-center">
  <div class="card">
    <div class="card-header bg-dark text-light">
      Prisijungimas
    </div>
    <div class="card-body">
      <form method="post" autocomplete="off" @submit.prevent="login">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text"><span class="fas fa-user"></span></span>
          </div>
          <input type="text" v-model="email" class="form-control" placeholder="El. paštas" aria-label="El. paštas" autocomplete="off"/>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text"><span class="fas fa-lock"></span></span>
          </div>
          <input type="password" v-model="password" class="form-control" placeholder="Slaptažodis" aria-label="Slaptažodis" />
        </div>
        <div class="form-group d-flex justify-content-center">
          <button type="submit" class="btn btn-warning text-dark">Prisijungti</button>
        </div>
      </form>
    </div>
  </div>
</div>
</template>
<script>
export default{
  data(){
    return {
      email: null,
      password: null,
      errors: []
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
