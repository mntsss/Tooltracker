<template>
  <v-card tile>
      <v-card-title class="secondary h5">
          <slot name="title">

          </slot>
      </v-card-title>
      <v-card-text class="white position-relative data-box-content">
          <Loading :active.sync="loading"
          :can-cancel="false"
          :is-full-page="false"></Loading>
            <slot name="data-table">

            </slot>
      </v-card-text>
  </v-card>
</template>
<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
export default{
  data(){
    return {
      loading: true
    }
  },
  mounted(){
    this.loading = false
  },
  components: {
    Loading
  }
}
</script>
