<template>

  <div class="container" style="min-height: 70vh !important" v-if="items">
      <CreateRentedItem></CreateRentedItem>
    <div class="card">
      <v-layout row mx-0 wrap align-center class="card-header pb-0 pt-0 mx-0 secondary">
          <v-flex>
              <div class="text-center headline">Nuomoti įrankiai</div>
          </v-flex>
          <v-flex shrink headline justify-end align-content-center v-if="$user.UserRole =='Administratorius'">
            <a @click="show('create-rented-item-modal')" class="headline"><span class="fas fa-plus primary--text p-2 ml-2 mr-2 mb-0 mt-0 btn-func-misc"></span></a>
          </v-flex>
      </v-layout>
      <div class="card-body" v-if="items.length > 0">
        <router-link tag="div" class="row mx-0 remove-side-margin cursor-pointer" :to="{ name: 'rentedItem', params: { itemProp: item}}" v-for="(item, index) in items" :key="index">
          <div class="col-6">
            {{item.RentedItemName}}<span class="ml-2" v-if="item.RentedItemDate">({{calcBusinessDays(item.RentedItemDate)*item.RentedItemDailyPrice}} &euro;)</span>
          </div>
          <div class="col text-center">
            {{itemState(item)}}
          </div>
      </router-link>
      </div>
      <div class="card-body mt-1 border border-dark" v-else-if="items.length == 0">
        <div class="text-center h5 pa-5">
          Išnuomotų įrankių nėra...
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import swal from 'sweetalert'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import CreateRentedItem from '../modals/rent/CreateRentedItem.vue'
import renttime from '../../mixins/renttime'
  export default {
    mixins: [renttime],
    data(){
      return {
        items: [],
        fullPage: false
      }
    },
    async created(){
        this.loadItems()
    },
    methods: {
        show (name) {
            this.$modal.show(name);
        },
        loadItems: function(){
          return this.$http.get('/rented/get').then((response)=>{
              if(response.status==200){
                  this.items = response.data;
                  this.$contentLoadingHide()
              }
          }).catch(error => {
            swal('Klaida', error.response.data.message, 'error')
          })
        },
        itemState: function(item){
          if(!item.cobject)
            return "Sandėlyje"
          else{
            return "Naudojamas ("+item.cobject.ObjectName+")";
          }
        }
    },
    components: {
      CreateRentedItem
    }
}
</script>
