<template>
    <div class="loading-parent">
        <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>
        <div class="container" style="min-height: 70vh !important">
            <div class="card">
                <div class="card-header">
                    <a @click="show('create-user-modal')"><h4 class="fas fa-plus text-primary p-2 btn-func-misc ml-2 mr-2 mb-0 mt-0"></h4></a>
                    <h4 class="text-dark text-center mb-0">Varbuotojai</h4>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
import swal from 'sweetalert'
export default{

    data(){
        return {
            isLoading: true,
            fullPage: false,
            users: null
        }
    },
    created(){
        this.loadUsers()
    },
    methods: {
        show: function(name, param = {}){
            this.$modal.show(name, param)
        },
        loadUsers: function(){
            this.$http.get('user/list').then((response)=>{
                if(response.status == 200){
                    this.users = response.data
                    //this.isLoading = false
                }
            }).catch(error => {
                swal(error.response.data.message, Object.values(error.response.data.errors)[0][0], 'error')
            })
        }
    },
    components: {
        Loading
    }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
