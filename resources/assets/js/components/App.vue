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
        <ChangePasswordModal></ChangePasswordModal>
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
      <v-toolbar-title><span class="headline shrink">Tool</span><span class="shrink headline text-danger pr-5">Tracker</span></v-toolbar-title>
      <div>
      <v-text-field
        flat
        solo-inverted
        hide-details
        prepend-inner-icon="search"
        label="Paieška..."
        v-model = "searchQuery"
        v-on:keydown="search"
        class="hidden-sm-and-down"
      ></v-text-field>
      <div class="search-result-wrapper bg-dark" v-if="resultBox && searchResults">
        <v-list>
          <template v-for="(item, index) in searchResults">
            <v-list-tile :key="index" @click="searchItem(item)">
              <v-list-tile-content>
                <v-list-tile-title>{{ item.item.ItemName }}</v-list-tile-title>
                <v-list-tile-sub-title class="text--primary">{{ item.state }}</v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-divider v-if="index + 1 < searchResults.length" :key="`divider-${index}`" class="ma-0"></v-divider>
          </template>
        </v-list>
      </div>
    </div>
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

import ChangePasswordModal from './modals/settings/ChangePassword.vue'
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
                  {icon: 'keyboard_arrow_right', text: 'Įšaldyti', click: ()=> {this.$router.push({name: 'suspendedItems'})}},
                  {icon: 'keyboard_arrow_right', text: 'Nuomoti', click:  ()=> {this.$router.push({name: 'rentedItems'})}},
                  {icon: 'keyboard_arrow_right', text: 'Ištrinti', click: ()=> {this.$router.push({name: 'deletedItems'})}}
                ]
              },
              {
                icon: 'fa-briefcase',
                'icon-alt': 'keyboard_arrow_down',
                text: 'Objektai',
                model: false,
                children: [
                  { icon: 'keyboard_arrow_right', text: 'Aktyvūs', click: ()=> { this.$router.push({name: 'objects'})} },
                  { icon: 'keyboard_arrow_right', text: 'Uždaryti', click: ()=>{ this.$router.push({name: 'closedObjects'})}}
                ]
              },
              {
                icon: 'fa-shipping-fast',
                'icon-alt': 'keyboard_arrow_down',
                text: 'Rezervacijos',
                model: false,
                children: [
                  { icon: 'keyboard_arrow_right', text: 'Aktyvios', click: ()=> { this.$router.push({name: 'reservations'})} },
                  { icon: 'keyboard_arrow_right', text: 'Atiduotos', click: ()=> { this.$router.push({name: 'closedReservations'})}}
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
              {text: 'Įrankių priskyrimas', click: ()=>{ this.$router.push({name: 'assign'})}},
              {text: 'Įrankių grąžinimas', click: ()=>{ this.$router.push({name: 'return'})}},
            ],
            settingsDropdownMeniu: [
              {text: 'Keisti slaptažodį', click: () =>{ this.$modal.show('change-password-modal')}},
              {text: 'Atsijungti', click: ()=>{ this.$auth.logout()}},
            ],
            snackbar: false,
            y: 'top',
            x: 'right',
            mode: 'vertical',
            timeout: 6000,
            searchQuery: '',
            searchResults: null
            }
    },
    mounted(){
      if(window.innerWidth < 1265)
        this.drawer = false
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
    },
    resultBox: function(){
        if(this.searchQuery.length < 3 || this.searchResults == null)
          return false
        else if(this.searchResults.length == 0)
          return false
        else
          return true
    }
  },
    watch:{
      RFIDcode(oldRFIDcode, newRFIDcode){
        this.snackbar = true
      }
    },
    methods: {
      search: function(){
        if(this.searchQuery.length >= 3)
        {
          this.searchResults = null
          this.$http.post('/item/search', {query: this.searchQuery}).then(response =>{
            if(response.status == 200)
            {
              this.searchResults = response.data
            }
          })
        }
        else
          this.searchResults = null
      },
      searchItem: function(item){
        this.searchResults = null
        this.searchQuery = ''
        this.$router.push({name: 'item', params: { itemProp: item}})
      }
    },
  components: {
    Login,
    Loading,
    ChangePasswordModal
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
.search-result-wrapper{
    position: absolute;
    bottom: auto;
    z-index: 100;
    height: auto;
    max-height: 250px;
    overflow-y: auto;
    width: 80%;
    max-width: 320px;
    min-width: 180px;
    ::-webkit-scrollbar {
    display: none;
  }
}
</style>
