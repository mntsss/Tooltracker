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
          <div class="card-body">
              <withdrawals :url="'/history/user/'+user.UserID"></withdrawals>
          </div>
        </div>
      </div>
</template>
<script>
import swal from 'sweetalert'
import withdrawals from './modules/withdrawals.vue'
export default{
    data(){
        return {
            user: null,
        }
    },
    props:{
        user: {
            required: true,
            type: Object
        }
    },
    components: {
      withdrawals
    }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
