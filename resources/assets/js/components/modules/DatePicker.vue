<template>
  <DatePicker v-model="date" :lang="lang" @change="changed()" format="YYYY-MM-DD"></DatePicker>
</template>
<script>
import DatePicker from 'vue2-datepicker'
export default{
  data(){
    return{
      date: null,
      lang: {
        days: ['Sek', 'Pr', 'An', 'Tr', 'Ket', 'Pn', 'Še'],
        months: ['Sau', 'Vas', 'Kov', 'Bal', 'Geg', 'Bir', 'Lie', 'Rugp', 'Rugs', 'Spa', 'Lap', 'Gru'],
        placeholder: {
          date: 'Pasirinkite datą',
          dateRange: 'Pasirinkite laikotarpį'
        }
      }
    }
  },
  methods: {
    changed: function(){
      this.$emit('changed', {date: this.date})
    }
  },
  components:{
    DatePicker
  }
}
</script>
