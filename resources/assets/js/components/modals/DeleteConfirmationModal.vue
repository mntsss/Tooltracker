<template>
    <modal :pivotY="0.3" name="delete-confirm-modal" classes="card" transition="pop-out" :height="auto" :adaptive="true" @before-open="beforeOpen">
            <div class="card-header bg-dark text-light">
                {{header}}
            </div>
            <div class="card-body remove-all-padding">
                <p class="text-center p-3">
                    {{message}}
                </p>
                <div class="pl-2 pr-2 d-flex justify-content-end w-100 align-items-baseline" style="position:absolute; bottom: 5px">
                    <button class="btn btn-danger mr-2" @click="deleteConfirm">Trinti</button>
                    <button class="btn btn-warning" @click="$modal.hide('delete-confirm-modal')">Atšaukti</button>
                </div>
            </div>
    </modal>
</template>
<script>
import swal from 'sweetalert'
export default{
    data(){
        return {
            header: '',
            message: '',
            url: ''
        }
    },
    methods: {
        beforeOpen: function(event){
            this.header = event.params.header;
            this.message = event.params.message;
            this.url = event.params.url;
            console.log(event.params)
        },
        deleteConfirm: function(){
            this.$http.get(this.url).then((response)=>{
                if(response.status == 200){
                    this.$modal.hide('delete-confirm-modal')
                    swal(response.data.message, response.data.success, "success")
                }
            }).catch(error =>{
                if(error.response.status == 422)
                {
                    this.$modal.hide('delete-confirm-modal')
                    swal(error.response.data.message, error.response.data.errors.name[0], "error");
                }
            })
        }
    }
}
</script>
