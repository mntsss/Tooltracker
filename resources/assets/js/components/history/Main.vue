<template>
    <div class="container" style="min-height: 70vh !important">
      <v-toolbar
      color="primary"
      dark
      tabs
      >
        <v-layout aling-center justify-center><v-flex shrink headline white--text>Istorija</v-flex></v-layout>
      </v-toolbar>
      <FilterBox :users = 'users' @filter = "loadHistory" @clear="loadHistory"></FilterBox>
       <v-card flat>
         <v-card-text>
           <ActionsTable :actions = 'actions' @clicked="goToItem"></ActionsTable>
         </v-card-text>
       </v-card>
    </div>
</template>
<script>
import swal from 'sweetalert'
import DatePicker from 'vue2-datepicker'
import ActionsTable from './modules/actionsTable.vue'
import FilterBox from '../modules/FilterBox.vue'
export default{
  created(){
    this.loadUsers().then(() => this.loadHistory().then(() => this.$contentLoadingHide()));
  },
  computed: {
    actions(){
      return this.$store.state.history.actions;
    },
    users(){
      return this.$store.state.history.users;
    }
  },
  methods:{
    loadHistory: function(payload){
      if(payload){
        let {user, from, til} = payload;
        if(user)
        {
          return this.$store.dispatch('history/LOAD_USER_ACTIONS', {user, from, til})
        }
        else {
          return this.$store.dispatch('history/LOAD_ACTIONS', {from, til})
        }
      }else {
        return this.$store.dispatch('history/LOAD_ACTIONS', {from:'', til:''});
      }
    },
    loadUsers: function(){
      return this.$store.dispatch('history/LOAD_USERS');
    },
    goToItem: function(payload){
      this.$router.push({ name: 'item', params: { itemID: payload.item.ItemID}})
    }
  },
  components:{
    DatePicker,
    ActionsTable,
    FilterBox
  }
}
</script>
