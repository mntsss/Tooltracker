<template>
    <Modal modal_name="qr-reader-modal">
        <span slot="header">Įrankių skaitytuvas</span>
        <div slot="content">
            <QRreader></QRreader>
        </div>
    </Modal>
</template>

<script>
    import Modal from './Modal.vue';
    import QRreader from './../modules/QRreader.vue';
    export default {
        components: {
            QRreader,
            Modal
        }
    }
</script>