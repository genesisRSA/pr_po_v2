<template>
    <div>
        <v-row>
          <v-col
            v-for="card in cards"
            :key="card"
            cols="12"
          >
            <v-card min-height="800">
            <div class="mt-5"></div>
              <v-subheader><h1 class="mt-5">{{ card }}</h1></v-subheader>
              <v-card-text>
              <v-divider></v-divider>
                    <v-row justify="end">
                        <v-col
                          cols="12"
                          sm="6"
                          md="3"
                        >
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                        class="mb-10"
                      ></v-text-field>
                      </v-col>
                    </v-row>
                      <v-data-table
                        :headers="headers"
                        :items="prList"
                        hide-default-footer
                        :search="search"
                        :page.sync="page"
                        :items-per-page="itemsPerPage"
                        @page-count="pageCount = $event"
                        class="elevation-1"
                    >

                        <template v-slot:item.actions="{ item }">

                            <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    class="mr-2"
                                    @click="viewPR(item)"
                                    v-bind="attrs"
                                    v-on="on"
                                >
                                    mdi-eye
                                </v-icon>
                            </template>
                            <span>View PR</span>
                            </v-tooltip>

                            <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    class="mr-2"
                                    @click="deletePR(item)"
                                    v-bind="attrs"
                                    v-on="on"
                                >
                                    mdi-delete
                                </v-icon>
                            </template>
                            <span>Delete PR</span>
                            </v-tooltip>

                        </template>
            
                    </v-data-table>
                    <div class="text-center pt-2">
                    <v-pagination
                        v-model="page"
                        :length="pageCount"
                        circle
                        :total-visible="7"
                    ></v-pagination>
                    </div>
               </v-card-text>
            </v-card>
          </v-col>
          <v-btn
                color="success"
                fab
                x-large
                dark
                class="position_add"
                @click="addPR()"
                >
                <v-icon>mdi-plus</v-icon>
            </v-btn>

            <v-dialog
                    transition="dialog-top-transition"
                    max-width="1050"
                    v-model="addPRdialog"
                >
                <v-card>
                        <v-toolbar
                        color="primary"
                        dark
                        >Add a Purchase Request</v-toolbar>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12" md="12">
                                            <div style="background-color: #2196F3; padding:10px;">
                                                <span style="color:white; font-weight:bold; letter-spacing: 2px;">PR Details</span>
                                            </div>
                                    </v-col>
                                </v-row>
                                <v-row>

                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                    <v-text-field
                                        label="PR No."
                                        v-model='pr_details.pr_no'
                                        readonly
                                        ></v-text-field>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                    <v-text-field
                                        label="Requestor"
                                        v-model='pr_details.requestor'
                                        readonly
                                        ></v-text-field>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                    <v-text-field
                                        label="Date"
                                        readonly
                                        v-model='pr_details.date'
                                    ></v-text-field>
                                    </v-col>

                                </v-row>


                                <v-row>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                    <v-text-field
                                        label="SO No."
                                        ></v-text-field>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                    <v-select
                                        label="Department"
                                        :items='departmentOptions'
                                        :item-text="departmentOptions.text"
                                        :item-value="departmentOptions.value"
                                        v-model='pr_details.department'
                                        ></v-select>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col cols="12" md="12">
                                            <div style="background-color: #2196F3; padding:10px;">
                                                <span style="color:white; font-weight:bold; letter-spacing: 2px;">Add Items</span>
                                            </div>
                                    </v-col>
                                </v-row>

                                <v-row>

                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                    <v-select
                                        label="Item Category"
                                        clearable
                                        :items='categoryOptions'
                                        :item-text="categoryOptions.text"
                                        :item-value="categoryOptions.value"
                                        v-model='pr_items.category'
                                        @input='getSubCatValRequestor($event)'
                                    ></v-select>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                    <v-select
                                        label="Item Sub-Category"
                                        clearable
                                        :items='subcategoryOptions'
                                        :item-text="subcategoryOptions.text"
                                        :item-value="subcategoryOptions.value"
                                        @input='getPartNameValRequestor($event)'
                                        v-model='pr_items.subcategory'
                                    ></v-select>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                    <v-select
                                        label="Part Name"
                                        clearable
                                        :items='partnameOptions'
                                        :item-text="partnameOptions.text"
                                        :item-value="partnameOptions.value"
                                        v-model='pr_items.part_name'
                                        @input='getDimensionValRequestor($event)'
                                    ></v-select>
                                    </v-col>

                                </v-row>

                                <v-row>

                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                    <v-select
                                        :items='dimensionOptions'
                                        :item-text="dimensionOptions.text"
                                        :item-value="dimensionOptions.value"
                                        v-model='pr_items.dimension'
                                        @input='getMaterialValRequestor($event)'
                                        label="Dimension"
                                        clearable
                                    ></v-select>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                    <v-select
                                        :items='materialOptions'
                                        :item-text="materialOptions.text"
                                        :item-value="materialOptions.value"
                                        v-model='pr_items.material'
                                        label="Materials"
                                        clearable
                                    ></v-select>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                    <v-text-field
                                        label="Quantity"
                                    ></v-text-field>
                                    </v-col>

                                </v-row>

                                <v-row>

                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="6"
                                    >
                                        <v-textarea
                                            outlined
                                            name="input-7-4"
                                            label="Remarks"
                                            placeholder="Drop a feedback here"
                                        ></v-textarea>
                                    </v-col>

                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="6"
                                    >
                                        <div>
                                            <vuetify-money
                                            v-model="raw_unit_price_for_list_item"
                                            label="Unit Price Cost"
                                            placeholder=" "
                                            :outlined="true"
                                            readonly
                                            v-bind:options="options"
                                            />
                                        </div>
                                    </v-col>

                                </v-row>

                                <v-row>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="6"
                                    >
                                    <v-btn color='primary'><v-icon color="white">mdi-plus-circle</v-icon>Add Item</v-btn>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col cols="12" md="12">
                                        <v-data-table
                                            :headers="headersForAddItem"
                                            hide-default-footer
                                            :items="addedItems"
                                            sort-by="calories"
                                            class="elevation-1"
                                        >
                                            <template v-slot:item.actions="{ item }">
                                                <!-- <v-icon
                                                    small
                                                    class="mr-2"
                                                >
                                                    mdi-pencil
                                                </v-icon> -->
                                                <v-icon
                                                    small
                                                    @click="deleteItem(item)"
                                                >
                                                    mdi-delete
                                                </v-icon>
                                                </template>
                                        </v-data-table>
                                    </v-col>   
                                </v-row>

                            </v-container>
                        </v-card-text>

                        <v-card-actions class="justify-end">
                        <v-btn
                         color="primary"
                        >Save</v-btn>
                        <v-btn
                            text
                        >Close</v-btn>
                        </v-card-actions>

                </v-card>
            </v-dialog>
        </v-row>
    </div>
