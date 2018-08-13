<template>
  <div class="container">
    <div class="card">
      <div class="card-header">
              <a @click="$router.go(-1)"><h4 class="fa fa-arrow-left text-primary remove-all-margin p-2 nav-arrow"></h4></a>
              <span class="fas fa-ellipsis-v text-primary remove-all-margin btn-func-misc"></span>
              <h4 class="text-dark text-center">{{itemGroup.ItemGroupName}}</h4>


      </div>
      <div class="card-body">
        <div class="row remove-side-margin">

        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    data(){
      return {
          itemGroup: null
      }
    },
    props: {
        groupID: {
            required: true,
            type: Number
        }
    },
    computed: {
        itemGroupID: function(){
            return this.groupID;
        }
    },
    mounted(){
        this.$http.get('/group/info/'+this.itemGroupID).then((response)=>{
            if(response.status==200){
                this.itemGroup = response.data;
            }
            else{
                console.log('error')
            }
        })
    }
}
</script>
