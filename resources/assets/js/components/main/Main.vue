<template>
  <v-container class="py-0 my-0" v-if='$user'>
    <v-layout row mx-0 mx-0 justify-center>
      <v-flex sm-6 class="px-4 pb-3 data-box">
        <WaitingConfirmationBox></WaitingConfirmationBox>
      </v-flex>
      <v-flex sm-6 class="px-4 pb-3 data-box">
        <StatisticsTiles></StatisticsTiles>
      </v-flex>
    </v-layout>
    <v-layout mx-0 mx-0 justify-center fill-height row>
      <v-flex sm-6 class="px-4 pb-3 data-box">
        <FixingItems></FixingItems>
      </v-flex>
      <v-flex sm-6 class="px-4 pb-3 data-box">
        <LongestRentedItems></LongestRentedItems>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
import FixingItems from './FixingItemsBox.vue'
import LongestRentedItems from './LongestRentedItemsBox.vue'
import StatisticsTiles from './StatisticsTilesBox.vue'
import WaitingConfirmationBox from './WaitingConfirmationBox.vue'

export default {
  mounted(){
      this.$contentLoadingHide();
  },
  computed: {
    RfidCode: function(){
        return this.$store.state.recentCode;
    }
  },
  watch:{
    RfidCode(val){
        if(this.RfidCode != null && this.item == null)
        {
          this.getItemInfo(this.RfidCode)
          this.$store.commit('resetCode')
        }
      }
    },
  methods: {
      getItemInfo: function(code){
          this.$http.post('/item/findcode', {
              code: code
          }).then(response => {
              if(response.status == 200){
                  this.$router.push({name: 'item', params: {itemProp: response.data}});
              }
          }).catch(err => {

                  console.log(err.response.data.message);
          })
      }
  },
  components: {
    LongestRentedItems,
    FixingItems,
    StatisticsTiles,
    WaitingConfirmationBox
  }
}
</script>
