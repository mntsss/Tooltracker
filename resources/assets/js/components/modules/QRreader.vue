<template>
    <div class="card">
        <div class="card-body">
            <p class="error">{{ error }}</p>
            <QrcodeStream @decode="onDecode" @init="onInit" />
        </div>
    </div>
</template>
<script>
    import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from 'vue-qrcode-reader'

    export default{
        data () {
            return {
                error: ''
            }
        },

        methods: {
            onDecode (result) {
                this.$store.commit('newcode', result);
            },

            async onInit (promise) {
                try {
                    await promise
                } catch (error) {
                    if (error.name === 'NotAllowedError') {
                        this.error = "Klaida: tinklalapis neturi leidimo naudoti kamerą!"
                    } else if (error.name === 'NotFoundError') {
                        this.error = "Klaida: nepavyko rasti kameros, galimai įranginys jos neturi."
                    } else if (error.name === 'NotSupportedError') {
                        this.error = "ERROR: secure context required (HTTPS, localhost)"
                    } else if (error.name === 'NotReadableError') {
                        this.error = "Klaida: kamera jau naudojama?"
                    } else if (error.name === 'OverconstrainedError') {
                        this.error = "Klaida: kamera netinkama..."
                    } else if (error.name === 'StreamApiNotSupportedError') {
                        this.error = "Klaida: įranginys nepalaiko šios funkcijos..."
                    }
                }
            }
        },
        components: {
            QrcodeStream, QrcodeDropZone, QrcodeCapture
        }
    }

</script>
<style scoped>
    .error {
        font-weight: bold;
        color: white;
    }
</style>
