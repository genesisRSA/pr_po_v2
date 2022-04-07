<template>
   <app-layout>
            <v-snackbar
                v-model="successSnackbar"
                :timeout="3000"
                :value="true"
                bottom
                color="success"
                success
                top
                right
                >
                <v-icon>mdi-check</v-icon>
                A record has been added successfully.
        </v-snackbar>
        <v-row>
          <v-col
            v-for="card in cards"
            :key="card"
            cols="12"
          >



        <!-- //////////dialog/////////// -->





            <!-- //////////dialog/////////// -->
            <v-card min-height="850">
            <div class="mt-5"></div>
              <v-subheader><h1 class="mt-5">{{ card }}</h1></v-subheader>
              <v-card-text>
              <v-divider></v-divider>
                  <v-container>
                        <v-tabs
                        v-model="tab"
                        background-color="transparent"
                        color="basil"
                        grow
                        class="mt-5"
                        >
                        <v-tab
                            v-for="item in items"
                            :key="item"
                        >
                            {{ item }}
                        </v-tab>
                        </v-tabs>

                        <v-tabs-items v-model="tab">
<!-- /////////////////////tab_item_for_item_categories /////////////////////-->
                        <v-tab-item
                        >
                            
                            <v-card
                            color="basil"
                            flat
                            >
                            <v-card-text>
                                
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
                                ></v-text-field>
                                </v-col>
                                </v-row>
                                <v-data-table
                                :headers="headers"
                                :items="item_categories"
                                :search="search"
                                hide-default-footer
                                :page.sync="page"
                                :items-per-page="itemsPerPage"
                                @page-count="pageCount = $event"
                                class="mt-5"
                                >
                                <template v-slot:item.subcategory_name="{ item }">
                                    <span
                                    :class="getColor(item.subcategory_name)"
                                    >
                                    {{ item.subcategory_name }}
                                    </span>
                                </template>
                                    <template v-slot:item.actions="{ item }">
                                        <v-icon
                                            small
                                            class="mr-2"
                                            @click="editCategoryItem(item)"
                                        >
                                            mdi-pencil
                                        </v-icon>
                                        <v-icon
                                            small
                                            @click="deleteItem(item)"
                                        >
                                            mdi-delete
                                        </v-icon>
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
                        </v-tab-item>
