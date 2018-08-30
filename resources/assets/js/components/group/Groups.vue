<template>
  <div class="loading-parent" style="height: 70vh">
      <Loading :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="fullPage"></Loading>

  <div class="container">
    <CreateGroup></CreateGroup>
    <v-layout row mx-0 mb-3 align-center justify-center class="theme--dark v-toolbar">
        <v-flex shrink headline>
            Įrankių grupės
        </v-flex>
    </v-layout>
    <div class="item-box-panel" @click="$modal.show('create-group-modal')">
        <div class="item-add-box" style="height: 100% !important">
            <span class="fas fa-plus text-success"></span>
        </div>
    </div>
    <router-link tag="div" class="item-box-panel" :to="{ path: '/group/'+group.ItemGroupID}" v-for="group in itemGroups" :key="group.ItemGroupID">
        <div class="item-image-box" v-if="group.ItemGroupImage">
            <img :src="group.ItemGroupImage" alt="item-img" class="item-img"/>
        </div>
        <div class="item-image-box" v-if="!group.ItemGroupImage">
          <div class="item-noimg">
              <span class="far fa-folder-open text-warning"></span>
          </div>
        </div>
        <div class="row item-name-field remove-all-margin">
            <div class="col-12 item-text remove-all-padding">
              {{group.ItemGroupName}}
            </div>
        </div>
    </router-link>
  </div>
</div>
</template>
<script>
import CreateGroup from '../modals/CreateGroup.vue';
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
    console.log(this.code)
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
