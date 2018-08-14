<template>
  <transition name="modal" v-show="$parent.openedModal === 'modalRename'" @close="openModal = null">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">

            <div class="modal-header">
                Pervadinti grupę<button class="float-left" @click="$emit('close')"><span class="fas fa-close"></span></button>
            </div>

            <div class="modal-body">
                <form method="post" @submit.prevent="rename">
                  <div class="form-group">
                    <input class="form-control" type="text" name="name" placeholder="Naujas pavadinimas" v-model="name" />
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary" type="submit">Išsaugoti</button>
                  </div>
                </form>
            </div>

            <div class="modal-footer">
            </div>
          </div>
        </div>
      </div>
    </transition>
</template>
<script>
export default{
  data(){
    return {
      name: ''
    }
  },
  methods:{
    rename: function(){
      this.$http.post('/', {'name': this.name})
      .then((response)=> {
        console.log(response.data)
      })
    }
  }
}
</script>
