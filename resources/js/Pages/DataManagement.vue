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
                Whoops, a duplicate record has been recognized.
            </v-snackbar>
            <v-snackbar
                v-model="updatedSnackbar"
                :timeout="3000"
                :value="true"
                bottom
                color="purple"
                success
                top
                right
                >
                <v-icon>mdi-lightbulb</v-icon>
                The record has been updated successfully.
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
                            @click="clearSearch()"
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



<!-- /////////////////////tab_item_for_item_list /////////////////////-->
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
                                    :headers="headersForItemList"
                                    :items="item_list"
                                    :search="search"
                                    hide-default-footer
                                    :page.sync="pageForItemList"
                                    :items-per-page="itemsPerPageForItemList"
                                    @page-count="pageCountForItemList = $event"
                                    class="mt-5"
                                    >
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
                                        <template v-slot:item.actions="{ item }">
                                            <v-icon
                                                small
                                                class="mr-2"
                                                @click="editItemList(item)"
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
                                        v-model="pageForItemList"
                                        :length="pageCountForItemList"
                                        circle
                                        :total-visible="7"
                                    ></v-pagination>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
<!-- ///////////////////end_tab_item ///////////////////////////////////////-->



<!-- /////////////////////tab_item_for_plating_processes /////////////////////-->
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
                                    :headers="headersForItemProcessingPlate"
                                    :search="search"
                                    :items="itemsForPlatingProcess"
                                    hide-default-footer
                                    :page.sync="pageForPlating"
                                    :items-per-page="itemsPerPageForPlating"
                                    @page-count="pageCountForPlating = $event"
                                    class="mt-5"
                                    >
                                    <template v-slot:item.type="{ item }">
                                        <span
                                        :class="getColor(item.type)"
                                        >
                                        {{ item.type }}
                                        </span>
                                    </template>
                                        <template v-slot:item.actions="{ item }">
                                            <v-icon
                                                small
                                                class="mr-2"
                                                @click="editPlatingProcess(item)"
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
                                        v-model="pageForPlating"
                                        :length="pageCountForPlating"
                                        circle
                                        :total-visible="7"
                                    ></v-pagination>
                                    </div>
                            </v-card-text>
                            </v-card>
                        </v-tab-item>
<!-- ///////////////////end_tab_item ///////////////////////////////////////-->



<!-- /////////////////////tab_item_vendors /////////////////////-->
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
                                    :headers="headersForVendor"
                                    :search="search"
                                    :items="itemsForVendor"
                                    hide-default-footer
                                    :page.sync="pageForVendor"
                                    :items-per-page="itemsPerPageForVendor"
                                    @page-count="pageCountForVendor = $event"
                                    class="mt-5"
                                    >
                                        <template v-slot:item.actions="{ item }">
                                            <v-icon
                                                small
                                                class="mr-2"
                                                @click="editVendor(item)"
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
                                        v-model="pageForVendor"
                                        :length="pageCountForVendor"
                                        circle
                                        :total-visible="7"
                                    ></v-pagination>
                                    </div>
                            </v-card-text>
                            </v-card>
                        </v-tab-item>
<!-- ///////////////////end_tab_item ///////////////////////////////////////-->


<!-- /////////////////////tab_item_payment_term /////////////////////-->
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
                                    :headers="headersForPaymentTerm"
                                    :search="search"
                                    :items="itemsForPaymentTerm"
                                    hide-default-footer
                                    :page.sync="pageForPayment"
                                    :items-per-page="itemsPerPageForPayment"
                                    @page-count="pageCountForPayment = $event"
                                    class="mt-5"
                                    >
                                        <template v-slot:item.actions="{ item }">
                                            <v-icon
                                                small
                                                class="mr-2"
                                                @click="editPaymentTerm(item)"
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
                                        v-model="pageForPayment"
                                        :length="pageCountForPayment"
                                        circle
                                        :total-visible="7"
                                    ></v-pagination>
                                    </div>
                            </v-card-text>
                            </v-card>
                        </v-tab-item>

