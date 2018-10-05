<template>
  <modal name="edit-rented-item-modal"
         height="auto"
         :adaptive="true"
         :scrollable="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div class="card">
        <div class="card-header secondary v-toolbar headline text-center">
            Redaguoti nuomotą įrankį <a @click="$modal.hide('edit-rented-item-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body">
            <v-form>
                <div class="form-group mb-4">
                    <label for="name" class="col-md-4 control-label">Pavadinimas</label>

                    <div class="col-md-6">
                        <input id="name" type="name" class="form-control" name="name" v-model="name" maxlength="45" required autofocus>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="name" class="col-md-4 control-label">Nuomos pradžia</label>

                    <div class="col-md-6">
                        <DatePicker v-model="rentDate" :lang="lang" format="YYYY-MM-DD"></DatePicker>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="number" class="col-md-4 control-label">Dienos kaina</label>

                    <div class="col-md-6">
                      <div class="input-group">
                        <span class="input-group-addon"><v-icon small class="mt-2 mx-2 primary--text">fa-euro-sign</v-icon></span>
                        <input type="number" v-model="price" min="0" step="0.01" data-number-to-fixed="2" class="form-control"/>
                      </div>
                    </div>
                </div>

                <v-layout row mx-0 mx-0 pa-2 align-center justify-center>
                  <v-flex shrink>
                    <v-btn outline color="primary" :disabled="!name || rentDate == '' || price == 0" @click="save()">
                            Išsaugoti
                        </v-btn>
                  </v-flex>
                </v-layout>
            </v-form>
        </div>
    </div>
  </modal>
</template>
<script>
import swal from 'sweetalert'
import DatePicker from 'vue2-datepicker'
export default {
    data(){
        return {
            name: null,
            rentDate: '',
            price: 0,
            id: null,
            lang: {
              days: ['Pr', 'An', 'Tr', 'Ket', 'Pn', 'Še', 'Sek'],
              months: ['Sau', 'Vas', 'Kov', 'Bal', 'Geg', 'Bir', 'Lie', 'Rugp', 'Rugs', 'Spa', 'Lap', 'Gru'],
              placeholder: {
                date: 'Pasirinkite datą',
                dateRange: 'Pasirinkite laikotarpį'
              }
            }
        }
    },
  methods: {
    save: function(){
        this.$http.post('/rented/edit',
        {
            name: this.name,
            rentDate: this.format(this.rentDate),
            price: this.price,
            id: this.id
        }
      ).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('edit-rented-item-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadItem();
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
    beforeOpen: function(event){
        this.name = event.params.item.RentedItemName
        this.rentDate = new Date(event.params.item.RentedItemDate)
        this.price = event.params.item.RentedItemDailyPrice
        this.id = event.params.item.RentedItemID

    },
    beforeClose: function(){
      this.name = null
      this.rentDate = ''
      this.price = 0
      this.id = null
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
    }
  },
  components: {
    DatePicker,
  }
}
</script>
