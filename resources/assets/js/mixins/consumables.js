export default {
  methods: {
    proccessObjectWithdrawals: function(){
      for(var i = 0; i< this.objects.length; i++){
        this.objects[i].item_withdrawals = this.addConsumables(this.objects[i].item_withdrawals);
      }
    },
    addConsumables: function(withdrawals){
      for(var i = 0; i < withdrawals.length; i++){
        if(withdrawals[i].item.ItemConsumable){
            for(var j = i+1; j< withdrawals.length; j++)
            {
              if(withdrawals[i].ItemID == withdrawals[j].ItemID)
              {
                withdrawals[i].ItemWithdrawalQuantity += withdrawals[j].ItemWithdrawalQuantity;
                withdrawals.splice(j,1);
                j--;
              }
            }
        }
      }
      return withdrawals
    }
  }
}