<!-- ///////////////////end_tab_item ///////////////////////////////////////-->

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
                >Add Item
                </v-toolbar>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="12">
                                    <div style="background-color: #2196F3; padding:10px;">
                                        <span style="color:white; font-weight:bold; letter-spacing: 2px;">Add an Item List</span>
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
                            label='Category Name'
                            clearable
                            :items="category_name_for_add_itemList"
                            :item-text="category_name_for_add_itemList.text"
                            :item-value="category_name_for_add_itemList.value"
                            v-model="category_val_for_list_item"
                            @input="selectingCategoryNameListForAdd($event)">
                            </v-select>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-select
                            label='Sub-Category Name'
                            clearable
                            :items="subcategory_name_for_add_itemList"
                            :item-text="subcategory_name_for_add_itemList.text"
                            :item-value="subcategory_name_for_add_itemList.value"
                            v-model="subcategory_val_for_list_item"
                            :disabled="!category_val_for_list_item">
                            </v-select>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Part Name'
                            clearable
                            v-model="part_name_for_list_item">
                            </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Material'
                            clearable
                            v-model="material_for_list_item">
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Dimension'
                            clearable
                            v-model="dimension_for_list_item">
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                                >
                                <div>
                                    <vuetify-money
                                    v-model="raw_unit_price_for_list_item"
                                    label="Unit Price Cost"
                                    placeholder=" "
                                    :outlined="true"
                                    :clearable="true"
                                    v-bind:options="options"
                                    />
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn
                         color="primary"
                         :disabled ="!category_val_for_list_item || !subcategory_val_for_list_item"
                         @click="addItemList()"
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
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="12">
                                    <div style="background-color: #2196F3; padding:10px;">
                                        <span style="color:white; font-weight:bold; letter-spacing: 2px;">Add a Plating Process</span>
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
                            label='Plating Process'
                            v-model="modelForPlatingProcesses.plating_process"
                            clearable>
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Type'
                            v-model="modelForPlatingProcesses.type"
                            clearable>
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >

                            <div>
                                <vuetify-money
                                v-model="modelForPlatingProcesses.price_per_square_inch"
                                label="Price per. Sq. Inch"
                                placeholder=" "
                                :outlined="true"
                                :clearable="true"
                                v-bind:options="options"
                                />
                            </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn
                         color="primary"
                            @click="addPlatingProcess()"
                            :disabled="!modelForPlatingProcesses.plating_process || !modelForPlatingProcesses.price_per_square_inch"
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
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="12">
                                    <div style="background-color: #2196F3; padding:10px;">
                                        <span style="color:white; font-weight:bold; letter-spacing: 2px;">Add a Vendor</span>
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
                            label='Company Name'
                            v-model="modelForVendor.company_name"
                            @input="(val) => (modelForVendor.company_name ? modelForVendor.company_name = modelForVendor.company_name.toUpperCase() : null)"
                            clearable>
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Company Address'
                            v-model="modelForVendor.address"
                            @input="(val) => (modelForVendor.address ? modelForVendor.address = modelForVendor.address.toUpperCase() : null)"
                            clearable>
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Contact Person'
                            v-model="modelForVendor.contact_person"
                            @input="(val) => (modelForVendor.contact_person ? modelForVendor.contact_person = modelForVendor.contact_person.toUpperCase() : null)"
                            clearable>
                            </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Contact Number'
                            v-model="modelForVendor.contact_number"
                            @input="(val) => (modelForVendor.contact_number ? modelForVendor.contact_number = modelForVendor.contact_number.toUpperCase() : null)"
                            clearable>
                            </v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn
                         color="primary"
                         @click="addVendor()"
                         :disabled="(modelForVendor.company_name =='' || modelForVendor.company_name ==null) || 
                                    (modelForVendor.address =='' || modelForVendor.address ==null) || 
                                    (modelForVendor.contact_number =='' || modelForVendor.contact_number ==null) || 
                                    (modelForVendor.contact_person =='' || modelForVendor.contact_person ==null)"
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
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="12">
                                    <div style="background-color: #2196F3; padding:10px;">
                                        <span style="color:white; font-weight:bold; letter-spacing: 2px;">Add a Payment Term</span>
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
                            label='Payment Term'
                            v-model="payment_term"
                            @input="(val) => (payment_term ? payment_term = payment_term.toUpperCase() : null)"
                            clearable>
                            </v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn
                         color="primary"
                         @click="addPaymentTerm()"
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

        <!-- //////dialog_for_edit_item_list////// -->
        <v-dialog v-model="dialogEditItemList" transition="dialog-top-transition"
            max-width="800">
            <v-card>
                <v-toolbar
                color="primary"
                dark>
                Edit Item List
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

                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                <v-select
                                label='Category Name'
                                clearable
                                :items="categoryNameList"
                                :item-text="categoryNameList.text"
                                :item-value="categoryNameList.value"
                                v-model="selectedItemList.cat_val"
                                @input="selectingCategoryNameList()"
                                >
                                </v-select>
                                </v-col>

                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                <v-select
                                label='Sub-Category Name'
                                clearable
                                :items="subCategoryNameList"
                                :item-text="subCategoryNameList.text"
                                :item-value="subCategoryNameList.value"
                                v-model="selectedItemList.subcat_val"
                                 >
                                </v-select>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                <v-text-field
                                label='Part Name'
                                clearable
                                v-model="selectedItemList.part_name == 'N/A' ? selectedItemList.part_name = null : selectedItemList.part_name"
                                 >
                                </v-text-field>
                                </v-col>

                            </v-row>
                                <!------- row 2 ---------->

                            <v-row>

                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                <v-text-field
                                label='Material'
                                clearable
                                v-model="selectedItemList.material == 'N/A' ? selectedItemList.material = null : selectedItemList.material"
                                >
                                </v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                <v-text-field
                                label='Dimension'
                                clearable
                                v-model="selectedItemList.dimension == 'N/A' ? selectedItemList.dimension = null : selectedItemList.dimension"
                                 >
                                </v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >

                                <div>
                                    <vuetify-money
                                    v-model="selectedItemList.raw_unit_price"
                                    label="Unit Price Cost"
                                    placeholder=" "
                                    :outlined="true"
                                    :clearable="true"
                                    v-bind:options="options"
                                    />
                                </div>
                                </v-col>

                             </v-row>


                                <!-------end of row 2 ---------->


                        </v-container>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn
                         color="primary"
                        @click="updateItemList()"
                        :disabled ="(selectedItemList.cat_val==null || selectedItemList.subcat_val==null) ||
                                    ((selectedItemList.cat_val == compareToSelectedItemList.cat_val || selectedItemList.cat_val == compareToSelectedItemList.cat_val.value)&&
                                    (selectedItemList.subcat_val == compareToSelectedItemList.subcat_val || selectedItemList.subcat_val == compareToSelectedItemList.subcat_val.value) &&
                                    selectedItemList.material == compareToSelectedItemList.material &&
                                    selectedItemList.part_name == compareToSelectedItemList.part_name &&
                                    selectedItemList.dimension == compareToSelectedItemList.dimension &&
                                    selectedItemList.raw_unit_price == compareToSelectedItemList.raw_unit_price)
                                    "
                        >Save Changes</v-btn>
                        <v-btn
                                text
                                @click="dialogEditItemList=false"
                            >Close
                        </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- //////end_dialog////// -->

        <!-- //////dialog_for_edit_process_plating////// -->
        <v-dialog v-model="dialogEditProcessPlating" transition="dialog-top-transition"
            max-width="800">
            <v-card>
                <v-toolbar
                color="primary"
                dark>
                Edit Plating Process
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
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Plating Process'
                            v-model="modelForPlatingProcesses.plating_process"
                            clearable>
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Type'
                            v-model="modelForPlatingProcesses.type == 'N/A'? modelForPlatingProcesses.type = null : modelForPlatingProcesses.type"
                            clearable>
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >

                            <div>
                                <vuetify-money
                                v-model="modelForPlatingProcesses.raw_price"
                                label="Price per. Sq. Inch"
                                placeholder=" "
                                :outlined="true"
                                :clearable="true"
                                v-bind:options="options"
                                />
                            </div>
                            </v-col>
                        </v-row>

                        </v-container>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn
                         color="primary"
                         :disabled="(modelForPlatingProcesses.plating_process == null || 
                                    modelForPlatingProcesses.raw_price == '' || 
                                    modelForPlatingProcesses.raw_price == '0.00') || 
                                    (modelForPlatingProcesses.raw_price == selectedPlatingProcesses.raw_price &&
                                    modelForPlatingProcesses.plating_process == selectedPlatingProcesses.plating_process &&
                                    modelForPlatingProcesses.type == selectedPlatingProcesses.type)"
                        @click="updatePlatingProcess()"
                        >Save Changes</v-btn>
                        <v-btn
                                text
                                @click="closeDialogEditProcessPlating()"
                            >Close
                        </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- //////dialog_for_edit_item_list////// -->

        <!-- //////dialog_for_edit_vendor////// -->
        <v-dialog v-model="dialogEditVendor" transition="dialog-top-transition"
            max-width="800">
            <v-card>
                <v-toolbar
                color="primary"
                dark>
                Edit Vendor
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
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Company Name'
                            v-model="modelForVendor.company_name"
                            @input="(val) => (modelForVendor.company_name ? modelForVendor.company_name = modelForVendor.company_name.toUpperCase() : null)"
                            clearable>
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Company Address'
                            v-model="modelForVendor.address"
                            @input="(val) => (modelForVendor.address ? modelForVendor.address = modelForVendor.address.toUpperCase() : null)"
                            clearable>
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Contact Person'
                            v-model="modelForVendor.contact_person"
                            @input="(val) => (modelForVendor.contact_person ? modelForVendor.contact_person = modelForVendor.contact_person.toUpperCase() : null)"
                            clearable>
                            </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Contact Number'
                            v-model="modelForVendor.contact_number"
                            @input="(val) => (modelForVendor.contact_number ? modelForVendor.contact_number = modelForVendor.contact_number.toUpperCase() : null)"
                            clearable>
                            </v-text-field>
                            </v-col>
                        </v-row>

                        </v-container>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn
                         color="primary"
                        :disabled="((modelForVendor.company_name =='' || modelForVendor.company_name ==null) || 
                                    (modelForVendor.address =='' || modelForVendor.address ==null) || 
                                    (modelForVendor.contact_number =='' || modelForVendor.contact_number ==null) || 
                                    (modelForVendor.contact_person =='' || modelForVendor.contact_person ==null)) ||
                                    (modelForVendor.company_name == selectedVendor.company_name &&
                                    modelForVendor.address== selectedVendor.address &&
                                    modelForVendor.contact_number == selectedVendor.contact_number &&
                                    modelForVendor.contact_person == selectedVendor.contact_person)"
                                @click="updateVendor()"
                        >Save Changes</v-btn>
                        <v-btn
                                text
                                @click="closeDialogEditVendor()"
                            >Close
                        </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- //////end_dialog////// -->

        <!-- //////dialog_for_edit_paymentTerm////// -->
        <v-dialog v-model="dialogEditPaymentTerm" transition="dialog-top-transition"
            max-width="800">
            <v-card>
                <v-toolbar
                color="primary"
                dark>
                Edit Payment Term
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
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Payment Term'
                            v-model="payment_term"
                            @input="(val) => (payment_term = payment_term.toUpperCase())"
                            clearable>
                            </v-text-field>
                            </v-col>
                        </v-row>

                        </v-container>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn
                         color="primary"
                          :disabled="(payment_term == '' || payment_term == null) || (payment_term == selecedPaymentTerm)"
                         @click="updatePaymentTerm()"
                        >Save Changes</v-btn>
                        <v-btn
                                text
                            @click="closeDialogEditPaymentTerm()"
                            >Close
                        </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- ///////////////////end-dialog///////// -->


   </app-layout>
