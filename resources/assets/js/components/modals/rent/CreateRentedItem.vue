<template>
  <modal name="create-rented-item-modal"
         height="auto"
         :adaptive="true"
         :scrollable="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen"
         @before-close="beforeClose">
    <div class="card">
        <div class="card-header secondary v-toolbar headline text-center">
            Pridėti nuomotą įrankį <a @click="$modal.hide('create-rented-item-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
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
                      <v-menu
                        :close-on-content-click="false"
                        v-model="menu"
                        :nudge-right="40"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        min-width="290px"
                      >
                        <v-text-field
                          slot="activator"
                          v-model="rentDate"
                          prepend-icon="event"
                          label="Nuomos pradžios data"
                          readonly
                        ></v-text-field>
                        <v-date-picker v-model="rentDate" @input="menu = false" first-day-of-week="1" locale="lt"></v-date-picker>
                      </v-menu>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="number" class="col-md-4 control-label">Dienos kaina</label>

                    <div class="col-md-6">
                      <div class="input-group">
                        <span class="input-group-addon"><v-icon small class="primary--text mt-2 mx-2">fa-euro-sign</v-icon></span>
                        <input type="number" v-model="price" min="0" step="0.01" data-number-to-fixed="2" class="form-control"/>
                      </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <div class="col-md-10">
                      <textarea class="form-control" placeholder="Komentaras" v-model="note" maxlength="500" rows="5"></textarea>
                    </div>
                </div>

                <v-layout row mx-0 mx-0 pa-2 align-center justify-center>
                  <v-flex shrink>
                    <v-progress-circular
                      indeterminate
                      color="primary"
                      v-if="pendingRequest"
                    ></v-progress-circular>
                    <v-btn v-else outline color="primary" :disabled="!name || rentDate == '' || price == 0" @click="save()">
                            Pridėti
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
import DatePicker from '../../modules/DatePicker.vue'
export default {
    data(){
        return {
            menu: false,
            name: null,
            rentDate: this.format(new Date()),
            price: 0,
            note: null,
            pendingRequest: false
        }
    },
  methods: {
    save: function(){
      this.pendingRequest = true
        this.$http.post('/rented/create',
        {
            name: this.name,
            rentDate: this.format(this.rentDate),
            price: this.price,
            note: this.note
        }
      ).then((response)=>{
        this.pendingRequest = false
            if(response.status == 200){
                this.$modal.hide('create-rented-item-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadItems();
            }
        }).catch(error =>{
          this.pendingRequest = false
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
    },
    beforeClose: function(){
      this.name = null
      this.rentDate = ''
      this.price = 0
      this.note = null
    },
    date_changed: function(event){
      this.rentDate = event.date
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
