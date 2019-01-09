<template>
  <router-link tag="div" class="item-box-panel" :to="{ path: '/group/'+group.ItemGroupID}">
      <div class="item-image-box" v-if="group.ItemGroupImage">
          <img :src="group.ItemGroupImage" alt="item-img" class="item-img"/>
      </div>
      <div class="item-image-box" v-if="!group.ItemGroupImage">
        <div class="item-noimg">
            <span class="far fa-folder-open text-warning"></span>
        </div>
      </div>
      <div class="row mx-0 item-name-field ma-0">
            {{group.ItemGroupName}}
      </div>
  </router-link>
</template>
<script>
  export default {
    props: {
      group: {
        required: true,
        type: Object
      }
    }
  }
</script>
