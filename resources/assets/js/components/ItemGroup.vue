<template>
  <div class="container">
    <div class="card">
      <div class="card-header">
              <a @click="$router.go(-1)"><h4 class="fa fa-arrow-left text-primary remove-all-margin p-2 nav-arrow"></h4></a>
              <span class="fas fa-ellipsis-v text-primary remove-all-margin btn-func-misc"></span>
              <h4 class="text-dark text-center">{{itemGroup.ItemGroupName}}</h4>


      </div>
      <div class="card-body">
        <div class="row remove-side-margin" v-for="item in itemGroup.items">
          <div class="col-6">
            {{item.ItemName}}
          </div>
          <div class="col">

          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    data(){
      return {

      }
    },
    props: {
        group: {
            required: true,
            type: Object
        }
    },
    computed: {
        itemGroup: function(){
            return this.group;
        }
    },
    created(){
        this.$http.get('/group/info/'+this.itemGroup.ItemGroupID).then((response)=>{
            if(response.status==200){
                console.log(response.data);
            }
            else{
                console.log('error')
            }
        })
    }
}
</script>
