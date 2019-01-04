<template>
  <v-navigation-drawer
    v-model="drawer"
    clipped
    fixed
    app
    v-if="$auth.check() && $user"
    v-on:input="propagateInputUp">
    <v-list dense>
      <v-flex v-for="item in meniuItems" :key="item.text">
        <v-list-group
          v-if="item.children"
          v-model="item.model"
          :key="item.text"
          :append-icon="item.model ? 'keyboard_arrow_up' : 'keyboard_arrow_down'"
        >
        <v-list-tile slot="activator" fluid>
          <v-list-tile-action>
            <v-icon class="primary--text">{{item.icon}}</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{item.text}}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile
            v-for="(child, i) in item.children"
            :key="i"
            @click="child.click"
            fluid            >
            <v-list-tile-action v-if="child.icon">
              <v-icon>{{ child.icon }}</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>
                {{ child.text }}
              </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
      </v-list-group>

      <v-list-tile @click="item.click" v-else-if="!item.children">
        <v-list-tile-action>
          <v-icon class="primary--text">{{item.icon}}</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>{{item.text}}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-flex>
    </v-list>
  </v-navigation-drawer>
</template>
<script>

export default {
  props:{
    show: {
      type: Boolean,
      required: true
    }
  },
  data(){
    return {
      drawer: this.show
    }
  },
  computed: {
    storages: function(){
      const storageList = this.$store.state.storage.storageList;
      if(storageList.length > 0)
      {
        const meniuStorageArray = [];
        storageList.forEach(function(storage, i){
          meniuStorageArray.push({icon: 'keyboard_arrow_right', text: storage.name, click: ()=> {this.$router.push({name: 'groups'})}})
        });
        return meniuStorageArray;
      }
      return [{icon: 'keyboard_arrow_right', text: 'Visi', click: ()=> {this.$router.push({name: 'groups'})}}];
    },
    meniuItems: function(){
      return [
        { icon: 'fa-home', text: 'Pradžia', click: ()=>{ this.$router.push({name: 'main'})} },
        { icon: 'fa-toolbox', 'icon-alt': 'keyboard_arrow_down', text: 'Įrankiai', click: () => {this.model = !this.model},
          model: false,
          children: [
            ...this.storages,
            {icon: 'keyboard_arrow_right', text: 'Įšaldyti', click: ()=> {this.$router.push({name: 'suspendedItems'})}},
            {icon: 'keyboard_arrow_right', text: 'Nuomoti', click:  ()=> {this.$router.push({name: 'rentedItems'})}},
            {icon: 'keyboard_arrow_right', text: 'Ištrinti', click: ()=> {this.$router.push({name: 'deletedItems'})}}
          ]
        },
        {
          icon: 'fa-briefcase',
          'icon-alt': 'keyboard_arrow_down',
          text: 'Objektai',
          model: false,
          children: [
            { icon: 'keyboard_arrow_right', text: 'Aktyvūs', click: ()=> { this.$router.push({name: 'objects'})} },
            { icon: 'keyboard_arrow_right', text: 'Uždaryti', click: ()=>{ this.$router.push({name: 'closedObjects'})}}
          ]
        },
        {
          icon: 'fa-shipping-fast',
          'icon-alt': 'keyboard_arrow_down',
          text: 'Rezervacijos',
          model: false,
          children: [
            { icon: 'keyboard_arrow_right', text: 'Aktyvios', click: ()=> { this.$router.push({name: 'reservations'})} },
            { icon: 'keyboard_arrow_right', text: 'Atiduotos', click: ()=> { this.$router.push({name: 'closedReservations'})}}
          ]
        },
        { icon: 'fa-users', text: 'Vartotojai', click: '',
          model: false,
          children: [
            { icon: 'keyboard_arrow_right', text: 'Aktyvūs', click: ()=>{ this.$router.push({name: 'users'})}},
            { icon: 'keyboard_arrow_right', text: 'Ištrinti', click: ()=>{ this.$router.push({name: 'deletedUsers'})}}
          ]
        },
        {icon: 'fa-history', text: 'Istorija', click: ()=> { this.$router.push({name: 'history'})}}
      ];
    }
  },
  methods:{
    propagateInputUp: function(payload){
      this.$eventBus.$emit("sidebar_event", payload);
    }
  },
  mounted(){
    this.$store.dispatch('storage/LOAD_STORAGE_LIST');
    if(window.innerWidth < 1265)
      this.drawer = false;
  },
  watch: {
    show(val){
      this.drawer = val;
    }
  }
}
</script>
