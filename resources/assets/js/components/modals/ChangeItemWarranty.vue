<template>
  <modal name="change-item-warranty-modal"
         height="auto"
         width= "320px"
         :adaptive="true"
         :scrollable="true"
         transition="pop-out"
         :pivotY="0.3"
         :clickToClose="false"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div class="card">
      <div class="card-header h5 secondary">
          Keisti įrankio garantinį laikotarpį <a @click="$modal.hide('change-item-warranty-modal')" class="float-right mr-3"><span class="fas fa-times btn-func-misc"></span></a>
      </div>
      <v-form>
      <v-layout>
        <v-flex xs12>
          <DatePicker class="justify-center d-flex theme--primary text-white ma-3" v-model="warranty_date" :lang="lang" format="YYYY-MM-DD"></DatePicker>
        </v-flex>
      </v-layout>
      <v-layout justify-center align-bottom>
        <v-flex shrink>
          <v-btn class="ma-2" @click="save()"><v-icon class="primary--text mr-3">fa-save</v-icon>Išsaugoti</v-btn>
        </v-flex>
      </v-layout>
      </v-form>
    </div>
  </modal>
</template>
<script>
import DatePicker from 'vue2-datepicker'
import swal from 'sweetalert'
export default {
    data(){
        return {
            warranty_date: null,
            itemID: null,
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
    computed: {
        date: function(){
            return this.format(this.warranty_date)
        }
    },
  methods: {
    save: function(){
        this.$http.post('/item/edit/warranty', {
            'id': this.itemID,
            'warranty': this.date
        }).then((response)=>{
            if(response.status == 200){
                this.$parent.itemData.ItemWarranty = this.date
                this.$modal.hide('change-item-warranty-modal')
            }
        }).catch(error =>{
            if(error.response.status == 422)
            {
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
            }
            else{
                swal("Klaida", error.response.data.message, "error");
            }
        })
    },
    format: function(date){
      if(date == null || date == "") return null
      var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
    },
    beforeOpen: function(event){
        this.itemID = event.params.itemID
        if(event.params.warranty)
            this.warranty_date = event.params.warranty
    },
    beforeClose: function(){
        this.warranty_date = null
        this.itemID = null
    }
  },
  components: {
      DatePicker
  }
}
</script>
<style>
.v--modal-overlay .v--modal-box{
    overflow: visible !important;
}
.mx-datepicker{
    margin: 20px auto !important;
}
</style>