<!-- ///////////////////end_tab_item ///////////////////////////////////////-->
                        <v-tab-item
                        >
                            <v-card
                            color="basil"
                            flat
                            >
                            <v-card-text></v-card-text>
                            </v-card>
                        </v-tab-item>
                        <v-tab-item
                        >
                            <v-card
                            color="basil"
                            flat
                            >
                            <v-card-text></v-card-text>
                            </v-card>
                        </v-tab-item>
                        <v-tab-item
                        >
                            <v-card
                            color="basil"
                            flat
                            >
                            <v-card-text></v-card-text>
                            </v-card>
                        </v-tab-item>
                        <v-tab-item
                        >
                            <v-card
                            color="basil"
                            flat
                            >
                            <v-card-text></v-card-text>
                            </v-card>
                        </v-tab-item>
                        </v-tabs-items>
                  </v-container>
               </v-card-text>
            </v-card>
          </v-col>
        </v-row>

        <!-- //////dialog_for_add////// -->
        <v-dialog
            transition="dialog-top-transition"
            max-width="800"
            v-model="dialog"
        >
            <template v-slot:activator="{ on, attrs }">
            <v-btn
                color="success"
                fab
                x-large
                dark
                class="position_add"
                v-bind="attrs"
                v-on="on"
                @click="getAvailableCateogryItems()"
                >
                <v-icon>mdi-plus</v-icon>
            </v-btn>
            </template>
            <template v-slot:default="dialog">
            <v-card>

                <div v-if="tab == 0">
                <v-toolbar
                color="primary"
                dark
                >Add Item Categories
                </v-toolbar>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="12">
                                    <div style="background-color: #2196F3; padding:10px;">
                                        <span style="color:white; font-weight:bold; letter-spacing: 2px;">Add a Category</span>
                                    </div> 
                            </v-col>  
                        </v-row>
                        <v-row>
                            <v-form class="width"
                                ref="category_form"
                                v-model="category_valid"
                                lazy-validation>
                            <v-col
                                cols="12"
                                sm="6"
                                md="3"
                            >
                            <v-text-field
                            label='Category Name'
                            clearable
                            :rules="[v => !!v || 'Category Name is required']"
                            v-model="category_val">
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="3"
                            >
                                <v-btn
                                    color="primary"
                                    fab
                                    x-small
                                    dark
                                    class="position_add_category"
                                    :disabled="category_val == '' ? true : false || !category_valid"
                                    @click="addCategory()"
                                    >
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>
                            </v-col>
                            </v-form>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="12">
                                    <div style="background-color: #2196F3; padding:10px;">
                                        <span style="color:white; font-weight:bold; letter-spacing: 2px;">Add a Sub-Category</span>
                                    </div> 
                            </v-col>  
                        </v-row>
                        <v-row>
                            <v-form class="width"
                                ref="subcategory_form"
                                v-model="subcategory_valid"
                                lazy-validation>
                            <div class="d-flex">
                            <v-col
                                cols="12"
                                sm="6"
                                md="3"
                            >
                            <v-select
                            label='Category Name'
                            menu-props="auto"
                            :menu-props="{ bottom: true, offsetY: true }"
                            v-model="category_name_selected"
                            :items="category_name_items"
                            :item-text="category_name_items.text"
                            :item-value="category_name_items.value"
                            :rules="[v => !!v || 'Category Name is required']"
                            clearable>
                            </v-select>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="3"
                            >
                            <v-text-field
                            label='Sub-Category Name'
                            v-model="subcategory_val"
                            :rules="[v => !!v || 'Sub-Category Name is required']"
                            clearable>
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="3"
                            >
                                <v-btn
                                    color="primary"
                                    fab
                                    x-small
                                    dark
                                    class="position_sub_category"
                                    :disabled="category_name_selected == '' || !subcategory_valid || subcategory_val ==''"
                                    @click="addSubCat()"
                                    >
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>
                            </v-col>
                            </div>
                            </v-form>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions class="justify-end">
                        <v-btn
                            text
                            @click="close()"
                        >Close
                    </v-btn>
                </v-card-actions>
                </div>
                
                <div v-if="tab == 1">
                <v-toolbar
                color="primary"
                dark
                >Add Item List
                </v-toolbar>
                <v-card-text>

                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn
                         color="primary"
                        >Save
                        </v-btn>
                        <v-btn
                            text
                            @click="close()"
                        >Close
                    </v-btn>
                </v-card-actions>
                </div>

                <div v-if="tab == 2">
                <v-toolbar
                color="primary"
                dark
                >Add Plating Process
                </v-toolbar>
                <v-card-text>

                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn
                         color="primary"
                        >Save
                        </v-btn>
                        <v-btn
                            text
                            @click="close()"
                        >Close
                    </v-btn>
                </v-card-actions>
                </div>

                <div v-if="tab == 3">
                <v-toolbar
                color="primary"
                dark
                >Add Vendors
                </v-toolbar>
                <v-card-text>

                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn
                         color="primary"
                        >Save
                        </v-btn>
                        <v-btn
                            text
                            @click="close()"
                        >Close
                    </v-btn>
                </v-card-actions>
                </div>

                <div v-if="tab == 4">
                <v-toolbar
                color="primary"
                dark
                >Add Payment Terms
                </v-toolbar>
                <v-card-text>

                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn
                         color="primary"
                        >Save
                        </v-btn>
                        <v-btn
                            text
                            @click="close()"
                        >Close
                    </v-btn>
                </v-card-actions>
                </div>

            </v-card>
            </template>
        </v-dialog>
        <!-- //////end_dialog////// -->

        <!-- //////dialog_for_delete_item_categories////// -->
        <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
                <v-card-title class="text-h5">Are you sure you want to delete this data?</v-card-title>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- //////end_dialog////// -->

        <!-- //////dialog_for_delete_item_categories////// -->
        <v-dialog v-model="dialogEditCategoryItem" transition="dialog-top-transition"
            max-width="800">
            <v-card>
                <v-toolbar
                color="primary"
                dark>
                Edit Item Category
                </v-toolbar>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="12">
                                        <div style="background-color: #2196F3; padding:10px;">
                                            <span style="color:white; font-weight:bold; letter-spacing: 2px;">Edit Data</span>
                                        </div> 
                                </v-col>  
                            </v-row>
                            <v-row>
                                <v-form class="width"
                                    ref="category_form"                              
                                    lazy-validation>
                                <div class='d-flex'>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="3"
                                >
                                <v-text-field
                                label='Category Name'
                                clearable
                                v-model="defaultCategoryItem.category_name"
                                >
                                </v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="3"
                                >
                                <v-text-field
                                label='SubCategory Name'
                                clearable
                                v-model="defaultCategoryItem.subcategory_name"
                                :disabled="defaultCategoryItem.subcategory_name=='N/A'"
                                 >
                                </v-text-field>
                                </v-col>
                                </div>                                    
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="3"
                                >
                                    <v-btn
                                        color="primary"
                                        fab
                                        x-small
                                        dark
                                        class="position_edit_category_button"
                                        :disabled="!defaultCategoryItem.category_name || !defaultCategoryItem.subcategory_name || selectedCategoryItem.category_name == defaultCategoryItem.category_name && selectedCategoryItem.subcategory_name == defaultCategoryItem.subcategory_name"
                                        @click="updateCategoryItem()"
                                        >
                                        <v-icon>mdi-plus</v-icon>
                                    </v-btn>
                                </v-col>
                                </v-form>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn
                            text
                            @click="dialogEditCategoryItem=false"
                        >Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- //////end_dialog////// -->

   </app-layout>
