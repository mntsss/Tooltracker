<template>
  <v-container class="pa-0" style="margin-top: 0 !important">
    <v-layout fill-height>
      <v-flex>
        <v-card class="param-box" tile>
          <v-card-title class="text-center justify-center">
            <v-icon class="display-3 text-success">fa-wrench</v-icon>
          </v-card-title>
          <v-card-text>
            <div class="text-center">
              Šį mėnesį taisymų:
            </div>
            <div class="text-center display-2 text-warning font-weight-bold">
              {{statistics.monthly_fixes}}
            </div>
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex>
        <v-card class="param-box" tile>
          <v-card-title class="text-center justify-center">
            <v-icon class="display-3 text-warning">fa-euro-sign</v-icon>
          </v-card-title>
          <v-card-text>
            <div class="text-center">
              Šio mėnesio išlaidos įrankių nuomai
            </div>
            <div class="text-center display-2 text-warning font-weight-bold">
              {{statistics.monthly_rent_price}}&euro;
            </div>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
    <v-layout fill-height>
      <v-flex>
        <v-card class="param-box" tile>
          <v-card-title class="text-center justify-center">
            <v-icon class="display-3 primary--text">fa-toolbox</v-icon>
          </v-card-title>
          <v-card-text>
            <div class="text-center">
              Įrankių skaičius sistemoje
            </div>
            <div class="text-center display-2 text-warning font-weight-bold">
              {{statistics.items_total}}
            </div>
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex>
        <v-card class="param-box" tile>
          <v-card-title class="text-center justify-center">
            <v-icon class="display-3 text-danger">fa-toolbox</v-icon>
          </v-card-title>
          <v-card-text>
            <div class="text-center">
              Naudojami įrankiai:
            </div>
            <div class="text-center display-2 text-warning font-weight-bold">
              {{statistics.items_in_use}}
            </div>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
export default{
  created(){
    this.$store.dispatch('main/LOAD_STATISTICS_TILES');
  },
  computed: {
    statistics: function(){
      return this.$store.state.main.statistics;
    }
  }
}
</script>
