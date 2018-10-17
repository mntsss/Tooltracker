<template>
  <v-container class="hill-height" v-else>
    <v-layout mx0 align-center justify-center>
      <v-flex shrink><v-icon class="text-success display-3">fa-check</v-icon></v-flex>
    </v-layout>
    <v-layout align-center justify-center>
      <v-flex shrink class="text-center h5">
         <slot name="no-data-text">
           
         </slot>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
export default{
  data(){
    return{

    }
  }
}
</script>
