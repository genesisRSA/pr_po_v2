<template>
    <div>

        <v-row>
          <v-col
            v-for="card in cards"
            :key="card"
            cols="12"
          >
            <v-card min-height="800">
            <v-img lazy-src="https://picsum.photos/id/11/10/6"
                min-height="800"
                max-width="1650"
                src="https://img.wallpapersafari.com/desktop/1600/900/81/18/WewaSt.jpg">
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
                        :loading='isPrTableLoading'
                        hide-default-footer
                        :search="search"
                        :page.sync="page"
                        :items-per-page="itemsPerPage"
                        @page-count="pageCount = $event"
                        class="elevation-1"
                    >

                        <template v-slot:item.status="{ item }">
                            <v-chip
                                :color="getColorForStatus(item.status)"
                                dark
                            >
                                {{ item.status }}
                            </v-chip>
                        </template>

                        <template v-slot:item.item_category="{ item }">
                            <strong>
                                {{ item.item_category.replace(/[^a-zA-Z_]/g, '*') }}
                            </strong>
                        </template>

                        <template v-slot:item.actions="{ item }">

                            <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    small
                                    class="mr-2"
                                    @click="viewPO(item)"
                                    v-bind="attrs"
                                    v-on="on"
                                >
                                    mdi-eye
                                </v-icon>
                            </template>
                            <span>View PR</span>
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
               </v-img>
            </v-card>
          </v-col>


              <v-dialog v-model="viewDialogPR" max-width="1000px">
                            <v-card>
                                <v-card-title class="text-h5"><span style='color:red;'>PR No : </span><span style='position:relative; left:5px;'>{{ prIdViewDialog }}</span><v-spacer></v-spacer><span style='color:red; position: relative; right:5px;'>Date Created : </span>{{ dateCreated }}</v-card-title>
                                <v-card-text>
                                        <v-data-table
                                            :headers="headersForViewItem"
                                            hide-default-footer
                                            :items="viewedItems"
                                            class="elevation-1"
                                        >
                                                <template v-slot:item.supplier_one="{ item }">
                                                    <div v-if='item.supplier_one == "PENDING"'>
                                                        <v-chip
                                                            :color="getColorForAddedItem(item.supplier_one)"
                                                            dark
                                                        >
                                                            {{ item.supplier_one }}
                                                            <v-icon
                                                                small
                                                                class="mr-1"
                                                                style="position: relative; left: 3px; bottom: 1px;"
                                                            >
                                                                mdi-clock
                                                            </v-icon>
                                                        </v-chip>
                                                    </div>
                                                    <div v-else>
                                                        {{ item.supplier_one}}
                                                    </div>
                                                </template>

                                                <template v-slot:item.supplier_two="{ item }">
                                                    <div v-if='item.supplier_one == "PENDING"'>
                                                        <v-chip
                                                            :color="getColorForAddedItem(item.supplier_two)"
                                                            dark
                                                        >
                                                            {{ item.supplier_two }}
                                                            <v-icon
                                                                small
                                                                class="mr-1"
                                                                style="position: relative; left: 3px; bottom: 1px;"
                                                            >
                                                                mdi-clock
                                                            </v-icon>
                                                        </v-chip>
                                                    </div>
                                                    <div v-else>
                                                        {{ item.supplier_two}}
                                                    </div>
                                                </template>

                                                <template v-slot:item.supplier_three="{ item }">
                                                    <div v-if='item.supplier_one == "PENDING"'>
                                                        <v-chip
                                                            :color="getColorForAddedItem(item.supplier_three)"
                                                            dark
                                                        >
                                                            {{ item.supplier_three }}
                                                            <v-icon
                                                                small
                                                                class="mr-1"
                                                                style="position: relative; left: 3px; bottom: 1px;"
                                                            >
                                                                mdi-clock
                                                            </v-icon>
                                                        </v-chip>
                                                    </div>
                                                    <div v-else>
                                                        {{ item.supplier_three}}
                                                    </div>
                                                </template>

                                                <template v-slot:item.target_cost="{ item }">
                                                            {{ item.target_cost.replace(/[^a-zA-Z_]/g, '*') }}
                                                </template>
                                        </v-data-table>
                                </v-card-text>
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
                cards: ['Purchase Order'],
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
                    { text: 'Target Cost', value: 'item_category', class: "yellow" },
                    { text: 'Date Created', value: 'created_at', class: "yellow" },
                    { text: 'Status', value: 'status', class: "yellow" },
                    { text: 'Actions', value: 'actions', sortable: false, class: "yellow" },
                ],

                headersForViewItem : [
                    {
                    text: 'Part Name',
                    value: 'part_name',
                    class: "yellow"
                    },
                    { text: 'Material', value: 'material', class: "yellow"},
                    { text: 'Dimension', value: 'dimension', class: "yellow" },
                    { text: 'Quantity', value: 'quantity', class: "yellow" },
                    { text: 'UOM', value: 'uom', class: "yellow" },
                    { text: 'Remarks', value: 'remarks', class: "yellow" },
                    { text: 'Supplier 1', value: 'supplier_one', class: "yellow" },
                    { text: 'Supplier 2', value: 'supplier_two', class: "yellow" },
                    { text: 'Supplier 3', value: 'supplier_three', class: "yellow" },
                    { text: 'Target Cost', value: 'target_cost', class: "yellow" },
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
                viewedItems: [],

                selectedIndex : null,

                viewDialogPR: false,
                prIdViewDialog: null,

                dateCreated: null,

                isPrTableLoading:true
    }),

    created: function(){
        this.getMyPOlist()
    },

    computed: {

    },

    watch: {
        addPRdialog (val) {
            val || this.closeAddPRdialog()
        },
    },

    methods: {

        getMyPOlist(){
               axios.get('/getMyPOlist')
              .then(response =>{
                  console.log(response.data)
                    this.prList = response.data
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {
                  this.isPrTableLoading = false
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

        viewPO(item){
               axios.get('/viewPORequestor',{ params : item })
              .then(response =>{
                    this.prIdViewDialog = item.pr_no
                    this.viewDialogPR = true
                    this.viewedItems = response.data

                    this.dateCreated = item.created_at
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
        },

        getColorForStatus(params){
            if(params == 'PO APPROVED'){
                return 'green'
            } else if(params.includes('DECLINED')){
                return 'red'
            } else {
                return 'orange'
            }
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

  .position_add{
   position: fixed;
   bottom: 15px;
   right: 37px;
  }
</style>
