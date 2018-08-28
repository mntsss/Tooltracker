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
        <v-layout row headline justify-center mx-0 class="theme--dark v-toolbar"><v-flex shrink >{{newItem.item.ItemName}}</v-flex></v-layout>
        <v-divider light></v-divider>
        <v-layout row wrap v-if="newItem.item.ItemConsumable">
          <v-flex shrink>
            <v-text-field type="number" v-model="newItem.quantity" placeholder="Paimamas kiekis"></v-text-field>
          </v-flex>
        </v-layout>
        <v-layout row align-center justify-center v-if="!newItem.item.ItemConsumable">
          <v-flex shrink v-if="!newItem.image">
            <image-uploader :debug="1" :maxWidth="1024" :quality="0.7" :autoRotate=true outputFormat="verbose" :preview=false :className="['fileinput', { 'fileinput--loaded' : hasImage }]"
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
          <v-flex shrink><v-btn outline @click="addToReservation"><v-icon class="text-danger headline mx-2">fa-plus</v-icon>Pridėti į rezervaciją</v-btn></v-flex>
        </v-layout>
      </v-container>
    </v-dialog>
  <v-container>
      <v-tabs slot="extension" v-model="tab" color="dark" grow>
        <v-tabs-slider color="red darken-3"></v-tabs-slider>
        <v-tab>
          Rezervacija
        </v-tab>
        <v-tab>
          Priskyrimas
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tab">
      <v-tab-item>
        <v-container fill-heigth justify-center>
          <v-flex shrink align-center>Rezervacijos tabas & stuff...</v-flex>
        </v-container>
      </v-tab-item>
      <v-tab-item>
        <v-card flat>
          <v-card-text>Priskyrimo tabas & stuffs</v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
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
      tab: null,
      userCard: false,
      user: null,
      waitingImageDialog: false,
      imageLoadingDialog: false,
      hasImage: false,
      newItem: {
        item: null,
        image: null,
        quantity: 1
      },
      reservedItems: []
    }
  },
  created(){
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
                  this.newItem.item = response.data
                  this.waitingImageDialog = true
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
    setImage: function(file){
        this.hasImage = true
        this.newItem.image = file
    },
    loadingDialog: function(){
      this.imageLoadingDialog = !this.imageLoadingDialog
    },
    addToReservation: function(){
      this.reservedItems.push(JSON.parse(JSON.stringify(this.newItem)))
      this.newItem.item = null
      this.newItem.image = null
      this.newItem.quantity = 1
      this.waitingImageDialog = false
    }
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
