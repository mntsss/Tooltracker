<template>
  <div class="form-group row">
          <label for="image" class="col-md-6 control-label">Nuotrauka</label>
          <imageLoadingBox></imageLoadingBox>
          <div class="col-md-8 text-left">
            <image-uploader :debug="1" :maxWidth="192" :quality="0.7" :autoRotate=true outputFormat="verbose" :preview="preview" :className="['fileinput', { 'fileinput--loaded' : hasImage }]"
              capture="environment" @input="setImage" @onUpload="$eventBus.$emit('image_resize_started')" @onComplete="$eventBus.$emit('image_resize_finished')" class="form-control"></image-uploader>
          </div>
  </div>
</template>
<script>
import { ImageUploader } from 'vue-image-upload-resize';
import imageLoadingBox from './../modules/ImageLoadingBox.vue'
export default {
  props: {
    preview: {
      default: false,
      required: false,
      type: Boolean
    }
  },
  data(){
    return {
      hasImage: false,
      image: null
    }
  },
  methods: {
    setImage: function(file){
        this.hasImage = true
        this.image = file
    },
  },
  watch: {
    image(val){
      this.$eventBus.$emit('image-uploaded', val);
    }
  },
  components:{
    ImageUploader,
    imageLoadingBox
  }
}
</script>
