<template>
  <modal name="create-item-modal"
         height="auto"
         :adaptive="true"
         transition="pop-out"
         :pivotY="0.3"
         @before-open="beforeOpen">
    <div class="card">
        <div class="card-header bg-dark text-light">
            Pridėti įrankį <a @click="$modal.hide('create-item-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <div class="card-body">
            <form class="" method="POST" @submit.prevent="save">
                <div class="form-group mb-4">
                    <label for="name" class="col-md-4 control-label text-dark">Pavadinimas</label>

                    <div class="col-md-6">
                        <input id="name" type="name" class="form-control" name="name" v-model="name" maxlength="45" required autofocus>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="name" class="col-md-4 control-label text-dark">Įsigijimo data</label>

                    <div class="col-md-6">
                        <DatePicker v-model="purchase_date" :lang="lang" format="YYYY-MM-DD"></DatePicker>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="name" class="col-md-4 control-label text-dark">Garantinis iki</label>

                    <div class="col-md-6">
                        <DatePicker v-model="warranty_date" :lang="lang" format="YYYY-MM-DD"></DatePicker>
                    </div>
                </div>
                <div class="form-group row remove-side-margin">
                  <label class="col-auto control-label text-dark mb-4" for="consumable">
                    Suvartojama
                  </label>
                  <div class="col align-left">
                    <input class="remove-all-margin" type="checkbox" value="" id="consumable" v-model="consumable" style="height:23px; width:23px">
                  </div>
                </div>

                <div class="form-group mb-4">
                        <label for="image" class="col-md-4 control-label text-dark">Nuotrauka</label>

                        <div class="col-md-6 text-right">
                             <input name="image" type="file" id="image" ref="image" v-on:change="handleFileUpload" class="form-control" accept="image/*">
                        </div>
                    </div>

                <div class="form-group d-flex justify-content-center p-3">
                        <button type="submit" class="btn btn-primary">
                            Pridėti
                        </button>
                </div>
            </form>
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
            image: null,
            code: null,
            consumable: false,
            warranty_date: '',
            purchase_date: '',
            groupID: null,
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
    computed: {
        visibility: function(){
            return this.$children[0].visibility.modal
        },
        RFIDCode: function(){
            return this.$store.state.recentCode
        }
    },
    watch: {
        RFIDCode(oldRFIDCode, newRFIDCode){
            if(this.visibility){
                this.code = this.RFIDCode;
            }
        }
    },
  methods: {
    save: function(){
        var form = new FormData();

        form.append('name', this.name)
        form.append('image', this.image)
        if(this.code != null && this.code != "")form.append('code', this.code)
        if(this.consumable == true) form.append('consumable', 1)
        if(this.consumable == false) form.append('consumable', 0)
        if(this.warranty_date != null && this.warranty_date != "") form.append('warranty_date', this.format(this.warranty_date))
        if(this.purchase_date != null && this.purchase_date != "") form.append('purchase_date', this.format(this.purchase_date))
        form.append('groupID', this.groupID)

        this.$http.post('/item/create', form,
        {
            headers: {'Content-Type': 'multipart/form-data'}
        }
      ).then((response)=>{
            if(response.status == 200){
                this.$modal.hide('create-item-modal')
                swal(response.data.message, response.data.success, "success")
                this.$parent.loadItems();
                this.name = null
                this.image = null
                this.code = null
                this.consumable = false
                this.warranty_date = ''
                this.purchase_date= ''
            }
        }).catch(error =>{

            if(error.response.status == 422)
            {
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
            }
            if(error.response.status == 413)
            {
                swal("Klaida", "Failo dydis netinkamas!", "error");
            }
        })
    },
    handleFileUpload: function(){
        this.image = this.$refs.image.files[0];
    },
    beforeOpen: function(event){
      this.groupID = event.params.groupID
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
    DatePicker
  }
}
</script>
