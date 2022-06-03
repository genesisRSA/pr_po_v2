
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
              <v-subheader><h1 class="mt-5">{{ costingName }}</h1></v-subheader>
              <v-card-text>
              <v-divider class='mb-5'></v-divider>
                    <v-row justify="start">
                    <v-col
                          cols="12"
                          sm="6"
                          md="3"
                          class='mt-4'
                        >
                      <v-btn color='primary' @click="exportPDF">
                       EXPORT PDF
                           <v-icon
                            small
                            >
                            mdi-file-pdf-box
                            </v-icon>
                      </v-btn>

                      <v-btn color='primary'>
                        <download-excel :data="costing">
                            EXPORT EXCEL
                        </download-excel>
                           <v-icon
                            small
                            >
                            mdi-file-excel
                            </v-icon>
                      </v-btn>

                      </v-col>
                      <v-spacer></v-spacer>
                        <v-col
                          cols="12"
                          sm="6"
                          md="3"
                        >
                    <v-text-field
                        v-model="searchForCosting"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                        class="mb-10"
                      ></v-text-field>
                      </v-col>
                    </v-row>

                      <v-data-table
                        :headers="headersForCosting"
                        :items="costing"
                        hide-default-footer
                        :search="searchForCosting"
                        :page.sync="pageForCosting"
                        :items-per-page="itemsPerPageForCosting"
                        @page-count="pageCountForCosting = $event"
                        sort-by="name"
                        class="elevation-1"
                    >

                    </v-data-table>
                    <div class="text-center pt-2">
                    <v-pagination
                        v-model="pageForCosting"
                        :length="pageCountForCosting"
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
                costingName : 'Costing',
                pageForCosting: 1,
                pageCountForCosting: 0,
                itemsPerPageForCosting: 10,
                searchForCosting: '',
                costing:[],
                headersForCosting: [
                    { text: 'PO No.', value: 'po_no', class: "yellow" },
                    {
                    text: 'PO Date',
                    value: 'po_date',
                    class: "yellow"
                    },
                    { text: 'PR No.', value: 'pr_no', class: "yellow" },
                    { text: 'Vendor', value: 'vendor', class: "yellow" },
                    { text: 'Item Code', value: 'item_code', class: "yellow" },
                    { text: 'Item Sub Category', value: 'item_subcat', class: "yellow" },
                    { text: 'Item Description', value: 'item_desc', class: "yellow" },
                    { text: 'Request Qty', value: 'req_quantity', class: "yellow" },
                    { text: 'Unit Cost', value: 'unit_cost', class: "yellow" },
                    { text: 'Total Amount', value: 'total_amount', class: "yellow" },
                    { text: 'Currency', value: 'currency', class: "yellow" },
                    { text: 'PR Remarks', value: 'PR_remarks', class: "yellow" },
                ],
    }),

    created: function(){
        this.getMyCostinglist()
    },

    computed: {

    },

    watch: {

    },

    methods: {

         getMyCostinglist(){
               axios.get('/getMyCostinglist')
              .then(response =>{
                    this.costing = response.data
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

          });
        },

        exportPDF(){
            const doc = new jsPDF('landscape')

            autoTable(doc, {html: '#my-table'})

            autoTable(doc,({
                theme : 'striped',
                body : this.costing,
                columns : [
                    { header : 'PO No.', dataKey: 'po_no' },
                    { header : 'PO Date', dataKey: 'po_date' },
                    { header : 'PR No.', dataKey: 'pr_no' },
                    { header : 'Vendor', dataKey: 'vendor' },
                    { header : 'Item Code', dataKey: 'item_code' },
                    { header : 'Item Sub Category', dataKey: 'item_subcat' },
                    { header : 'Item Description', dataKey: 'item_desc' },
                    { header : 'Request Qty', dataKey: 'req_quantity' },
                    { header : 'Unit Cost', dataKey: 'unit_cost' },
                    { header : 'Total Amount', dataKey: 'total_amount' },
                    { header : 'Currency', dataKey: 'currency' },
                    { header : 'PR Remarks', dataKey: 'PR_remarks' },
                ]
            }))
            let ms = Date.now();
            doc.save('costing_'+ms+'.pdf')
        }

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
