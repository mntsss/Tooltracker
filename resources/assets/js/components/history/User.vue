<template>
      <div class="container" style="min-height: 70vh !important">
        <div class="card" v-if="user">
          <v-layout row mx-0 wrap align-center class="card-header pb-0 pt-0 mx-0 secondary v-toolbar" >
              <v-flex headline shrink justify-start align-content-center>
                  <a @click="$back()" class="headline"><span class="fa fa-arrow-left primary--text remove-all-margin p-2 btn-func-misc"></span></a>
              </v-flex>
              <v-flex>
                  <div class="text-center headline" v-if="user">{{user.Username}} istorija</div>
              </v-flex>
          </v-layout>
          <FilterBox :user_filter='false' @filter = "loadHistory" @clear="loadHistory"></FilterBox>
           <v-card flat>
             <v-card-text>
               <ActionsTable :actions = 'actions' @clicked="goToItem"></ActionsTable>
             </v-card-text>
           </v-card>
        </div>
      </div>
</template>
<script>
import swal from 'sweetalert'
import ActionsTable from './modules/actionsTable.vue'
import FilterBox from '../modules/FilterBox.vue'
export default{
    props:{
        user: {
            required: true,
            type: Object
        }
    },
    created(){
      this.loadHistory().then(() => this.$contentLoadingHide());
    },
    computed: {
      actions(){
        return this.$store.state.history.actions;
      }
    },
    methods:{
      loadHistory: function(payload){
        if(payload){
          let {from, til} = payload;
          return this.$store.dispatch('history/LOAD_USER_ACTIONS', {user:this.user.UserID, from, til})
        }
        else {
          return this.$store.dispatch('history/LOAD_USER_ACTIONS', {user:this.user.UserID, from: '', til: ''})
        }
      },
      goToItem: function(payload){
        this.$router.push({ name: 'item', params: { itemID: payload.item.ItemID}})
      }
    },
    components: {
      ActionsTable,
      FilterBox
    }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
