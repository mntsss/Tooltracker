<template>
  <div class="loading-parent" style="height: 70vh">
      <Loading :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="fullPage"></Loading>
      <div class="overlay position-absolute h-100 w-100 bg-dark" v-if="!userCard">
        <v-container>
          <v-layout align-center justify-center row fill-height mt-5>
            <v-flex shrink mt-5>
              <v-progress-circular :size="100" :width="7" color="white" indeterminate></v-progress-circular>
            </v-flex>
          </v-layout>
          <v-layout align-center justify-center>
            <v-flex shrink align-center class="h4 text-danger text-center mt-3">
              Laukiama identifikacinės kortelės...
            </v-flex>
          </v-layout>
        </v-container>
      </div>
    <v-dialog v-model="waitingImageDialog" persistent max-width="780" >
      <v-container v-if="newItem.item" style="background: #292929">
        <div class="card-header bg-dark text-light headline">
            {{newItem.item.ItemName}} <a @click="cancelItemAddition()" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
        </div>
        <v-divider light></v-divider>
        <v-layout row wrap v-if="newItem.item.ItemConsumable">
          <v-flex shrink>
            <v-text-field type="number" v-model="newItem.quantity" placeholder="Paimamas kiekis"></v-text-field>
          </v-flex>
        </v-layout>
        <v-layout row align-center justify-center v-if="!newItem.item.ItemConsumable">
          <v-flex shrink v-if="!newItem.image">
            <image-uploader :debug="0" :maxWidth="1024" :quality="0.7" :autoRotate=true outputFormat="verbose" :preview=false :className="['fileinput', { 'fileinput--loaded' : hasImage }]"
              capture="environment" @input="setImage" @onUpload="loadingDialog" @onComplete="loadingDialog"></image-uploader>
          </v-flex>
          <v-flex shrink v-else-if="newItem.image">
            <img v-bind:src="newItem.image.dataUrl" alt="uploaded_image" class="uploaded-image" />
          </v-flex>

          <!-- Loading modal appearing on upload start and loading til upload and resize ends -->
            <v-dialog v-model="imageLoadingDialog" hide-overlay persistent width="300">
              <v-card dark class="border border-danger">
                <v-card-text>
                  Kraunama...
                  <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
              </v-card>
            </v-dialog>

        </v-layout>
        <v-layout row wrap mx-0 pa-3 justify-center align-center>
          <v-flex shrink><v-btn outline @click="addToReservation" :disabled="addButtonDisabled"><v-icon class="text-danger headline mx-2">fa-plus</v-icon>Pridėti į rezervaciją</v-btn></v-flex>
        </v-layout>
      </v-container>
    </v-dialog>
    <v-container>
        <v-select
            :items="objects"
            v-model="reservationObject"
            menu-props="auto"
            label="Pasirinkite rezervacijos objektą"
            hide-details
            item-text="ObjectName"
            item-value="ObjectID"
            prepend-icon="fa-building"
            outline
            class="mb-4 mt-2"
        ></v-select>
        <v-data-table :headers="headers" :items="reservedItems" hide-actions class="elevation-1">
            <template slot="items" slot-scope="props">
              <td>{{ props.item.item.ItemName }}</td>
              <td class="text-xs-center">{{ props.item.quantity }}</td>
              <td class="justify-center layout px-0">
                  <v-btn><v-icon @click="deleteItem(props.item)">delete</v-icon></v-btn>
              </td>
            </template>
            <template slot="no-data">
              <v-alert :value="true" class="bg-warning" icon="warning">
                Laukiama rezervuojamų įrankių...
              </v-alert>
            </template>
          </v-data-table>
          <v-layout row wrap mx-0 pa-3 justify-center align-center>
            <v-flex shrink><v-btn outline @click="save" :disabled="saveButtonDisabled"><v-icon class="text-danger headline mx-2">fa-save</v-icon>Išsaugoti rezervaciją</v-btn></v-flex>
          </v-layout>
  </v-container>
