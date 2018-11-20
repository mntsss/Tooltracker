<template>
  <v-container>
    <v-layout align-center justify-center class="px-3 py-1" row wrap>
      <v-flex xs12 sm6 md4 d-flex>
        <v-select
          :items="users"
          v-model="selectedUser"
          label="Pagal vartotoją"
          prepend-icon="fa-user"
          item-text="Username"
          item-value="UserID"
        ></v-select>
      </v-flex>
      <v-flex xs12 sm6 md4 d-flex>
        <v-menu
          :close-on-content-click="false"
          v-model="menu_from"
          :nudge-right="40"
          lazy
          transition="scale-transition"
          offset-y
          full-width
          min-width="290px"
        >
          <v-text-field
            slot="activator"
            v-model="date_from"
            label="Rodyti nuo..."
            prepend-icon="event"
            readonly
          ></v-text-field>
          <v-date-picker v-model="date_from" no-title scrollable locale="lt" first-day-of-week="1" @input="menu_from = false">
          </v-date-picker>
        </v-menu>
      </v-flex>
      <v-flex xs12 sm6 md4 d-flex>
        <v-menu
          :close-on-content-click="false"
          v-model="menu_til"
          :nudge-right="40"
          lazy
          transition="scale-transition"
          offset-y
          full-width
          min-width="290px"
        >
          <v-text-field
            slot="activator"
            v-model="date_til"
            label="Rodyti iki..."
            prepend-icon="event"
            readonly
          ></v-text-field>
          <v-date-picker v-model="date_til" no-title scrollable locale="lt" first-day-of-week="1" @input="menu_til = false">
          </v-date-picker>
        </v-menu>
      </v-flex>
    </v-layout>
    <v-layout align-center justify-center>
      <v-flex shrink>
        <v-btn color="primary" outline><v-icon class="px-2">fa-filter</v-icon>Filtruoti</v-btn>
      </v-flex>
      <v-flex shrink>
        <v-btn color="primary" outline @click="clear()"><v-icon class="px-2">fa-eraser</v-icon>Išvalyti</v-btn>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
export default{
  data(){
    return {
      date_from: new Date().toISOString().substr(0, 10),
      date_til: new Date().toISOString().substr(0, 10),
      menu_from: false,
      menu_til: false,
      selectedUser: null
    }
  }
  methods: {
    clear: function(){
      this.date_from = new Date().toISOString().substr(0, 10);
      this.date_til = new Date().toISOString().substr(0, 10);
      this.selectedUser = null;
      this.$emit('clear');
    },
    filter: function(){
      this.$emit('filter', {user: this.selectedUser, from: this.date_from, til: this.date_til});
    }
  }
}
</script>
