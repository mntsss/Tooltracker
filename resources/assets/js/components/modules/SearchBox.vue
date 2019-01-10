<template>
    <div>
        <v-text-field
                flat
                solo
                hide-details
                prepend-inner-icon="search"
                label="Paieška..."
                v-model="searchQuery"
                v-on:keydown="search"
                class="hidden-sm-and-down"
        ></v-text-field>
        <div class="search-result-wrapper bg-dark border--primary" v-if="resultBox && searchResults">
            <v-list>
                <template v-for="(item, index) in searchResults">
                    <v-list-tile :key="index" @click="searchItem(item)">
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.name }}</v-list-tile-title>
                            <v-list-tile-sub-title class="text--primary">{{ item.state }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-divider v-if="index + 1 < searchResults.length" :key="`divider-${index}`"
                               class="ma-0"></v-divider>
                </template>
            </v-list>
        </div>
    </div>
</template>
<script>

    export default {
        data() {
            return {
                searchQuery: '',
                searchResults: null
            }
        },
        computed: {
            resultBox: function () {
                if (this.searchQuery.length < 3 || this.searchResults == null)
                    return false
                else if (this.searchResults.length == 0)
                    return false
                else
                    return true
            }
        },
        methods: {
            search: function () {
                if (this.searchQuery.length >= 3) {
                    this.searchResults = null
                    this.$http.post('/item/search', {query: this.searchQuery}).then(response => {
                        if (response.status == 200) {
                            this.searchResults = response.data
                        }
                    }).catch(error => {
                        swal('Klaida', error.response.data.message, 'error')
                    })
                }
                else
                    this.searchResults = null
            },
            searchItem: function (item) {
                this.searchResults = null;
                this.searchQuery = '';
                this.$store.commit('item/setItem', item);
                this.$router.push({name: 'item', params: {itemID: item.id}})
            }
        }
    }
</script>
