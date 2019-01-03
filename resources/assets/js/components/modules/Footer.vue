<template>
  <v-footer app fixed justify-center v-if="$auth.ready()">
      <v-layout justify-center align-center>
          <v-flex shrink>
              <span class="h5">Tool</span><span class="h5 text-danger pr-2">Tracker</span><span>&copy; 2018</span>
          </v-flex>
      </v-layout>

  </v-footer>
</template>
