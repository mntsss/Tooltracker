<template>
  <modal :name="modal_name"
         height="auto"
         :adaptive="true"
         :scrollable="true"
         transition="pop-out"
         :pivotY="0.3"
         :clickToClose="allowClose"
         :beforeClose="onClose"
         @closed="closed">



    <div class="card">
        <div class="card-header secondary d-flex align-center h5 py-1">
            <slot name="header">
            </slot>
            <v-btn icon @click="$modal.hide(modal_name)" class="ma-0 float-right btn-close"><v-icon class="primary--text">fa-times</v-icon></v-btn>
        </div>

        <div class="card-body">
            <slot name="content"></slot>
        </div>
    </div>
  </modal>
</template>
<script>
import swal from 'sweetalert';

export default {
    props: {
        modal_name:{
            required: true,
            type: String
        },
        allowClose:{
            required: false,
            type: Boolean
        },
        onClose: {
          required: false
        }
    },
    computed: {

    },
  methods: {
    closed: function(){
        this.$emit('closed')
    }
  }
}
</script>
<style>
    .btn-close{
        max-width: 40px;
    }
</style>
