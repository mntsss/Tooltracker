<template>
  <div class="loading-parent">
    <Loading :active.sync="isLoading"
    :can-cancel="false"
    :is-full-page="fullPage"></Loading>
    <div class="container" style="min-height: 70vh !important">
      <v-toolbar
      color="primary"
      dark
      tabs
      >
      <v-layout aling-center justify-center><v-flex shrink headline white--text>Istorija</v-flex></v-layout>

      <!-- <DatePicker class="justify-center d-flex theme--primary text-white ma-3" v-model="date_from" :shortcuts="false" :range='true' range-separator="-" :lang="lang" format="YYYY-MM-DD"></DatePicker> -->
      <v-tabs
       slot="extension"
       v-model="currentTab"
       color="transparent"
       fixed-tabs
       slider-color="white"
     >
       <v-tab
         v-for="tab in tabs"
         :href="'#tab-' + tab"
         :key="tab"
       >
         {{ tab }}
       </v-tab>
     </v-tabs>
   </v-toolbar>

   <v-tabs-items v-model="currentTab">
     <v-tab-item
       v-for="tab in tabs"
       :id="'tab-' + tab"
       :key="tab"
     >
       <v-card flat v-if="currentTab == 'tab-'+tabs[0]">
         <v-card-text>
           <ItemsHistory></ItemsHistory>
         </v-card-text>
       </v-card>
     </v-tab-item>
   </v-tabs-items>
    </div>
  </div>
</template>
<script>
import swal from 'sweetalert'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import DatePicker from 'vue2-datepicker'
import ItemsHistory from './modules/withdrawals'
export default{
  data(){
    return{
      isLoading: false,
      fullPage: false,
      currentTab: null,
      date_from: null,
      date_til: null,
      tabs: [
        'Įrankių naudojimas',
        'Įrankių taisymas',
        'Objektai'
      ],
      lang: {
        days: ['Sek', 'Pr', 'An', 'Tr', 'Ket', 'Pn', 'Še'],
        months: ['Sau', 'Vas', 'Kov', 'Bal', 'Geg', 'Bir', 'Lie', 'Rugp', 'Rugs', 'Spa', 'Lap', 'Gru'],
        placeholder: {
          date: 'Pasirinkite datą',
          dateRange: 'Pasirinkite laikotarpį'
        }
      }

    }
  },
  created(){

  },
  methods:{

  },
  components:{
    Loading,
    DatePicker,
    ItemsHistory
  }
}
</script>
<style>
  .loading-parent{
    position: relative;
  }
</style>
