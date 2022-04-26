<template>
    <div>
        <v-snackbar
                v-model="dupliRecordSnackbar"
                :timeout="3000"
                :value="true"
                bottom
                color="warning"
                success
                top
                right
                >
                <v-icon>mdi-flag-triangle</v-icon>
                Whoops, a duplicate PR ID has been recognized, try again.
        </v-snackbar>
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
                                        v-model='pr_details.so_no'
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
                                        @input='getMaterialValRequestor($event)'
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
                                        :items='materialOptions'
                                        :item-text="materialOptions.text"
                                        :item-value="materialOptions.value"
                                        v-model='pr_items.material'
                                        label="Materials"
                                        @input='getDimensionValRequestor($event)'
                                        clearable
                                    ></v-select>
                                    </v-col>

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
                                        @input='quantityAndPriceState($event)'
                                        label="Dimension"
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
                                        @keypress="isNumber($event, pr_items.quantity)"
                                        @keyup="computedVal($event)"
                                        v-model="pr_items.quantity"
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
                                            v-model="pr_items.remarks"
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
                                            v-model="pr_items.raw_unit_price_for_list_item"
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
                                    <v-btn color='primary' :disabled='add_item_disable()' @click="addToItemList()"><v-icon color="white">mdi-plus-circle</v-icon>Add Item</v-btn>
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

                                                <template v-slot:item.supplier_one="{ item }">
                                                        <v-chip
                                                            :color="getColorForAddedItem(item.supplier_one)"
                                                            dark
                                                        >
                                                            {{ item.supplier_one }}
                                                        </v-chip>
                                                </template>

                                                <template v-slot:item.supplier_two="{ item }">
                                                        <v-chip
                                                            :color="getColorForAddedItem(item.supplier_two)"
                                                            dark
                                                        >
                                                            {{ item.supplier_two }}
                                                        </v-chip>
                                                </template>

                                                <template v-slot:item.supplier_three="{ item }">
                                                        <v-chip
                                                            :color="getColorForAddedItem(item.supplier_three)"
                                                            dark
                                                        >
                                                            {{ item.supplier_three }}
                                                        </v-chip>
                                                </template>

                                        </v-data-table>
                                    </v-col>
                                </v-row>

                            </v-container>
                        </v-card-text>

                        <v-card-actions class="justify-end">
                        <v-btn
                         color="primary"
                         :disabled="addedItems.length == 0 ? true : false"
                         @click='savePR()'
                        >Save</v-btn>
                        <v-btn
                            text
                        @click='closeAddPRdialog()'
                        >Close</v-btn>
                        </v-card-actions>

                </v-card>
            </v-dialog>


            <v-dialog v-model="dialogDeletePR" max-width="500px">
                            <v-card>
                                <v-card-title class="text-h5">Are you sure you want to delete this PR?</v-card-title>
                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="closeDeletePR">Cancel</v-btn>
                                <v-btn color="blue darken-1" text @click="deletePRConfirm">OK</v-btn>
                                <v-spacer></v-spacer>
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
                dupliRecordSnackbar : false,
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
                    { text: 'Grand Total', value: 'item_category', class: "yellow" },
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
                    { text: 'Quantity', value: 'quantity', class: "yellow" },
                    { text: 'Remarks', value: 'remarks', class: "yellow" },
                    { text: 'Supplier 1', value: 'supplier_one', class: "yellow" },
                    { text: 'Supplier 2', value: 'supplier_two', class: "yellow" },
                    { text: 'Supplier 3', value: 'supplier_three', class: "yellow" },
                    { text: 'Target Cost', value: 'target_cost', class: "yellow" },
                    { text: 'Actions', value: 'actions', sortable: false, class: "yellow" },
                ],
                addPRdialog : false,
                dialogDeletePR : false,
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
                    raw_unit_price_for_list_item : ''
                },

                departmentOptions: [],

                categoryOptions: [],
                subcategoryOptions: [],
                partnameOptions: [],
                dimensionOptions: [],
                materialOptions: [],

                addedItems: [],

                selectedIndex : null
    }),

    created: function(){
        this.getMyPRlist()
    },

    computed: {

    },

    watch: {
        addPRdialog (val) {
            val || this.closeAddPRdialog()
        },
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
               axios.get('/viewPRRequestor',{ params : item.id })
              .then(response =>{
                    console.log(response.data)
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

            });
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

                this.pr_items.quantity = null
                this.pr_items.raw_unit_price_for_list_item = null
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

                 this.pr_items.quantity = null
                this.pr_items.raw_unit_price_for_list_item = null
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

        getMaterialValRequestor(params){
            let sample = {
                'cat' : this.pr_items.category,
                'subcat' : this.pr_items.subcategory,
                'part_name' : params,
            }

             if(params == null){
                 this.pr_items.dimension = null
                 this.dimensionOptions = []

                this.pr_items.quantity = null
                this.pr_items.raw_unit_price_for_list_item = null
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
        },

        getDimensionValRequestor(params){

            let sample = {
                'cat' : this.pr_items.category,
                'subcat' : this.pr_items.subcategory,
                'part_name' : this.pr_items.part_name,
                'material' : params
            }

            if(params == null){
                this.pr_items.quantity = null
                this.pr_items.raw_unit_price_for_list_item = null
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

        quantityAndPriceState(params){
            if(this.pr_items.dimension == null){
                this.pr_items.quantity = null
                this.pr_items.raw_unit_price_for_list_item = null
            }
        },

        computedVal(event){
            if((this.pr_items.category) &&
               (this.pr_items.subcategory) &&
               (this.pr_items.part_name) &&
               (this.pr_items.material) &&
               (this.pr_items.dimension)){

                    if(this.pr_items.quantity == null || this.pr_items.quantity == ''){
                         this.pr_items.raw_unit_price_for_list_item = null
                         return
                     }
                    axios.get('/getComputedPRprice', { params : this.pr_items })
                    .then(response =>{
                            this.pr_items.raw_unit_price_for_list_item = response.data * this.pr_items.quantity
                            if(this.pr_items.raw_unit_price_for_list_item == 0){
                                this.pr_items.raw_unit_price_for_list_item = null
                            }
                    })
                    .catch(error =>{
                            console.log(error.response);
                    })
                    .finally(() => {

                    });
               }

        },
        isNumber(event, quantity) {

            if ((!/\d/.test(event.key) &&
                (event.key !== "." || /\./.test(quantity)) || event.key =='0' && quantity.length == 0)
            )
                return event.preventDefault();

        },

        add_item_disable(){
            if(
            (this.pr_details.so_no == null || this.pr_details.so_no == '') ||
            (this.pr_details.department == null || this.pr_details.department == '') ||
            (this.pr_items.category == null || this.pr_items.category == '')||
            (this.pr_items.subcategory == null || this.pr_items.subcategory == '')||
            (this.pr_items.part_name == null || this.pr_items.part_name == '')||
            (this.pr_items.dimension == null || this.pr_items.dimension == '')||
            (this.pr_items.material == null || this.pr_items.material == '')||
            (this.pr_items.quantity == null || this.pr_items.quantity == '')||
            (this.pr_items.raw_unit_price_for_list_item == null || this.pr_items.raw_unit_price_for_list_item == '')){
                return true;
            }
        },

        addToItemList(){

         this.addedItems.push({item : this.addedItems.length + 1,
                              part_name : this.pr_items.part_name,
                              material : this.pr_items.material,
                              dimension : this.pr_items.dimension,
                              quantity : this.pr_items.quantity,
                              remarks : this.pr_items.remarks,
                              supplier_one : 'PENDING',
                              supplier_two : 'PENDING',
                              supplier_three : 'PENDING',
                              target_cost : this.pr_items.raw_unit_price_for_list_item.toLocaleString('en-US',{style:'currency',currency:'PHP'})
              })
            this.pr_items.category = null
            this.pr_items.subcategory = null
            this.pr_items.part_name = null
            this.pr_items.material = null
            this.pr_items.dimension = null
            this.pr_items.quantity = null
            this.pr_items.remarks = null
            this.pr_items.raw_unit_price_for_list_item = null
        },

        getColorForAddedItem(params){
            if(params == 'PENDING'){
                return 'orange'
            }
        },

        deleteItem(params){
              let selectedDelete = this.addedItems.indexOf(params)
              this.addedItems.splice(selectedDelete,1)
        },

        closeAddPRdialog(){
            this.addPRdialog = false
            this.pr_details.so_no = null
            this.pr_details.department = null

            this.pr_items.category = null
            this.pr_items.subcategory = null
            this.pr_items.part_name = null
            this.pr_items.material = null
            this.pr_items.dimension = null
            this.pr_items.quantity = null
            this.pr_items.remarks = null
            this.pr_items.raw_unit_price_for_list_item = null
            this.addedItems = []
        },

        savePR(){
            let params = {
                pr_details : this.pr_details,
                pr_items : this.addedItems
            }
               axios.post('/savePrRequestor', { params : params })
              .then(response =>{
                    if(response.data == 'dupli'){
                        this.dupliRecordSnackbar = true
                    }
                    this.closeAddPRdialog()
                    this.getMyPRlist()
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

            });
        },

        deletePR(item){
            this.dialogDeletePR = true
            this.selectedIndex = item.id
        },

        closeDeletePR(){
            this.dialogDeletePR = false
        },

        deletePRConfirm(){
              axios.post('/deletePrRequestor', { params : this.selectedIndex })
              .then(response =>{
                    this.closeDeletePR()
                    this.getMyPRlist()
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
  tbody tr:nth-of-type(even) {
    background-color: rgba(0, 0, 0, .05);
  }
  .position_add{
   position: fixed;
   bottom: 15px;
   right: 37px;
  }
</style>
