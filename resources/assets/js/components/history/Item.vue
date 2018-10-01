<template>
    <div class="loading-parent">
        <Loading :active.sync="isLoading"
        :can-cancel="false"
        :is-full-page="fullPage"></Loading>

      <div class="container" style="min-height: 70vh !important">
        <div class="card" v-if="history && item">
          <v-layout row wrap align-center class="card-header pb-0 pt-0 mx-0 secondary v-toolbar" >
              <v-flex headline shrink justify-start align-content-center>
                  <a @click="$back()" class="headline"><span class="fa fa-arrow-left primary--text remove-all-margin p-2 btn-func-misc"></span></a>
              </v-flex>
              <v-flex>
                  <div class="text-center headline" v-if="item">{{item.ItemName}} istorija</div>
              </v-flex>
          </v-layout>
          <div class="card-body">
              <v-data-table :headers="tableHeaders" :items="history" :pagination.sync="pagination" :rows-per-page-items='[5,10,25,{"text":"$vuetify.dataIterator.rowsPerPageAll","value":25}]' hide-actions class="elevation-3 border border-dark">
                  <template slot="items" slot-scope="props">
                    <tr class="cursor-pointer">
                      <td>
                        {{ actionDesc[props.item.Action][props.item.Type][props.item.Subtype]}}
                      </td>
                      <td>
                        {{ props.item.Username }}
                      </td>
                      <td class="text-xs-center">
                        {{ props.item.Date }}
                      </td>
                      <td class="justify-center layout px-0" v-if="item.ItemConsumable && props.item.Quantity">
                        {{ props.item.Quantity}}
                      </td>
                    </tr>
                  </template>
                  <template slot="no-data">
                    <v-alert :value="true" class="bg-warning" icon="warning">
                      Įrankio istorija tuščia...
                    </v-alert>
                  </template>
                </v-data-table>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
import swal from 'sweetalert'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.min.css'
export default{
    data(){
        return {
            pagination: {
                sortBy: 'Date',
                descending: true,
                rowsPerPage: 50,
            },
            history: [],
            item: null,
            isLoading: true,
            fullPage: false,
            actionDesc: {
                suspention: {
                    in: {
                        unconfirmed_return: "Grąžinimas patvirtintas",
                        warranty_fix: "Grįžo iš garantinio",
                        unwarranted_fix: "Sutaisyta"
                    },
                    out: {
                        unconfirmed_return: "Įšaldyta: Nepatvirtintas grąžinimas",
                        warranty_fix: "Įšaldyta: Garantinis taisymas",
                        unwarranted_fix: "Įšaldyta: Taisymas"
                    }
                },
                withdrawal: {
                    in: {
                        null: "Įrankis grąžintas"
                    },
                    out: {
                        null: "Įrankis priskirtas vartotojui"
                    }
                },
                reservation: {
                    in: {
                        null: "Įrankio rezervacija patvirtinta"
                    },
                    out: {
                        null: "Įrankis rezervuotas"
                    }
                }
            }
        }
    },
    props:{
        id: {
            required: true,
            type: Number
        },
        type: {
            required: true,
            type: String
        }
    },
    created(){
        this.loadHistory()
    },
    computed: {
        tableHeaders: function(){
            var headers = [
                {
                  text: 'Veiksmas',
                  align: 'left',
                  value: false,
                  sortable: false
                },
                {
                  text: 'Vartotojas',
                  align: 'left',
                  sortable: false,
                  value: 'Username'
                },
                {
                  text: 'Data',
                  value: 'Date',
                  align: 'left'
                }
              ]
              if(this.item)
                if(this.item.ItemConsumable)
                    headers.push({
                      text: 'Kiekis (vnt.)',
                      align: 'center',
                      value: 'Quantity'
                    })
            return headers
        }
    },
    methods: {
        loadHistory: function(){
            this.$http.get(this.type+'/history/'+this.id)
            .then(response => {
                if(response.status == 200){
                    this.history = response.data.actions
                    this.item = response.data.item
                    this.isLoading = false
                }
            }).catch(error => {
                swal('Klaida', error.response.data.message, 'error')
            })
        }
    },
    components: {
        Loading
    }
}
</script>
<style>
    .loading-parent{
        position: relative;
    }
</style>
