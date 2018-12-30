<template>
  <div class="container" style="min-height: 70vh !important" v-if="items">
    <div class="card">
      <v-layout row mx-0 wrap align-center class="card-header pb-0 pt-0 mx-0 secondary">
          <v-flex>
              <div class="text-center headline">Ištrinti įrankiai</div>
          </v-flex>
      </v-layout>
      <div class="card-body" v-if="items.length > 0">
        <router-link tag="div" class="row mx-0 remove-side-margin cursor-pointer" :to="{ name: 'item', params: { itemID: item.ItemID}}" v-for="item in items" :key="item.ItemID">
          <div class="col-6">
            {{item.ItemName}}
          </div>
          <div class="col text-center">
            {{item.updated_at}}
          </div>
      </router-link>
      </div>
      <div class="card-body mt-1 border border-dark" v-else-if="items.length == 0">
        <div class="text-center h5 pa-5">
          Ištrintų įrankių nėra...
        </div>
      </div>
    </div>
  </div>
</template>
<script>

import RenameModal from '../modals/RenameGroup.vue'
import ChangeImageModal from '../modals/ChangeGroupImage.vue'
import CreateItemModal from '../modals/item/CreateItem.vue'
import swal from 'sweetalert'
  export default {
    data(){
      return {
        items: [],
        itemGroup: null
      }
    },
    async created(){
        this.loadItems()
    },
    methods: {
        loadItems: function(){
          return this.$http.get('/item/deleted').then((response)=>{
              if(response.status==200){
                  this.items = response.data;
                  this.$contentLoadingHide()
              }
          }).catch(error => {
            swal('Klaida', error.response.data.message, 'error')
          })
        }
    }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
