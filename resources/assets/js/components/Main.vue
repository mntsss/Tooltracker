<template>
  <div class="loading-parent" style="height: 70vh">
      <Loading :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="fullPage"></Loading>

  <v-container>
    <v-layout row mx-0 align-center justify-center>
      <v-flex shrink headline>Cia bus main puslapis...</v-flex>
    </v-layout>
  </v-container>
</div>
</template>
<script>
import CreateGroup from './modals/CreateGroup.vue';
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import swal from 'sweetalert'
export default {
  data(){
    return {
      itemGroups: [],
      isLoading: true,
      fullPage: false
    }
  },
  created(){
    this.loadGroups()

  },
  computed: {
    username: function(){
      return this.$auth.user().UserName
    },
    code: {
      get(){
        return this.$store.state.recentCode;
      },
      set(value){
        return this.$store.commit('newcode', value)
      }
    }
  },
  methods: {
    loadGroups: function(){
      this.$http.get('/group/list').then((response)=>{
        if(response.status == 200)
          this.itemGroups = response.data;
          this.isLoading = false
      }).catch(error => {
          swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], 'error')
      })
    }
  },
  components: {
    CreateGroup,
    Loading
  }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
