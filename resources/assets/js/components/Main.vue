<template>
  <div class="container">
    <CreateGroup></CreateGroup>
    <div class="item-box-panel" onclick="$('#categoryAdd').modal()">
        <div class="item-add-box" style="height: 100% !important">
            <span class="fas fa-plus text-success"></span>
        </div>
    </div>
    <div class="item-box-panel" onclick="alert('group desc box!')" v-for="group in itemGroups">
        <div class="item-image-box" v-if="group.ItemGroupImage">
            <img :src="'media/uploads/'+group.ItemGroupImage" alt="item-img" class="item-img"/>
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
    </div>
  </div>
</template>
<script>
import CreateGroup from './modals/CreateGroup.vue';
export default {
  data(){
    return {
      itemGroups: [],
    }
  },
  mounted(){
    this.loadGroups()
  },
  computed: {
    username: function(){
      return this.$auth.user().UserName
    }
  },
  methods: {
    loadGroups: function(){
      this.$http.get('/group/list').then((response)=>{
      this.itemGroups = response.data;
      })
    }
  },
  components: {
    CreateGroup
  }
}
</script>
