<template>
    <modal name="view-item-modal" height="auto" :adaptive="true" transition="pop-out" :pivotY="0.3" @before-open="beforeOpen">
        <div class="card">
            <div class="card-header">
                {{item.ItemName}} <a @click="$modal.hide('view-item-modal')" class="float-right"><span class="fas fa-times btn-func-misc"></span></a>
            </div>
            <div class="card-body">

            </div>
        </div>
    </modal>
</template>
<script>
export default{
    date(){
        return {
            item: null,

        }
    },
    methods: {
        beforeOpen: function(event){
            this.item = this.event.params.item
        }
    }
}
</script>
