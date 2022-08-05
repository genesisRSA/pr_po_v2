
<template>
    <app-layout>
        <v-row>
            <v-col
                cols="12"
            >
                <v-card min-height="800">
                <v-img lazy-src="https://picsum.photos/id/11/10/6"
                    min-height="800"
                    max-width="1650"
                    src="https://img.wallpapersafari.com/desktop/1600/900/81/18/WewaSt.jpg">
                <div class="mt-5"></div>
                <v-subheader><h1 class="mt-5">Masterlist</h1></v-subheader>
                    <v-card-text>
                        <v-divider class='mb-5'></v-divider>
                            <v-row justify="start">
                            <v-spacer></v-spacer>
                                <v-col
                                cols="12"
                                sm="6"
                                md="3"
                                >
                            <v-text-field
                                v-model="searchForMasterlist"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                                class="mb-10"
                            ></v-text-field>
                            </v-col>
                            </v-row>

                            <v-data-table
                                :headers="headersForMasterlist"
                                :items="masterlist"
                                hide-default-footer
                                :search="searchForMasterlist"
                                :page.sync="pageForMasterlist"
                                :items-per-page="itemsPerPageForMasterlist"
                                @page-count="pageCountForMasterlist = $event"
                                sort-by="name"
                                class="elevation-1"
                            >

                            <template v-slot:item.item_code="{ item }">
                                        <span
                                        :class="getColor(item.item_code)"
                                        >
                                        {{ item.item_code }}
                                        </span>
                            </template>

                            <template v-slot:item.part_name="{ item }">
                                        <span
                                        :class="getColor(item.part_name)"
                                        >
                                        {{ item.part_name }}
                                        </span>
                            </template>

                            <template v-slot:item.material="{ item }">
                                        <span
                                        :class="getColor(item.material)"
                                        >
                                        {{ item.material }}
                                        </span>
                            </template>

                            <template v-slot:item.dimension="{ item }">
                                        <span
                                        :class="getColor(item.dimension)"
                                        >
                                        {{ item.dimension }}
                                        </span>
                            </template>

                            </v-data-table>
                            <div class="text-center pt-2">
                            <v-pagination
                                v-model="pageForMasterlist"
                                :length="pageCountForMasterlist"
                                circle
                                :total-visible="7"
                            ></v-pagination>
                            </div>
                    </v-card-text>
                </v-img>
                </v-card>
            </v-col>
        </v-row>
    </app-layout>
</template>

<script>
    import AppLayout from '../Layouts/AppLayout'
    import jsPDF from "jspdf";
    import autoTable from 'jspdf-autotable';
    import JsonExcel from 'vue-json-excel';
    Vue.component("downloadExcel", JsonExcel);
    export default {
        components: {
            AppLayout,
        },
            data: () => ({

            pageForMasterlist: 1,
            pageCountForMasterlist: 0,
            itemsPerPageForMasterlist: 10,
            searchForMasterlist: '',
            headersForMasterlist: [
                { text: 'Item Code', value: 'item_code', class: "yellow" },
                { text: 'Category', value: 'category', class: "yellow" },
                { text: 'Sub-Category', value: 'subcategory', class: "yellow" },
                { text: 'Part Name', value: 'part_name', class: "yellow" },
                { text: 'Material', value: 'material', class: "yellow" },
                { text: 'Dimension', value: 'dimension', class: "yellow" }
            ],

            masterlist: [],

    }),

    created: function(){
        this.getMasterlist()
    },

    computed: {

    },

    watch: {

    },

    methods: {

        getMasterlist(){
            axios.get('/getMasterlist')
                .then(response =>{
                        this.masterlist = response.data
                })
                .catch(error =>{
                        console.log(error.response);
                })
                .finally(() => {

            });
        },

        getColor (calories) {
            if (calories == 'N/A') return 'red--text'
            else return
        },

    },

    }
</script>

<style>
  tbody tr:nth-of-type(even) {
    background-color: rgba(0, 0, 0, .05);
  }

 table th + th { border-left:1px solid #dddddd; }
 table td + td { border-left:1px solid #dddddd; }

  .void-text{
   font-size: 150% !important;
  }
</style>
