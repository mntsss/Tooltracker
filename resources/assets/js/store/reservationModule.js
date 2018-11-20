const reservationModule = {
    namespaced: true,
    state:{
        reservationItems: [],
        userID: null,
        objectID: null,
        newItem: {
          item: null,
          image: null,
          quantity: 1
        }
    },
    mutations:
    {
        clearReservation(state){
            state.reservationItems = []
            state.userID = null
            state.objectID = null
        },
        setUser(state, userID){
            state.userID = userID
        },
        setObject(state, objectID){
            state.objectID = objectID
        },
        addItem(state, item){
            state.newItem.item = item
        },
        setImage(state, image){
            state.newItem.image = image
        },
        setQuantity(state, quantity){
            state.newItem.quantity = quantity
        },
        cancelItem(state){
            state.newItem = {
              item: null,
              image: null,
              quantity: 1
            }
        },
        removeItem(state, item){
            var index = state.reservationItems.indexOf(item);
            state.reservationItems.splice(index, 1)
        },
        reserveItem(state){
            state.reservationItems.push(state.newItem)
            state.newItem = {
              item: null,
              image: null,
              quantity: 1
            }
        }
    }
}

export default reservationModule;