</template>

<script>
    import AppLayout from '../Layouts/AppLayout'
    export default {

        components: {
            AppLayout,
        },

        data: () => ({
            successSnackbar : false,
            cards: ['Data Management'],
            tab: 0,
            dialog : false,
            dialogEditCategoryItem : false,
            dialogDelete: false,
            items: [
            'Item Categories', 'Item List', 'Plating Process', 'Vendors','Payment Terms'
            ],
            search: '',
            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            headers: [
                {
                text: 'Category Name',
                align: 'start',
                value: 'category_name',
                class: "yellow"
                },
                { text: 'Sub-Category', value: 'subcategory_name', class: "yellow"},
                { text: 'Actions', value: 'actions', sortable: false, class: "yellow" },
            ],
            category_val:'',
            subcategory_val:'',
            category_name_selected:'',
            category_name_items:[],
            item_categories: [],

            category_valid: true,
            subcategory_valid: true,

            defaultCategoryItem: {
                category_name: '',
                subcategory_name: '',
            },

            selectedCategoryItem:{
                category_name: '',
                subcategory_name: '',
            }
        
    }),

    created: function(){
        this.getAvailableCateogryItems()
    },

    computed: {

    },

    watch: {
        dialog (val) {
            val || this.close()
        },

        dialogDelete(val) {
            val || this.closeDelete()
        }
    },

    methods: {
        
         addCategory(){
            axios.post('/addCategory', {cat_name : this.category_val})
              .then(response =>{
                    this.getAvailableCateogryItems()
                    this.successSnackbar = true
                    this.$refs.category_form.reset()
                    this.category_val=''
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {
                  
              });
         },

         deleteItemCategory(params){
              axios.post('/deleteItemCategory', {params : this.defaultCategoryItem})
              .then(response =>{
                    //console.log(response.data)
                    this.getAvailableCateogryItems()
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {
                  
              });
         },

         addSubCat(){
              axios.post('/addSubCategory', {cat_name : this.category_name_selected , subcat_name : this.subcategory_val})
              .then(response =>{
                    console.log(response.data)
                    this.successSnackbar = true
                    this.$refs.subcategory_form.reset()
                    this.category_name_selected=''
                    this.subcategory_val=''
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {
                  
              });
         },

        updateCategoryItem(){
            console.log(this.defaultCategoryItem)
        },

         close(){
              this.dialog = false;
              this.getAvailableCateogryItems()
            //   this.rfqs=[],
              this.$nextTick(() => {
                //    this.$refs.form.reset()
                //    this.data_items = [];
                this.$refs.category_form.reset()
                this.$refs.subcategory_form.reset()
                this.category_val=''
                this.category_name_selected=''
                this.subcategory_val=''
              })
          },

        getAvailableCateogryItems(){
              axios.get('/viewCatItem')
              .then(response =>{
                    this.item_categories = response.data[1]
                    this.category_name_items = response.data[0]
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

        closeDelete(){
            this.dialogDelete = false
        },

        deleteItem(item){
            this.defaultCategoryItem = Object.assign({},item)
            this.dialogDelete = true
        },

        deleteItemConfirm(){
            //console.log(this.defaultCategoryItem)
            this.deleteItemCategory(this.defaultCategoryItem)
            this.closeDelete()
        },

        editCategoryItem(item){
            this.dialogEditCategoryItem = true;
            this.defaultCategoryItem = Object.assign({}, item)
            this.selectedCategoryItem = Object.assign({}, item)
        },

        updateCategoryItem(){
              axios.post('/updateSubCategory', {updated_val : this.defaultCategoryItem , selected_val : this.selectedCategoryItem})
              .then(response =>{
                    this.getAvailableCateogryItems()
                    this.dialogEditCategoryItem = false
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
  .void-text{
   font-size: 150% !important;
  }
  .position_add{
   position: fixed;
   bottom: 15px;
   right: 37px;
  }
  .position_add_category{
   bottom: 80px;
   left: 180px;
  }
  .position_sub_category{
   top: 15px;
   right: 11px;
  }
  .width{
    width: 100% !important;
  }
  .position_edit_category_button{
    bottom: 80px;
    left: 360px;
  }
</style>