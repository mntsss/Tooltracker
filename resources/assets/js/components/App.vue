<style>
  @import '/css/app.css';
</style>
<template>
  <v-app dark :if="$auth.ready()">
    <v-snackbar
      v-model="snackbar"
      :bottom="y === 'bottom'"
      :left="x === 'left'"
      :multi-line="mode === 'multi-line'"
      :right="x === 'right'"
      :timeout="timeout"
      :top="y === 'top'"
      :vertical="mode === 'vertical'"
    >
      {{ snackbarText }}
      <v-btn
        color="pink"
        flat
        @click="snackbar = false"
      >
        Close
      </v-btn>
    </v-snackbar>
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
              <v-icon class="text-danger">{{item.icon}}</v-icon>
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
            <v-icon class="text-danger">{{item.icon}}</v-icon>
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
      <v-toolbar-title><span class="headline shrink">Tool</span><span class="shrink headline text-danger">Tracker</span></v-toolbar-title>
      <v-spacer></v-spacer>
      <v-menu offset-y>
        <v-btn icon slot="activator">
          <v-icon >shopping_cart</v-icon>
        </v-btn>
        <v-list>
          <v-list-tile v-for="(item, index) in cartDropdownMeniu" :key="index" @click="item.click">
            <v-list-tile-title>{{item.text}}</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-menu>
      <v-menu offset-y>
        <v-btn icon slot="activator">
          <v-icon >more_vert</v-icon>
        </v-btn>
        <v-list>
          <v-list-tile v-for="(item, index) in settingsDropdownMeniu" :key="index" @click="item.click">
            <v-list-tile-title>{{item.text}}</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-menu>

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
    <v-footer app fixed v-if="$auth.ready()">
      <span>&copy; 2018</span>
    </v-footer>
  </div>
</v-app>
</template>
<script>
import Login from './Login.vue'
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
                  {icon: 'keyboard_arrow_right', text: 'Visi', click: ()=> {this.$router.push({name: 'groups'})}},
                  {icon: 'keyboard_arrow_right', text: 'Įšaldyti', click: ''},
                  {icon: 'keyboard_arrow_right', text: 'Ištrinti', click: ''}
                ]
              },
              {
                icon: 'fa-briefcase',
                'icon-alt': 'keyboard_arrow_down',
                text: 'Objektai',
                model: false,
                children: [
                  { icon: 'keyboard_arrow_right', text: 'Aktyvūs', click: ()=> { this.$router.push({name: 'objects'})} },
                  { icon: 'keyboard_arrow_right', text: 'Uždaryti', click: ''}
                ]
              },
              {
                icon: 'fa-shipping-fast',
                'icon-alt': 'keyboard_arrow_down',
                text: 'Rezervacijos',
                model: false,
                children: [
                  { icon: 'keyboard_arrow_right', text: 'Aktyvios', click: ()=> { this.$router.push({name: 'reservations'})} },
                  { icon: 'keyboard_arrow_right', text: 'Atiduotos', click: ''}
                ]
              },
              { icon: 'fa-users', text: 'Vartotojai', click: '',
                model: false,
                children: [
                  { icon: 'keyboard_arrow_right', text: 'Aktyvūs', click: ()=>{ this.$router.push({name: 'users'})}},
                  { icon: 'keyboard_arrow_right', text: 'Ištrinti', click: ()=>{ this.$router.push({name: 'deletedUsers'})}}
                ]
              },
              {icon: 'fa-history', text: 'Istorija', click: ''}
            ],
            cartDropdownMeniu: [
              {text: 'Nauja rezervacija', click: () =>{this.$router.push({name: 'cart'})}},
              {text: 'Įrankių priskyrimas', click: ()=>{ alert('Įrankių priskyrimas...')}},
              {text: 'Įrankių grąžinimas', click: ()=>{ alert('Įrankių grąžinimas...')}},
            ],
            settingsDropdownMeniu: [
              {text: 'Keisti slaptažodį', click: () =>{ alert('Keisti slaptazodi...')}},
              {text: 'Atsijungti', click: ()=>{ this.$auth.logout()}},
            ],
            snackbar: false,
            y: 'top',
            x: 'right',
            mode: 'vertical',
            timeout: 6000
            }
    },
    computed: {
    isLoading: function(){
          return !(this.$auth.ready())
      },
    RFIDcode: function(){
        return this.$store.state.recentCode
      },
    snackbarText: function(){
      return 'Nuskaitytas kodas: '+this.RFIDcode
      }
    },
    watch:{
      RFIDcode(oldRFIDcode, newRFIDcode){
        this.snackbar = true
      }
    },
  components: {
    Login,
    Loading
  }
}
</script>
<style>
.fa, .far, .fas {
    font-family: "Font Awesome 5 Free" !important;
}
.fa, .fas {
    font-weight: 900 !important;
}
</style>
