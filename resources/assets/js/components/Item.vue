<template>
<div class="loading-parent">
    <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>
    <div class="container">

    <div class="card">
      <div class="card-header">
          <a @click="$router.push({ path: '/group/'+item.ItemGroupID})"><h4 class="fa fa-arrow-left text-primary remove-all-margin p-2 nav-arrow"></h4></a>

          <div class="dropdown show">
            <a class="fas fa-ellipsis-v text-primary btn-func-misc ml-2 mr-2 mb-0 mt-0" id="dropdownGroupFunc" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownGroupFunc">
              <a class="dropdown-item cursor-pointer">Priskirti čipą</a>
              <a class="dropdown-item cursor-pointer">Pervadinti</a>
              <a class="dropdown-item cursor-pointer">Keisti nuotrauką</a>
              <a class="dropdown-item cursor-pointer">Ištrinti</a>
            </div>
          </div>
          <h4 class="text-dark text-center">{{item.ItemName}}</h4>

      </div>
      <div class="card-body">
        <div class="row remove-side-margin">
            <div class="col-auto">
                <vueImages :imgs="images"
                            :modalclose="modalclose"
                            :keyinput="keyinput"
                            :showclosebutton="showclosebutton" class="image">
                </vueImages>
            </div>
            <div class="colt">
                <p class="text-left h4">
                    <span class="fab fa-ethereum"></span>&nbsp;Būsena:&nbsp;{{itemStatus}}
                </p>
                <p class="text-left h4" v-if="item.ItemPurchase != null">
                    <span class="fas fa-calendar-alt"></span>&nbsp;Įsigijimo data:&nbsp;{{item.ItemPurchase}}
                </p>
                <p class="text-left h4" v-if="item.ItemWarranty != null">
                    <span class="fas fa-calendar-alt"></span>&nbsp;Garantinis iki:&nbsp;{{item.ItemWarranty}}
                </p>
                <p class="text-left h4" v-if="item.ItemConsumable">
                    <span class="fas fa-check"></span>&nbsp;Suvartojama
                </p>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import vueImages from 'vue-images'
import swal from 'sweetalert'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'

  export default {
    data(){
      return {
          item: '',
          itemStatus: '',
          images: [],
          modalclose: true,
          keyinput: true,
          mousescroll: true,
          showclosebutton: true,
          showcaption: true,
          isLoading: true,
          fullPage: false
      }
  },
  props: {
      itemProp: {
          required: true,
          type: Object
      }
  },
  created(){
      this.item =  this.itemProp.item
      this.itemStatus = this.itemProp.state
      this.images.push({imageUrl: '/media/uploads/'+this.item.ItemImage, caption: this.item.ItemName})
  },
  mounted(){
    this.isLoading = false  
  },
  methods: {
    loadItem: function(){
            this.isLoading = true
        this.$http.get('/item/get/'+this.itemID).then((response)=>{
            if(response.status == 200){
                this.itemStatus = response.data.state
                this.item = response.data.item
                this.isLoading = false;
            }
        }).catch(error => {
            if(error.response.status == 422){
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
                this.isLoading = false
            }
        })
    }
  },
  components: {
      vueImages,
      Loading
  }
}
</script>
<style>
    .image{
        width: 100%;
        height: auto;
        overflow: hidden;
    }
    .loading-parent{
        position: relative;
    }
    .loading-overlay .loading-background {
        opacity: 1 !important;
        background-color: #F5F8FA !important;
    }
    </style>
