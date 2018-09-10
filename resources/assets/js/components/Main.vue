<template>
  <div class="loading-parent" style="height: 70vh">
      <Loading :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="fullPage"></Loading>

  <v-container>
    <v-layout row mx-0 justify-center>
      <v-flex sm-6 class="px-4 pb-3">
          <v-card tile>
              <v-card-title class="theme--dark v-toolbar h5 text-light">
                  Įrankiai laukiantys patvirtinimo
              </v-card-title>
              <v-card-text style="min-height: 25vh" class="bg-dark">
                  <v-layout align-center mt-5 justify-center row fill-height>
                    <v-flex shrink>
                      <v-progress-circular :size="100" :width="7" color="white" indeterminate></v-progress-circular>
                    </v-flex>
                  </v-layout>
              </v-card-text>
          </v-card>
      </v-flex>
      <v-flex sm-6 class="px-4 pb-3">
          <v-card tile>
              <v-card-title class="theme--dark v-toolbar h5 text-light">
                  Ilgiausiai nuomojami įrankiai
              </v-card-title>
              <v-card-text style="min-height: 25vh" class="bg-dark">
                  <v-layout align-center mt-5 justify-center row fill-height>
                    <v-flex shrink>
                      <v-progress-circular :size="100" :width="7" color="white" indeterminate></v-progress-circular>
                    </v-flex>
                  </v-layout>
              </v-card-text>
          </v-card>
      </v-flex>
    </v-layout>
    <v-layout row mx-0 justify-center>
      <v-flex sm-6 class="px-4 pb-3">
          <v-card tile>
              <v-card-title class="theme--dark v-toolbar h5 text-light">
                  Taisomi įrankiai
              </v-card-title>
              <v-card-text style="min-height: 25vh" class="bg-dark d-flex">
                  <v-layout align-center mt-5 justify-center row fill-height>
                    <v-flex shrink>
                      <v-progress-circular :size="100" :width="7" color="white" indeterminate></v-progress-circular>
                    </v-flex>
                  </v-layout>
              </v-card-text>
          </v-card>
      </v-flex>
      <v-flex sm-6 class="px-4">
          <v-card tile>
              <v-card-title class="theme--dark v-toolbar h5 text-light">
                  Jūsų aktyvios rezervacijos
              </v-card-title>
              <v-card-text style="min-height: 25vh" class="bg-dark">
                  <v-layout align-center mt-5 justify-center row fill-height>
                    <v-flex shrink>
                      <v-progress-circular :size="100" :width="7" color="white" indeterminate></v-progress-circular>
                    </v-flex>
                  </v-layout>
              </v-card-text>
          </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</div>
</template>
<script>
import CreateGroup from './modals/CreateGroup.vue';
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import swal from 'sweetalert'
export default {
  data(){
    return {
      itemGroups: [],
      isLoading: true,
      fullPage: false
    }
  },
  mounted(){
      this.isLoading = false
  },
  computed: {
    username: function(){
      return this.$auth.user().UserName
    },
    RfidCode: function(){
        return this.$store.state.recentCode;
    }
  },
  watch:{
    RfidCode(val){
        if(this.RfidCode != null && this.item == null)
        {
          this.getItemInfo(this.RfidCode)
          this.$store.commit('resetCode')
        }
      }
    },
  methods: {
      getItemInfo: function(code){
          this.$http.post('/item/findcode', {
              code: code
          }).then(response => {
              if(response.status == 200){
                  this.$router.push({name: 'item', params: {itemProp: {item: response.data.item, state: null}}});
              }
          }).catch(err => {
              if(err.response.status == 422)
              {
                  swal(err.response.data.message, Object.values(err.response.data.errors)[0][0], "error");
              }
              else{
                  swal("Klaida", err.response.data.message, "error");
              }
          })
      }
  },
  components: {
    CreateGroup,
    Loading
  }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
