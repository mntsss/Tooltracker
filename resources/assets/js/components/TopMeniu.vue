<template>
  <nav class="navbar navbar-expand-sm navbar-keytracker remove-top-padding w-100 align-self-top mb-5">
    <a class="navbar-brand " @click="$router.push('/')"><img src="/media/logo.svg" alt="logo"/></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Išskleisti meniu">
      <i class="fas fa-bars text-warning" style="font-size: 30px"></i>
    </button>

    <div class="collapse navbar-collapse row" id="navbar">
      <ul class="navbar-nav mr-auto col-lg-6">
        <li class="nav-item">
          <router-link tag="div" to="/" class="nav-link cursor-pointer">Pradžia</router-link>
        </li>
        <li class="nav-item">
          <router-link tag="div" :to="{ name: 'users'}" class="nav-link cursor-pointer">Vartotojai</router-link>
        </li>
        <li class="nav-item">
          <router-link tag="div" to="/" class="nav-link cursor-pointer">Objektai</router-link>
        </li>
        <li class="nav-item">
          <router-link tag="div" to="/" class="nav-link cursor-pointer">Statistika</router-link>
        </li>
        <li class="nav-item">
          <router-link tag="div" to="/" class="nav-link cursor-pointer">Istorija</router-link>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto col-lg-6">
        <form class="form-inline my-2 my-md-0 nav-search">
        <div class="input-group ">
          <input class="form-control py-2" type="search" name="query" id="search" placeholder="Paieška...">
          <span class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">
              <i class="fa fa-search"></i>
            </button>
          </span>
        </div>
      </form>
      <li class="nav-item dropdown">
                      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                          {{ $auth.user().Username }} <span class="caret"></span>
                      </a>

        <div class="dropdown-menu" aria-labelledby="dropdown-user">
            <a class="dropdown-item" href="#" role="button">Keisti slaptažodį</a>
            <a class="dropdown-item" @click="$auth.logout()">Atsijungti</a>
        </div>
      </li>
      </ul>

    </div>
  </nav>
</template>
<script>
  export default{

  }
</script>
