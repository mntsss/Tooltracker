<template>
  <div class="loading-parent" style="height: 70vh">
      <Loading :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="fullPage"></Loading>

  <div class="container" style="overflow: auto !important">
    <CreateGroup></CreateGroup>
    <v-layout row mx-0 mb-3 align-center justify-center class="primary">
        <v-flex shrink headline class="text-white">
            Įrankių grupės
        </v-flex>
    </v-layout>
    <div class="item-box-panel" @click="$modal.show('create-group-modal')" v-if="$user.UserRole =='Administratorius'">
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
        <div class="row item-name-field ma-0">
              {{group.ItemGroupName}}
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
  },
  updated(){
    this.equalizeHeigth()
  },
  methods: {
    loadGroups: function(){
      this.$http.get('/group/list').then((response)=>{
        if(response.status == 200)
          this.itemGroups = response.data;
          this.isLoading = false
      }).catch(error => {
          swal('Klaida', error.response.data.message, 'error')
      })
    },
    equalizeHeigth: function(){
      var boxes = document.getElementsByClassName("item-box-panel");
      var maxHeight = 0;
      for (var i = 0; i < boxes.length; i++) {
        if(boxes[i].clientHeight > maxHeight)
          maxHeight = boxes[i].clientHeight
      }
      for (var i = 0, len = boxes.length; i < len; i++) {
        boxes[i].style.height = maxHeight+"px"
      }

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
