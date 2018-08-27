<template>
  <div class="loading-parent" style="height: 70vh">
      <Loading :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="fullPage"></Loading>
      <div class="overlay position-absolute h-100 w-100 bg-dark" v-if="!userCard">
        <v-container>
          <v-layout align-center justify-center row fill-height mt-5>
            <v-flex shrink mt-5>
              <v-progress-circular
              :size="100"
              :width="7"
              color="white"
              indeterminate
              ></v-progress-circular>
            </v-flex>
          </v-layout>
          <v-layout align-center justify-center>
            <v-flex shrink align-center class="h4 text-danger text-center mt-3">
              Laukiama identifikacinės kortelės...
            </v-flex>
          </v-layout>
        </v-container>
      </div>
    <v-dialog v-model="waitingImageDialog" persistent max-width="780">
      <v-container>
        <v-layout row headline justify-center><v-flex shrink v-if="responseItem">{{responseItem.ItemName}}</v-flex></v-layout>
        <v-layout row>
          <image-uploader
            :debug="1"
            :maxWidth="1024"
            :quality="0.7"
            :autoRotate=true
            outputFormat="verbose"
            :preview=false
            :className="['fileinput', { 'fileinput--loaded' : hasImage }]"
            capture="environment"
            @input="setImage"
          ></image-uploader>
        </v-layout>
      </v-container>
    </v-dialog>
  <v-container>
      <v-tabs
        slot="extension"
        v-model="tab"
        color="dark"
        grow
      >
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
      responseItem: null,
      uploadedImage: null,
      hasImage: false
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
                  this.responseItem = response.data
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
        this.uploadedImage = file
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
</style>