</div>
</template>
<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import swal from 'sweetalert'
import { ImageUploader } from 'vue-image-upload-resize'
export default{

  data(){
    return {
      isLoading: true,
      fullPage: false,

      waitingImageDialog: false,
      imageLoadingDialog: false,
      hasImage: false,

      userCard: false,
      user: null,
      objects: [],
      reservationObject: null,

      newItem: {
        item: null,
        image: null,
        quantity: 1
      },
      reservedItems: [],
      //table setup
      headers: [
          {
            text: 'Įrankio (daikto) pavadinimas',
            align: 'left',
            sortable: false,
            value: 'item.ItemName'
          },
          { text: 'Kiekis (vnt.)', value: 'quantity' },
          { text: '', value: 'value' }
        ],
    }
  },
  created(){
    this.loadObjects()
    this.fetch()
  },
  mounted(){
    this.isLoading = false
    this.user = this.$auth.user()
  },
  computed: {
    RFIDCode: function(){
        return this.$store.state.recentCode
    },
    addButtonDisabled: function(){
      if(this.newItem.item.ItemConsumable)
        return false
      else return !this.hasImage;
    },
    saveButtonDisabled: function(){
      if(this.reservationObject){
        if(this.reservedItems.length != 0)
          if(this.userCard)
            return false
      }
      return true
    }
  },
  watch: {
    RFIDCode(oldRFIDCode, newRFIDCode){
        if(this.RFIDCode){
            if(!this.userCard){
              if(this.user.UserRFIDCode == this.RFIDCode)
                this.userCard = true
            }
            else if(this.userCard){
              this.$http.post('/item/findcode', {code: this.RFIDCode}).then((response) => {
                if(response.status == 200)
                  if(response.data.status == null){
                    this.newItem.item = response.data.item
                    this.waitingImageDialog = true
                  }
                  else if(response.data.status == 'reserved')
                    swal("Klaida!", 'Įrankis jau yra pridėtas aktyvioje rezervacijoje...', 'error')
                  else if(response.data.status == 'withdrew')
                    swal("Klaida!", 'Įrankis yra naudojamas ir negali būti pridėtas į rezervaciją!', 'error')
                  else if(response.data.status == 'suspended')
                    swal("Klaida!", 'Įrankis yra įšaldytas, todėl negali būti pridėtas į rezervaciją!', 'error')
                  else if(response.data.status == 'deleted')
                    swal("Klaida!", 'Įrankis yra ištrintas, todėl negali būti pridėtas į rezervaciją!', 'error')
              }).catch(error => {
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
              })
            }
            this.$store.commit('resetCode')
        }
    }
  },
  methods: {
    fetch() {
        this.$auth.fetch({
          success(response)
          {
            this.user = response.data;
          },
          error(error)
          {
            console.log('error ' + error);
          }
      });
    },
    loadObjects: function(){
      this.$http.get('/object/list').then((response) => {
        if(response.status == 200){
          this.objects = response.data
        }
      }).catch(error => {
        swal('Klaida!',error.response.data.message, 'error');
      })
    },
    setImage: function(file){
        this.hasImage = true
        this.newItem.image = file
    },
    loadingDialog: function(){
      this.imageLoadingDialog = !this.imageLoadingDialog
    },
    addToReservation: function(){
      this.reservedItems.push(JSON.parse(JSON.stringify(this.newItem)))
      this.hasImage = false
      this.newItem.item = null
      this.newItem.image = null
      this.newItem.quantity = 1
      this.waitingImageDialog = false
    },
    cancelItemAddition: function(){
        this.hasImage = false
        this.newItem.item = null
        this.newItem.image = null
        this.newItem.quantity = 1
        this.waitingImageDialog = false
    },
    save: function(){
      this.$http.post('/reservation/create', {
        objectID: this.reservationObject,
        items: this.reservedItems
      }).then((response) => {
        swal(response.data.message, response.data.success, "success").then(value => {this.$router.push({name: 'reservations'})})
      }).catch(error => {
        if(error.response.status == 422)
        {
            swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
        }
        else{
            swal("Klaida", error.response.data.message, "error");
        }
      })
  },
  deleteItem (item) {
        const index = this.reservedItems.indexOf(item)
        this.reservedItems.splice(index, 1)
      },
  },
  components: {
    Loading,
    ImageUploader
  }
}
</script>
<style>
  .loading-parent{
    position: relative;
  }
  .overlay{
    z-index: 5;
    background-color: #303030 !important;
  }
  .loading-overlay{
    z-index: 10 !important;
  }
  .uploaded-image{
    max-width: 576px;
    padding: 10px;
    max-height: 50vh;
  }
</style>
