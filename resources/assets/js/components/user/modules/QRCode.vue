<template>
    <VueQRCodeComponent :text="qr_code" :size="size"></VueQRCodeComponent>
</template>
<script>
    import VueQRCodeComponent from 'vue-qrcode-component';
    export default {
        name: "QRCode",
        props: {
            code: {
                required: false,
                type: String,
                default: null
            },
            size: {
                required: false,
                type: Number,
                default: 175
            }
        },
        computed: {
            qr_code: function(){
                if(!this.code)
                    return this.$user.code;
                else
                    return this.code;
            }
        },
        components: {
            VueQRCodeComponent
        }
    }
</script>