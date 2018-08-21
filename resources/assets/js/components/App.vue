<style>
  @import '/css/app.css';
</style>
<template>
  <v-app id="inspire" dark :if="$auth.ready()">
    <div class="loading-parent">
        <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>
    <v-navigation-drawer
      v-model="drawer"
      clipped
      fixed
      app
    v-if="$auth.check()">
      <v-list dense>
        <v-flex v-for="item in meniuItems" :key="item.text" >
          <v-list-group
            v-if="item.children"
            v-model="item.model"
            :key="item.text"
            :append-icon="item.model ? 'keyboard_arrow_up' : 'keyboard_arrow_down'"
          >
          <v-list-tile slot="activator" fluid>
            <v-list-tile-action>
              <v-icon>{{item.icon}}</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>{{item.text}}</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>

          <v-list-tile
              v-for="(child, i) in item.children"
              :key="i"
              @click="child.click"
              fluid            >
              <v-list-tile-action v-if="child.icon">
                <v-icon>{{ child.icon }}</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>
                  {{ child.text }}
                </v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
        </v-list-group>

        <v-list-tile @click="item.click" v-else-if="!item.children">
          <v-list-tile-action>
            <v-icon>{{item.icon}}</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{item.text}}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-flex>
      </v-list>
    </v-navigation-drawer>
    <v-toolbar app fixed clipped-left v-if="$auth.check()">
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title>Įrankių valdymas</v-toolbar-title>
    </v-toolbar>
    <v-content>
      <v-container fluid fill-height>
        <v-layout justify-center align-center v-if="!$auth.check() && $auth.ready()">
          <Login></Login v-if="!$auth.check() && $auth.ready()">
        </v-layout>
        <v-layout justify-center align-center v-if="$auth.check()">
          <v-flex grow>
            <router-view></router-view>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
    <v-footer app fixed>
      <span>&copy; 2018</span>
    </v-footer>
  </div>
</v-app>
</template>
<script>
import Login from './Login.vue'
import TopMeniu from './TopMeniu.vue'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
export default {
    data(){
        return {
            fullPage: true,
            drawer: true,
            meniuItems: [
              { icon: 'fa-home', text: 'Pradžia', click: ()=>{ this.$router.push({name: 'main'})} },
              { icon: 'fa-toolbox', 'icon-alt': 'keyboard_arrow_down', text: 'Įrankiai', click: () => {this.model = !this.model},
                model: false,
                children: [
                  {icon: 'fa-wrench', text: 'Visi', click: ''},
                  {icon: 'fa-lock', text: 'Įšaldyti', click: ''},
                  {icon: 'fa-trash', text: 'Ištrinti', click: ''}
                ]
              },
              {
                icon: 'fa-briefcase',
                'icon-alt': 'keyboard_arrow_down',
                text: 'Objektai',
                model: false,
                children: [
                  { icon: 'fa-building', text: 'Aktyvūs', click: '' },
                  { icon: 'fa-hotel', text: 'Uždaryti', click: ''}
                ]
              },
              { icon: 'fa-users', text: 'Vartotojai', click: ()=>{ this.$router.push({name: 'users'})} },
              {icon: 'fa-history', text: 'Istorija', click: ''},
              {icon: 'fa-cogs', text: 'Nustatymai', click: '',
                model: false,
                children: [
                  {icon: '', text: 'Keisti slaptažodį', click: ''},
                  {icon: '', text: 'Atsijungti', click: ()=>{this.$auth.logout()}},
                ]
              }
            ]
            }
    },
    computed: {
    isLoading: function(){
            return !(this.$auth.ready())
        }
    },
  components: {
    Login,
    TopMeniu,
    Loading
  }
}
</script>
