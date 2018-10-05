<template>
  <div class="container" style="min-height: 70vh !important" v-if="items">
    <div class="card">
      <v-layout row mx-0 wrap align-center class="card-header pb-0 pt-0 mx-0 secondary">
          <v-flex>
              <div class="text-center headline">Įšaldyti įrankiai</div>
          </v-flex>
      </v-layout>
      <div class="card-body" v-if="items.length > 0">
        <router-link tag="div" class="row mx-0 remove-side-margin cursor-pointer" :to="{ name: 'item', params: { itemID: item.item.ItemID}}" v-for="item in items" :key="item.item.ItemID">
          <div class="col-6">
            {{item.item.ItemName}}
          </div>
          <div class="col text-center">
            {{item.state}}
          </div>
      </router-link>
      </div>
      <div class="card-bodymt-1 border border-dark" v-else-if="items.length == 0">
        <div class="text-center h5 pa-5">
          Grupėje įrankių nėra...
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import swal from 'sweetalert'

  export default {
    data(){
      return {
        items: [],
        itemGroup: null,
      }
    },
    async created(){
        this.loadItems()
    },
    computed:{
    },
    methods: {
        show (name) {
            this.$modal.show(name);
        },
        loadItems: function(){
          return this.$http.get('/item/suspended').then((response)=>{
              if(response.status==200){
                  this.items = response.data;
                  this.$contentLoadingHide()
              }
          }).catch(error => {
            swal('Klaida', error.response.data.message, 'error')
          })
        }
    },
    components: {

    }
}
</script>