</template>

<script>
    import AppLayout from '../Layouts/AppLayout'
    export default {

        components: {
            AppLayout,
        },

        data: () => ({
            deleteIndex : null,
            successSnackbar : false,
            dupliRecordSnackbar : false,
            updatedSnackbar : false,
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
            },

//-------------------- for item list section------------------------------------

            pageForItemList: 1,
            pageCountForItemList: 0,
            itemsPerPageForItemList: 10,

            headersForItemList: [
                {
                text: 'Category Name',
                align: 'start',
                value: 'category_name',
                class: "yellow"
                },
                { text: 'Sub-Category Name', value: 'subcategory_name', class: "yellow"},
                { text: 'Part Name', value: 'part_name', class: "yellow"},
                { text: 'Material', value: 'material', class: "yellow"},
                { text: 'Dimension', value: 'dimension', class: "yellow"},
                { text: 'Unit Price', value: 'unit_price', class: "yellow"},
                { text: 'Actions', value: 'actions', sortable: false, class: "yellow" },
            ],

            category_val_for_list_item : null,
            subcategory_val_for_list_item : null,
            part_name_for_list_item : null,
            material_for_list_item : null,
            dimension_for_list_item : null,
            raw_unit_price_for_list_item : null,

            item_list : [],
            dialogEditItemList : false,

            selectedItemList : {},
            compareToSelectedItemList : {},
            categoryNameList : [],
            subCategoryNameList : [],

            category_name_for_add_itemList : [],
            subcategory_name_for_add_itemList : [],

//-------------------- for plating process section------------------------------------


            pageForPlating: 1,
            pageCountForPlating: 0,
            itemsPerPageForPlating: 10,

            dialogEditProcessPlating : false,

            headersForItemProcessingPlate: [
                {
                text: 'Plating Process',
                align: 'start',
                value: 'plating_process',
                class: "yellow"
                },
                { text: 'Type', value: 'type', class: "yellow"},
                { text: 'Price per Sq. Inch', value: 'price_per_square_inch', class: "yellow"},
                { text: 'Actions', value: 'actions', sortable: false, class: "yellow" },
            ],

            itemsForPlatingProcess : [],

            modelForPlatingProcesses : {
                plating_process : null,
                type : null,
                price_per_square_inch : null,
                raw_price : null
            },

            selectedPlatingProcesses: {},


            clearable: true,
            options: {
            locale: "en-US",
            prefix: "₱",
            suffix: "",
            length: 11,
            precision: 2
            },

//-------------------- for vendor list------------------------------------

            pageForVendor: 1,
            pageCountForVendor: 0,
            itemsPerPageForVendor: 10,

            dialogEditVendor : false,
            headersForVendor: [
                {
                text: 'Company Name',
                align: 'start',
                value: 'company_name',
                class: "yellow"
                },
                { text: 'Address', value: 'address', class: "yellow"},
                { text: 'Contact Person', value: 'contact_person', class: "yellow"},
                { text: 'Contact No.', value: 'contact_number', class: "yellow"},
                { text: 'Actions', value: 'actions', sortable: false, class: "yellow" },
            ],

            itemsForVendor : [],

            modelForVendor : {
                company_name : null,
                address : null,
                contact_person : null,
                contact_number : null
            },

            selectedVendor: {},


//-------------------- for payment_term------------------------------------

            pageForPayment: 1,
            pageCountForPayment: 0,
            itemsPerPageForPayment: 10,

            dialogEditPaymentTerm : false,
            headersForPaymentTerm :[
                {
                text: 'Payment Term',
                align: 'start',
                value: 'payment_term',
                class: "yellow"
                },
                { text: 'Actions', value: 'actions', sortable: false, class: "yellow" },
            ],

            itemsForPaymentTerm : [],
            payment_term : null,
            idOfPaymentTerm : null,
    }),

    created: function(){
        this.getAvailableCateogryItems()
        this.getAvailableItemList()
        this.getAvailablePlatingProcesses()
        this.getAvailableVendor()
        this.getAvailablePaymentTerm()
    },

    computed: {

    },

    watch: {
        dialog (val) {
            val || this.close()
        },

        dialogDelete(val) {
            val || this.closeDelete()
        },

        dialogEditProcessPlating(val){
            val || this.closeDialogEditProcessPlating()
        },

        dialogEditVendor(val){
            val || this.closeDialogEditVendor()
        },

        dialogEditPaymentTerm(val){
            val || this.closeDialogEditPaymentTerm()
        }
    },

    methods: {

         addCategory(){
            axios.post('/addCategory', {cat_name : this.category_val})
              .then(response =>{
                    this.getAvailableCateogryItems()
                    this.getAvailableItemList()
                    if(response.data == 0){
                        this.successSnackbar = true
                    } else {
                        this.dupliRecordSnackbar = true
                    }
                    this.$refs.category_form.reset()
                    this.category_val=''
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
         },

         addItemList(){
            axios.post('/addItemList', {cat_val : this.category_val_for_list_item,
                                        subcat_val : this.subcategory_val_for_list_item,
                                        partname_val : this.part_name_for_list_item,
                                        material_val : this.material_for_list_item,
                                        dimension : this.dimension_for_list_item,
                                        unit_price : this.raw_unit_price_for_list_item})
              .then(response =>{
                    this.getAvailableItemList()
                    this.close();
                    if(response.data > 0){
                        this.dupliRecordSnackbar = true
                    } else {
                        this.successSnackbar = true
                    }
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
         },

         addPlatingProcess(){
               axios.post('/addPlatingProcess', {params : this.modelForPlatingProcesses})
              .then(response =>{
                    this.getAvailablePlatingProcesses();
                    this.close();
                    if(response.data > 0){
                        this.dupliRecordSnackbar = true
                    } else {
                        this.successSnackbar = true
                    }
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
         },

         addVendor(){
               axios.post('/addVendor', {params : this.modelForVendor})
              .then(response =>{
                    this.getAvailableVendor();
                    this.close();
                    if(response.data > 0){
                        this.dupliRecordSnackbar = true
                    } else {
                        this.successSnackbar = true
                    }
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
         },

         addPaymentTerm(){
               axios.post('/addPaymentTerm', {params : this.payment_term})
              .then(response =>{
                   this.getAvailablePaymentTerm()
                   this.close();
                    if(response.data == 0){
                        this.successSnackbar = true
                    } else {
                        this.dupliRecordSnackbar = true
                    }
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
                    this.getAvailableItemList()
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
         },

         deleteItemList(params){
              axios.post('/deleteItemList', {params : params})
              .then(response =>{
                    this.deleteIndex = null
                    this.getAvailableItemList()
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
         },

         deletePlatingProcess(params){
              axios.post('/deletePlatingProcess', {params : params})
              .then(response =>{
                    this.deleteIndex = null
                    this.getAvailablePlatingProcesses()
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
         },

        deleteVendor(params){
              axios.post('/deleteVendor', {params : params})
              .then(response =>{
                    this.getAvailableVendor()
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
         },

         deletePaymentTerm(params){
              axios.post('/deletePaymentTerm', {params : params})
              .then(response =>{
                    this.getAvailablePaymentTerm()
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
                    if (response.data == 0) {
                        this.successSnackbar = true
                    } else {
                        this.dupliRecordSnackbar = true
                    }
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
                this.category_val_for_list_item  = null
                this.subcategory_val_for_list_item  = null
                this.part_name_for_list_item  = null
                this.material_for_list_item  = null
                this.dimension_for_list_item  = null
                this.raw_unit_price_for_list_item = null

                this.tab == 0 ? this.$refs.category_form.reset() : false
                this.tab == 0 ? this.$refs.subcategory_form.reset() : false
                this.category_val=''
                this.category_name_selected=''
                this.subcategory_val=''

                this.modelForPlatingProcesses.plating_process = null,
                this.modelForPlatingProcesses.type = null,
                this.modelForPlatingProcesses.price_per_square_inch = null
                this.raw_price = null

                this.modelForVendor.company_name = null
                this.modelForVendor.address = null
                this.modelForVendor.contact_person = null
                this.modelForVendor.contact_number = null

                this.payment_term = null
                this.selecedPaymentTerm = null
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

              if(this.tab == 1){
              axios.get('/getcat_subcat_for_add_ItemList')
              .then(response =>{
                 this.category_name_for_add_itemList = response.data
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
              }

        },

        getAvailableItemList(){
              axios.get('/getAvailableItemList')
              .then(response =>{
                 this.item_list = response.data
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
        },

        getAvailablePlatingProcesses(){
              axios.get('/getAvailablePlatingProcesses')
              .then(response =>{
                  this.itemsForPlatingProcess = response.data
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
        },

        getAvailableVendor(){
              axios.get('/getAvailableVendor')
              .then(response =>{
                  this.itemsForVendor = response.data
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
        },

        getAvailablePaymentTerm(){
              axios.get('/getAvailablePaymentTerm')
              .then(response =>{
                  this.itemsForPaymentTerm = response.data
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
        },

        getcat_subcat_ItemList(params){
              axios.get('/getcat_subcat_ItemList', { params : params})
              .then(response =>{
                 this.categoryNameList = response.data[0]
                 this.subCategoryNameList = response.data[1]
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
        },

        selectingCategoryNameList(){
              axios.get('/selectingCategoryNameList', { params : this.selectedItemList})
              .then(response =>{
                  this.selectedItemList.subcat_val = null
                  this.subCategoryNameList = response.data
                 //console.log(response.data)
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
        },

        selectingCategoryNameListForAdd(val){
              axios.get('/selectingCategoryNameListForAdd', { params : val})
              .then(response =>{
                this.subcategory_val_for_list_item = null
                 this.subcategory_name_for_add_itemList = response.data
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

        closeDialogEditProcessPlating(){
            this.dialogEditProcessPlating = false
                            this.modelForPlatingProcesses.plating_process = null,
                this.modelForPlatingProcesses.type = null,
                this.modelForPlatingProcesses.price_per_square_inch = null
                this.modelForPlatingProcesses.raw_price = null
        },

        closeDialogEditVendor(){
            this.dialogEditVendor = false
                            this.modelForVendor.company_name = null
               this.modelForVendor.address = null
                this.modelForVendor.contact_person = null
                this.modelForVendor.contact_number = null
        },

        closeDialogEditPaymentTerm(){
            this.dialogEditPaymentTerm = false
            this.payment_term = null
        },

        deleteItem(item){
            if(this.tab == 0){
                this.defaultCategoryItem = Object.assign({},item)
                this.dialogDelete = true
            }
            if(this.tab == 1){
                this.deleteIndex = item.id
                this.dialogDelete = true
            }
            if(this.tab == 2){
                this.deleteIndex = item.id
                this.dialogDelete = true
            }
            if(this.tab == 3){
                this.deleteIndex = item.id
                this.dialogDelete = true
            }
            if(this.tab == 4){
                this.deleteIndex = item.id
                this.dialogDelete = true
            }
        },

        deleteItemConfirm(){
            if(this.tab == 0){
                this.deleteItemCategory(this.defaultCategoryItem)
                this.closeDelete()
            }
            if(this.tab == 1){
                this.deleteItemList(this.deleteIndex)
                this.closeDelete()
            }
            if(this.tab == 2){
                this.deletePlatingProcess(this.deleteIndex)
                this.closeDelete()
            }
            if(this.tab == 3){
                this.deleteVendor(this.deleteIndex)
                this.closeDelete()
            }
            if(this.tab == 4){
                this.deletePaymentTerm(this.deleteIndex)
                this.closeDelete()
            }

        },

        editCategoryItem(item){
            this.dialogEditCategoryItem = true;
            this.defaultCategoryItem = Object.assign({}, item)
            this.selectedCategoryItem = Object.assign({}, item)
        },

        updateCategoryItem(){
              axios.post('/updateSubCategory', {updated_val : this.defaultCategoryItem , selected_val : this.selectedCategoryItem})
              .then(response =>{
                    console.log(response.data)
                    if(response.data > 1){
                        this.dupliRecordSnackbar = true
                    } 
                    this.getAvailableCateogryItems()
                    this.getAvailableItemList()
                    this.dialogEditCategoryItem = false
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
        },

        updateItemList(){
              axios.post('/updateItemList', {params : this.selectedItemList})
              .then(response =>{
                  this.getAvailableItemList()
                  this.dialogEditItemList = false
                  if(response.data > 0){
                      this.dupliRecordSnackbar = true
                  } else {
                      this.updatedSnackbar = true
                  }
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });

        },

        updatePlatingProcess(){
            axios.post('/updatePlatingProcess', {params : this.modelForPlatingProcesses})
              .then(response =>{
                this.getAvailablePlatingProcesses()
                this.closeDialogEditProcessPlating()
                console.log(response.data)
                    if(response.data > 0){
                        this.dupliRecordSnackbar = true
                    } else {
                        this.successSnackbar = true
                    }
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
        },

        updateVendor(){
            axios.post('/updateVendor', {params : this.modelForVendor})
              .then(response =>{
                  if(response.data > 0){
                      this.dupliRecordSnackbar = true
                  } else {
                      this.updatedSnackbar = true
                  }
                  this.getAvailableVendor()
                  this.closeDialogEditVendor()
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
        },

        updatePaymentTerm(){
            let params = {
                payment_type :  this.payment_term,
                id : this.idOfPaymentTerm
            }
            axios.post('/updatePaymentTerm', {params : params})
              .then(response =>{
                  this.getAvailablePaymentTerm()
                  this.closeDialogEditPaymentTerm()
                   if(response.data > 0){
                       this.dupliRecordSnackbar = true
                   } else {
                       this.updatedSnackbar = true
                   }
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
        },

        editItemList(item){
            this.dialogEditItemList = true
            this.getcat_subcat_ItemList(item)
            this.selectedItemList = Object.assign({},item)
            this.compareToSelectedItemList = Object.assign({},item)
            //console.log(this.compareToSelectedItemList)
        },

        editPlatingProcess(item){
            this.dialogEditProcessPlating = true
            this.modelForPlatingProcesses = Object.assign({},item)
            this.selectedPlatingProcesses = Object.assign({},item)
            //console.log(item)
        },

        editVendor(item){
            this.dialogEditVendor = true
            this.modelForVendor = Object.assign({},item)
            this.selectedVendor = Object.assign({},item)
        },

        editPaymentTerm(item){
            this.dialogEditPaymentTerm = true
            this.payment_term = item.payment_term
            this.selecedPaymentTerm = item.payment_term
            this.idOfPaymentTerm = item.id
        },

        isNumber(event, quantity) {
            if (!/\d/.test(event.key) &&  
                (event.key !== "." || /\./.test(quantity))  
            )  
                return event.preventDefault();  
        },

        clearSearch(){
            this.search =''
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
  ::placeholder{
    font-style: italic;
  }
</style>
