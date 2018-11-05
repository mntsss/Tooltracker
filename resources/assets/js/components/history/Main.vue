<template>
    <div class="container" style="min-height: 70vh !important">
      <v-toolbar
      color="primary"
      dark
      tabs
      >
      <v-layout aling-center justify-center><v-flex shrink headline white--text>Istorija</v-flex></v-layout>

      <!-- <DatePicker class="justify-center d-flex theme--primary text-white ma-3" v-model="date_from" :shortcuts="false" :range='true' range-separator="-" :lang="lang" format="YYYY-MM-DD"></DatePicker> -->
   </v-toolbar>
       <v-card flat>
         <v-card-text>
           <ItemsHistory></ItemsHistory>
         </v-card-text>
       </v-card>
    </div>
</template>
<script>
import swal from 'sweetalert'
import DatePicker from 'vue2-datepicker'
import ItemsHistory from './modules/withdrawals.vue'
export default{
  data(){
    return{
      date_from: null,
      date_til: null,
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
  methods:{

  },
  components:{
    DatePicker,
    ItemsHistory
  }
}
</script>