</template>

<script>
    export default {
        components: {

        },
            data: () => ({
                cards: ['Purchase Request'],
                page: 1,
                pageCount: 0,
                itemsPerPage: 10,
                search: '',
                prList: [],
                headers: [
                    { text: 'PR No.', value: 'pr_no', class: "yellow" },
                    {
                    text: 'SO No.',
                    align: 'start',
                    value: 'so_no',
                    class: "yellow"
                    },
                    { text: 'Department', value: 'department', class: "yellow"},
                    { text: 'Item Category', value: 'item_category', class: "yellow" },
                    { text: 'Date Created', value: 'created_at', class: "yellow" },
                    { text: 'Status', value: 'status', class: "yellow" },
                    { text: 'Actions', value: 'actions', sortable: false, class: "yellow" },
                ],

                headersForAddItem : [
                    { text: 'Item', value: 'item', class: "yellow" },
                    {
                    text: 'Part Name',
                    value: 'part_name',
                    class: "yellow"
                    },
                    { text: 'Material', value: 'material', class: "yellow"},
                    { text: 'Dimension', value: 'dimension', class: "yellow" },
                    { text: 'Remarks', value: 'remarks', class: "yellow" },
                    { text: 'Supplier 1', value: 'supplier_one', class: "yellow" },
                    { text: 'Supplier 2', value: 'supplier_two', class: "yellow" },
                    { text: 'Supplier 3', value: 'supplier_three', class: "yellow" },
                    { text: 'Target Cost', value: 'target_cost', class: "yellow" },
                    { text: 'Actions', value: 'actions', sortable: false, class: "yellow" },
                ],
                addPRdialog : false,
                raw_unit_price_for_list_item : null,
                options: {
                locale: "en-US",
                prefix: "₱",
                suffix: "",
                length: 11,
                precision: 2
                },

                pr_details : {
                    pr_no : '',
                    so_no : '',
                    department : '',
                    date : '',
                    requestor : ''
                },

                pr_items : {
                    category : '',
                    subcategory : '',
                    part_name : '',
                    dimension : '',
                    material : '',
                    quantity : '',
                    remarks : '',
                    unit_cost : '' 
                },

                departmentOptions: [],
                
                categoryOptions: [],
                subcategoryOptions: [],
                partnameOptions: [],
                dimensionOptions: [],
                materialOptions: [],

                addedItems: []
    }),

    created: function(){
        this.getMyPRlist()
    },

    computed: {

    },

    watch: {

    },

    methods: {

        getMyPRlist(){
               axios.get('/getMyPRlistRequestor')
              .then(response =>{
                    this.prList = response.data
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

          });
        },

        getPRNumber(){
              axios.get('/getPRNumberRequestor')
              .then(response =>{
                    this.pr_details.pr_no = response.data[0]
                    this.pr_details.requestor = response.data[1]
                    this.pr_details.date = response.data[2]
                    this.departmentOptions = response.data[3]

                    this.categoryOptions = response.data[4]
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

            });
        },

        viewPR(item){
            console.log(item)
        },

        addPR(){
            this.addPRdialog = true
            this.getPRNumber()
        },

        getSubCatValRequestor(params){

             if(params == null){
                 this.pr_items.part_name = null
                 this.partnameOptions = []

                 this.pr_items.dimension = null
                 this.dimensionOptions = []

                 this.pr_items.material = null
                 this.materialOptions = []
             }
              axios.get('/getSubCatValRequestor', { params : params })
              .then(response =>{
                    this.subcategoryOptions = response.data
                    this.pr_items.subcategory = ''
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

            });
        },

        getPartNameValRequestor(params){
            let sample = {
                'cat' : this.pr_items.category,
                'subcat' : params
            }

            if(params == null){
                this.pr_items.dimension = null
                 this.dimensionOptions = []

                 this.pr_items.material = null
                 this.materialOptions = []
            }
            
            axios.get('/getPartNameValRequestor', { params : sample })
              .then(response =>{
                    this.partnameOptions = response.data
                    this.pr_items.part_name = ''
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

            });
        },

        getDimensionValRequestor(params){
            let sample = {
                'cat' : this.pr_items.category,
                'subcat' : this.pr_items.subcategory,
                'part_name' : params
            }

            if(params == null){
                 this.pr_items.material = null
                 this.materialOptions = []
            }
            
            axios.get('/getDimensionValRequestor', { params : sample })
              .then(response =>{
                    this.dimensionOptions = response.data
                    this.pr_items.dimension = ''
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

            });
        },

        getMaterialValRequestor(params){
            let sample = {
                'cat' : this.pr_items.category,
                'subcat' : this.pr_items.subcategory,
                'part_name' : this.pr_items.part_name,
                'dimension' : params
            }
            
             axios.get('/getMaterialValRequestor', { params : sample })
              .then(response =>{
                    this.materialOptions = response.data
                    this.pr_items.material = ''
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

            });
        }

        },
    }
</script>

<style>
  .position_add{
   position: fixed;
   bottom: 15px;
   right: 37px;
  }
</style>