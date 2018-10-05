<template>
    <Modal modal_name="waiting-image-modal" :allowClose='false' v-on:closed="imageDialogClosed()">
        <div slot="header" v-if="newItem.item">
            {{newItem.item.ItemName}}
        </div>
        <div slot="content" v-if="newItem.item">
            <v-layout justify-center class="border border-primary" row mx-0 wrap v-if="newItem.item.ItemConsumable">
                <v-flex shrink>
                    <v-text-field type="number" v-model="quantity" label="Kiekis" placeholder="Paimamas kiekis"></v-text-field>
                </v-flex>
            </v-layout>
            <v-layout row mx-0 align-center justify-center v-if="!newItem.item.ItemConsumable">
                <v-flex shrink v-if="!newItem.image">
                    <image-uploader :debug="0" :maxWidth="1024" :quality="0.7" :autoRotate=true outputFormat="verbose" :preview=false :className="['fileinput', { 'fileinput--loaded' : hasImage }]"
                    accept="image/*" capture="camera" @input="setImage" @onUpload="loadingDialog = true" @onComplete="loadingDialog = false"></image-uploader>
                </v-flex>
                <v-flex shrink v-else-if="newItem.image">
                    <img v-bind:src="newItem.image.dataUrl" alt="uploaded_image" class="uploaded-image" />
                </v-flex>

          <!-- Loading modal appearing on upload start and loading til upload and resize ends -->
                <v-dialog v-model="loadingDialog" hide-overlay persistent width="300">
                    <v-card class="border border-danger">
                        <v-card-text>
                            Kraunama...
                            <v-progress-linear indeterminate color="primary" class="mb-0"></v-progress-linear>
                    </v-card-text>
                    </v-card>
                </v-dialog>
            </v-layout>
            <v-layout row mx-0 wrap mx-0 pa-3 justify-center align-center>
                <v-flex shrink><v-btn outline @click="addToReservation" :disabled="addButtonDisabled"><v-icon class="primary--text headline mx-2">fa-plus</v-icon>Pridėti į rezervaciją</v-btn></v-flex>
            </v-layout>
        </div>
  </Modal>
</template>
<script>
import Modal from '../../modals/Modal.vue'
import { ImageUploader } from 'vue-image-upload-resize'
export default{
    data(){
        return {
            loadingDialog: false,
            hasImage: false,
            quantity: 1
        }
    },
    props:{
        forceImage: {
            required: false,
            type: Boolean
        }
    },
    computed: {
        newItem: function(){
            return this.$store.state.reservation.newItem;
        },
        addButtonDisabled: function(){
            if(this.forceImage)
                if(!this.newItem.item.ItemConsumable)
                    return !this.hasImage
            else
                return false
        }
    },
    methods: {
        addToReservation: function(){
            this.$store.commit('reservation/reserveItem')
            this.$modal.hide('waiting-image-modal')
        },
        setImage: function(file){
            this.hasImage = true
            this.$store.commit('reservation/setImage', file)
        },
        imageDialogClosed(){
            this.$store.commit('reservation/cancelItem')
            this.loadingDialog = false,
            this.hasImage = false,
            this.quantity = 1
        }
    },
    watch: {
        quantity(val){
            this.$store.commit('reservation/setQuantity', this.quantity)
        }
    },
    components:{
        Modal,
        ImageUploader
    }
}
</script>
<style>
.uploaded-image{
  max-width: 576px;
  padding: 10px;
  max-height: 50vh;
}

</style>
