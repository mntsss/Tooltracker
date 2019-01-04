<style>
  @import '/css/app.css';
</style>
<template>
  <v-app :if="$auth.ready()">
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
        <Sidebar :show = "drawer"></Sidebar>
      <v-toolbar app fixed clipped-left v-if="$auth.check() && $user" class="white">
        <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
        <v-toolbar-title><a href="/"><img src="/media/logo.png" alt="logo" class="logo mx-4"/></a></v-toolbar-title>
        <SearchBox></SearchBox>
        <v-spacer></v-spacer>
        <v-menu offset-y v-if="$user.UserRole =='Administratorius'">
          <v-btn icon slot="activator" class="mx-2">
            <v-icon medium class="primary--text">shopping_cart</v-icon>
          </v-btn>
          <v-list>
            <v-list-tile v-for="(item, index) in cartDropdownMeniu" :key="index" @click="item.click">
              <v-list-tile-title>{{item.text}}</v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-menu>
        <v-menu offset-y>
            <v-flex shrink v-if="$user && screenWidth > 650" slot="activator" class="hover-bottom-border">
              <v-icon  class="primary--text pr-3">fa-user</v-icon>{{$user.Username}}<v-icon class="pl-2">keyboard_arrow_down</v-icon>
            </v-flex>
            <v-btn icon v-else slot="activator">
              <v-icon class="primary--text">fa-user</v-icon>
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
            <Login v-if="!$auth.check() && $auth.ready()" v-on:loginSuccess="$getUser()"></Login>
          </v-layout>
          <v-layout justify-center align-center v-if="$auth.check()">
            <v-flex grow class="loading-parent">
                <Loading :active.sync="$contentLoading"
                :can-cancel="false"
                :is-full-page="false"></Loading>
              <router-view></router-view>
            </v-flex>
          </v-layout>
        </v-container>
      </v-content>
      <Footer></Footer>
    </div>
</v-app>
</template>
<script>
import Login from './Login.vue'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import SearchBox from './modules/SearchBox.vue'
import Sidebar from './modules/Sidebar.vue'
import Footer from './modules/Footer.vue'
import ChangePasswordModal from './modals/settings/ChangePassword.vue'
export default {
    data(){
        return {
            fullPage: true,
            drawer: true,
            cartDropdownMeniu: [
              {text: 'Nauja rezervacija', click: () =>{this.$router.push({name: 'cart'})}},
              {text: 'Įrankių priskyrimas', click: ()=>{ this.$router.push({name: 'assign'})}},
              {text: 'Įrankių grąžinimas', click: ()=>{ this.$router.push({name: 'return'})}},
            ],
            settingsDropdownMeniu: [
              {text: 'Mano įrankiai', click: ()=>{this.$router.push({name:'userWithdrawals', params: {userID: this.$user.UserID}})}},
              {text: 'Keisti slaptažodį', click: () =>{ this.$modal.show('change-password-modal')}},
              {text: 'Atsijungti', click: ()=>{ this.$store.commit('setUser', null); this.$auth.logout()}},
            ],
            snackbar: false,
            y: 'top',
            x: 'right',
            mode: 'vertical',
            timeout: 6000,
            }
    },
    mounted(){
      this.$eventBus.$on('sidebar_event', (payload) => {
        this.drawer = payload;
      });

      if(window.innerWidth < 1265)
        this.drawer = false

      Echo.channel(process.env.MIX_PUSHER_CHANNEL_NAME)
        .listen('ReceivedCode', (e) => {
          console.log("Code received")
          if(e.userID == this.$user.UserID)
            this.$store.commit('newcode', e.code)
        });
    },
    computed: {
    screenWidth: function(){
      return window.innerWidth
    },
    isLoading: function(){
          return !(this.$auth.ready())
      },
    RFIDcode: function(){
        return this.$store.state.recentCode
      },
    snackbarText: function(){
      return 'NFC kodas nuskaitytas!'
    }
  },
  watch:{
    RFIDcode(oldRFIDcode, newRFIDcode){
      this.snackbar = true
    }
  },
  methods: {
    changeDrawer: function (payload){
      this.drawer = payload;
    }
  },
  components: {
    Login,
    Loading,
    ChangePasswordModal,
    SearchBox,
    Sidebar,
    Footer
  }
}
</script>
