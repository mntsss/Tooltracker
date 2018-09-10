<template>
    <div class="loading-parent">
        <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>

  <div class="container" style="min-height: 70vh !important" v-if="items">
    <div class="card">
      <v-layout row wrap align-center class="card-header pb-0 pt-0 mx-0 theme--dark v-toolbar">
          <v-flex>
              <div class="text-center headline">Ištrinti įrankiai</div>
          </v-flex>
      </v-layout>
      <div class="card-body bg-dark" v-if="items.length > 0">
        <router-link tag="div" class="row remove-side-margin cursor-pointer" :to="{ name: 'item', params: { itemProp: {item: item, state: 'Ištrintas'}}}" v-for="item in items" :key="item.ItemID">
          <div class="col-6">
            {{item.ItemName}}
          </div>
          <div class="col text-center">
            {{item.state}}
          </div>
      </router-link>
      </div>
      <div class="card-body bg-dark mt-1 border border-dark" v-else-if="items.length == 0">
        <div class="text-center text-light h5 pa-5">
          Ištrintų įrankių nėra...
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>

import RenameModal from '../modals/RenameGroup.vue'
import ChangeImageModal from '../modals/ChangeGroupImage.vue'
import CreateItemModal from '../modals/CreateItem.vue'
import swal from 'sweetalert'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'

  export default {
    data(){
      return {
        items: [],
        itemGroup: null,
        isLoading: true,
        fullPage: false
      }
    },
    async created(){
        this.loadItems()
    },
    computed:{
    },
    methods: {
        loadItems: function(){
          return this.$http.get('/item/deleted/').then((response)=>{
              if(response.status==200){
                  this.items = response.data;
                  this.isLoading = false;
              }
          }).catch(error => {
            swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], "error");
          })
        }
    },
    components: {
      Loading
    }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
