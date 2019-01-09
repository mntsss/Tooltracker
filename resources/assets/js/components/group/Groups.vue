<template>
  <div class="container" style="overflow: auto !important" v-if="storage">
    <CreateGroup></CreateGroup>
    <v-layout row mx-0 mx-0 mb-3 align-center justify-center class="primary">
        <v-flex shrink headline class="text-white text-center">
            Sandėlio <u>{{storage.name}}</u> grupės
        </v-flex>
    </v-layout>
    <div class="item-box-panel" @click="$modal.show('create-group-modal')" v-if="$user.UserRole =='Administratorius'">
        <div class="item-add-box" style="height: 100% !important">
            <span class="fas fa-plus text-success"></span>
        </div>
    </div>
    <group-card :group="group" v-for="(group, i) in storage.groups" :key="i"></group-card>
  </div>

</template>
<script>
import CreateGroup from '../modals/group/create.vue';
import groupCard from './modules/GroupCard.vue';
import swal from 'sweetalert'
export default {
  props: {
    storage_id: {
      required: true,
      type: Number
    }
  },
  created(){
    //this.loadGroups()
    this.$contentLoadingHide();
  },
  updated(){
    this.equalizeHeigth()
  },
  computed:{
    storage: function (){
      return this.$store.state.storage.storage;
    }
  },
  methods: {
    loadGroups: function(){
      if(!this.storage){
        this.$store.dispatch('storage/LOAD_STORAGE', {id: this.storage_id});
      }
      else
      if(this.storage_id != this.storage.id)
      {
        this.$store.dispatch('storage/LOAD_STORAGE', {id: this.storage_id});
      }

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
    groupCard
  }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
