<template>
  <div class="loading-parent" style="height: 70vh !important">
      <Loading :active.sync="isLoading"
          :can-cancel="false"
          :is-full-page="fullPage"></Loading>
      <v-container>
        <v-layout wrap row class="theme--dark v-toolbar mb-5" justify-center><v-flex shrink headline>Įrankių grąžinimas</v-flex></v-layout>
        <v-layout class="" align-center justify-center>
          <v-flex shrink>
            <v-card tile>
              <div class="text-center p-5">
                <v-icon class="display-4 text-danger" d-flex justify-center>fa-broadcast-tower</v-icon>
              </div>
              <v-progress-linear :indeterminate="true" color="red darken-2" background-color="white" class="mb-5 mt-5"></v-progress-linear>
              <v-card-title class="headline">
                Skanuokite įrankius, kuriuos grąžinate į sandėlį
              </v-card-title>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
  </div>
</template>
<script>
import swal from 'sweetalert'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
export default{
  data(){
    return {
      isLoading: true,
      fullPage: false
    }
  },
  created(){
    this.isLoading = false
  },
  computed:{
    RfidCode: function(){
      return this.$store.state.recentCode;
    }
  },
  watch:
  {
    RfidCode(val){
      if(this.RfidCode != null)
      {
        alert(this.RfidCode)
        this.$store.commit('resetCode')
      }
    }
  },
  methods: {

  },
  components: {
    Loading
  }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
    </style>
