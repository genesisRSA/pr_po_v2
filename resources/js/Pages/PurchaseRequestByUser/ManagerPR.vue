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
        <v-snackbar
                v-model="missingInfoSupp"
                :timeout="3000"
                :value="true"
                bottom
                color="danger"
                success
                top
                right
                >
                <v-icon>mdi-flag-triangle</v-icon>
                Whoops,.. Some of supplier's info were missing. Please fill them up.
        </v-snackbar>
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
                        hide-default-footer
                        :loading="isPrTableLoading"
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
                                {{ item.item_category }}
                            </strong>
                        </template>

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
               </v-img>
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
                                                            <v-icon
                                                                small
                                                                class="mr-1"
                                                                style="position: relative; left: 3px; bottom: 1px;"
                                                            >
                                                                mdi-clock
                                                            </v-icon>
                                                        </v-chip>
                                                </template>

                                                <template v-slot:item.supplier_two="{ item }">
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
                                                </template>

                                                <template v-slot:item.supplier_three="{ item }">
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

              <v-dialog v-model="viewDialogPR" max-width="1200px">
                            <v-card>
<v-card-title class="text-h5"><span style='color:red;'>PR No : </span><span style='position:relative; left:5px;'>{{ prIdViewDialog }}</span><v-spacer></v-spacer><span style='color:red; position: relative; right:5px;'>Date Created : </span>{{ dateCreated }}</v-card-title>
                                <v-card-text>
                                        <v-data-table
                                            :headers="canChoose"
                                            hide-default-footer
                                            :items="viewedItems"
                                            class="elevation-1"
                                        >

                                                <template v-slot:item.supplier_one="{ item }">
                                                    <div v-if='item.id === editedItem.id' class="d-flex">
                                                        <v-select v-model="editedItem.supplier_one"
                                                        :items="supplier_list"
                                                        :item-text="supplier_list.text"
                                                        :item-value="supplier_list.value"
                                                        :item-disabled="checkIsItemDisabled"
                                                        @input="changeData_suppOne()"
                                                        :hide-details="true" dense single-line :autofocus="true"></v-select>
                                                    </div>
                                                <div v-else>
                                                    <div v-if='item.supplier_one == "PENDING" || item.supplier_one == null'>
                                                        <v-chip
                                                            :color="getColorForAddedItem(item.supplier_one)"
                                                            dark
                                                        >
                                                            PENDING
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
                                                        <span :class="getColorPreferredOne(item)">
                                                        {{ item.supplier_one }}
                                                        </span>
                                                           <v-icon
                                                                small
                                                                class="mr-2"
                                                                @click="getSuppInfo([item,'SUPPLIER ONE'])"
                                                            >
                                                                mdi-information
                                                            </v-icon>
                                                    </div>
                                                </div>
                                                </template>

                                                <template v-slot:item.supplier_two="{ item }">
                                                    <div v-if='item.id === editedItem.id' class="d-flex">
                                                        <v-select v-model="editedItem.supplier_two"
                                                        :items="supplier_list"
                                                        :item-text="supplier_list.text"
                                                        :item-value="supplier_list.value"
                                                        :hide-details="true" dense single-line
                                                        :item-disabled="checkIsItemDisabled"
                                                        @input="changeData_suppOne()"
                                                        :autofocus="true"></v-select>
                                                    </div>
                                                <div v-else>
                                                    <div v-if='item.supplier_two == "PENDING" || item.supplier_two == null'>
                                                        <v-chip
                                                            :color="getColorForAddedItem(item.supplier_two)"
                                                            dark
                                                        >
                                                            PENDING
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
                                                        <span :class="getColorPreferredTwo(item)">
                                                        {{ item.supplier_two }}
                                                        </span>
                                                           <v-icon
                                                                small
                                                                class="mr-2"
                                                                @click="getSuppInfo([item,'SUPPLIER TWO'])"
                                                            >
                                                                mdi-information
                                                            </v-icon>
                                                    </div>
                                                </div>
                                                </template>

                                                <template v-slot:item.supplier_three="{ item }">
                                                     <div v-if='item.id === editedItem.id' class="d-flex">
                                                         <v-select v-model="editedItem.supplier_three"
                                                         :items="supplier_list" :item-text="supplier_list.text"
                                                         :item-value="supplier_list.value"
                                                         :item-disabled="checkIsItemDisabled"
                                                         @input="changeData_suppOne()"
                                                         :hide-details="true" dense single-line v-if="item.id === editedItem.id" ></v-select>
                                                     </div>
                                                 <div v-else>
                                                     <div v-if='item.supplier_three == "PENDING" || item.supplier_three == null'>
                                                        <v-chip
                                                            :color="getColorForAddedItem(item.supplier_three)"
                                                            dark
                                                        >
                                                            PENDING
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
                                                        <span :class="getColorPreferredThree(item)">
                                                        {{ item.supplier_three}}
                                                        </span>
                                                            <v-icon
                                                                small
                                                                class="mr-2"
                                                                @click="getSuppInfo([item,'SUPPLIER THREE'])"
                                                            >
                                                                mdi-information
                                                            </v-icon>
                                                    </div>
                                                 </div>
                                                </template>
                                                <template v-slot:item.chosen_supplier="{ item }">
                                                    <div class="d-flex">
                                                           <v-select :items='chosenSupp' :item-text="chosenSupp.text" :item-value="chosenSupp.value" v-model="item.chosen_supplier" @input='getSelectedFinalSupp(item)'></v-select>
                                                    </div>
                                                </template>
                                                <template v-slot:item.actions="{ item }">
                                                <div v-if="item.id === editedItem.id" class="d-flex">
                                                        <v-icon color="red" class="mr-3" @click="close">
                                                            mdi-window-close
                                                            </v-icon>
                                                            <v-icon color="green"  @click="saveSupplier">
                                                            mdi-content-save
                                                            </v-icon>
                                                </div>
                                                <div v-else>
                                                <v-icon
                                                    small
                                                    @click="editSupplier(item)"
                                                >
                                                    mdi-pencil
                                                </v-icon>
                                                </div>
                                                </template>
                                        </v-data-table>
                                </v-card-text>
                                <v-card-actions class="justify-end">
                                    <v-btn
                                    color="primary"
                                    :disabled="getOne()"
                                    @click="checkIfNoSupplier()"
                                    >Save</v-btn>
                                    <v-btn
                                        text
                                    @click='closeViewDialogPR()'
                                    >Close</v-btn>
                                </v-card-actions>
                            </v-card>
              </v-dialog>
              <v-dialog v-model="suppInfoDialog" max-width="700px">
                <v-card>
                    <v-card-title class="text-h5">{{ supp_no }}</v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="6"
                                >
                                <v-text-field
                                :label='supp_no'
                                v-model="supp_info_detail.supplier_name"
                                readonly>
                                </v-text-field>
                                </v-col>

                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="3"
                                >
                                <v-checkbox
                                label='is Preferred?'
                                v-model="supp_info_detail.isPreferred"
                                >
                                </v-checkbox>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                    <div>
                                        <vuetify-money
                                        v-model="supp_info_detail.supplier_cost"
                                        label="Supplier Cost"
                                        placeholder=" "
                                        :outlined="true"
                                        v-bind:options="options"
                                        />
                                    </div>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                <v-select label="Payment Method"
                                v-model="supp_info_detail.selected_payment_method"
                                :items="supp_info_detail.payment_method"
                                :item-text="supp_info_detail.payment_method.text"
                                :item-value="supp_info_detail.payment_method.value">
                                </v-select>
                                </v-col>

                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                >
                                    <v-menu
                                    ref="menu"
                                    v-model="menu"
                                    :close-on-content-click="false"
                                    :return-value.sync="supp_info_detail.eta"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="supp_info_detail.eta"
                                        label="Delivery Date"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                    </template>
                                    <v-date-picker
                                    v-model="supp_info_detail.eta"
                                    no-title
                                    scrollable
                                    :min="supp_info_detail.min_date"
                                    >
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        text
                                        color="primary"
                                        @click="menu = false"
                                    >
                                        Cancel
                                    </v-btn>
                                    <v-btn
                                        text
                                        color="primary"
                                        @click="$refs.menu.save(supp_info_detail.eta)"
                                    >
                                        OK
                                    </v-btn>
                                    </v-date-picker>
                                </v-menu>
                                </v-col>
                            </v-row>
                        </v-card-text>
                          <v-card-actions class="justify-end">
                                    <v-btn
                                    color="primary"
                                    @click="saveSuppInfo()"
                                    >Save</v-btn>
                                    <v-btn
                                        text
                                    @click='closeSuppInfoDialog()'
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
                    { text: 'Requestor', value: 'user_id', class: "yellow"},
                    { text: 'Target Cost', value: 'item_category', class: "yellow" },
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

                headersForViewItem : [
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
                    { text: 'Chosen Supplier', value: 'chosen_supplier', class: "yellow" },
                    { text: 'Actions', value: 'actions', sortable: false, class: "yellow" },
                ],
                defHeaderForViewItem : [
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
                viewedItems: [],

                selectedIndex : null,

                viewDialogPR: false,
                prIdViewDialog: null,

                isPreferredOne: '(Preferred)',
                isPreferredTwo: '(Preferred)',
                isPreferredThree: '(Preferred)',

                supplier_list: [],

                editedItem: {
                id: 0,
                supplier_one: '',
                supplier_two: '',
                supplier_three: '',
                chosenSupp: ''
                },

                defaultItem: {
                 id: 0
                },

                editedIndex : -1,

                suppInfoDialog : false,
                supp_no : null,

                supp_info_detail : {
                    id : null,
                    supplier_name : null,
                    isPreferred : false,
                    payment_method : [],
                    selected_payment_method : null,
                    eta: null,
                    min_date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                    supplier_cost: null
                },

                menu: false,
                canChoose: null,
                dateCreated : null,

                chosenSupp : [
                    {
                        text : 'SUPPLIER 1', value : '1'
                    },
                    {
                        text : 'SUPPLIER 2', value : '2'
                    },
                    {
                        text : 'SUPPLIER 3', value : '3'
                    },
                ],
                missingInfoSupp: false,
                isPrTableLoading: true,

                selectedForPRChanges: [],

                dialogRepeatPR: false
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
        viewDialogPR (val){
            val || this.closeViewDialogPR()
        },
        suppInfoDialog (val){
            val || this.closeSuppInfoDialog()
        }
    },

    methods: {
        getOne(){
            var count = 0;
            Object.entries(this.viewedItems).forEach(([key,val]) => {
                if(val.supplier_one == 'PENDING'){
                     count += 1
                }
                if(val.supplier_two == 'PENDING'){
                     count += 1
                }
                if(val.supplier_three == 'PENDING'){
                     count += 1
                }
            });
            if(count > 0){
                return true
            }
        },

        close () {
            setTimeout(() => {
                this.editedItem.supplier_one = null
                this.editedItem.supplier_two = null
                this.editedItem.supplier_three = null
                const source = { supplier_one: 'PENDING', supplier_two: 'PENDING', supplier_three: 'PENDING' };
                Object.assign(this.viewedItems[this.editedIndex],source)
            }, 300)
        },

        getMyPRlist(){
               axios.get('/getMyPRlistRequestor')
              .then(response =>{
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

        viewPR(item){


            localStorage.setItem('params', JSON.stringify(item));
            var localItem = JSON.parse(localStorage.getItem('params'));

               axios.get('/viewPRRequestor',{ params : localItem.id })
              .then(response =>{
                    this.prIdViewDialog = localItem.pr_no
                    this.viewDialogPR = true
                    this.viewedItems = response.data[0]
                    this.supplier_list = response.data[1]
                    this.dateCreated = localItem.created_at
                    if(response.data[2] == true){
                        this.canChoose = this.headersForViewItem
                    } else {
                        this.canChoose = this.defHeaderForViewItem
                    }
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
            if(params == 'PENDING' || params == null){
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

        saveSupplier(){
            axios.post('/saveEditedVendor', { params : {one : this.viewedItems[this.editedIndex] , two : this.editedItem} })
              .then(response =>{
                    Object.assign(this.viewedItems[this.editedIndex],this.editedItem)
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1;
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

            });
        },

        editSupplier(item){
            this.editedIndex = this.viewedItems.indexOf(item)
            this.editedItem = Object.assign({}, item);
            //console.log(this.editedItem)
        },

        saveEditedSupp(){
               axios.post('/saveVendor', { params : this.viewedItems })
              .then(response =>{
                  this.closeViewDialogPR()
                  this.getMyPRlist()
                    console.log(response.data)
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

            });
        },

        getSuppInfo(params){
              axios.get('/getSuppInfo', { params : params })
              .then(response =>{
                  this.suppInfoDialog = true
                  this.supp_no = params[1]

                  if(this.supp_no == 'SUPPLIER ONE'){ this.supp_info_detail.supplier_name = params[0]['supplier_one'] }
                  if(this.supp_no == 'SUPPLIER TWO'){ this.supp_info_detail.supplier_name = params[0]['supplier_two'] }
                  if(this.supp_no == 'SUPPLIER THREE'){ this.supp_info_detail.supplier_name = params[0]['supplier_three'] }

                  this.supp_info_detail.payment_method = response.data[1]
                  this.supp_info_detail.id = response.data[0][0]['id']
                  this.supp_info_detail.isPreferred = response.data[0][0]['is_preferred']
                  this.supp_info_detail.supplier_cost = response.data[0][0]['supplier_cost']
                  this.supp_info_detail.selected_payment_method = response.data[0][0]['payment_method']
                  this.supp_info_detail.eta = response.data[0][0]['eta']
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

            });
            localStorage.setItem('supp_detail_id',params[0]['id'])
        },

        closeViewDialogPR(){
            this.editedItem = Object.assign({}, this.defaultItem);
            this.editedIndex = -1;
            this.viewDialogPR = false
            this.getMyPRlist()
            localStorage.removeItem('params')
        },

        closeSuppInfoDialog(){
            this.supp_info_detail.id = null,
            this.supp_info_detail.supplier_name = null
            this.supp_info_detail.isPreferred = false
            this.supp_info_detail.payment_method = []
            this.supp_info_detail.selected_payment_method = null
            this.supp_info_detail.eta = null
            this.supp_info_detail.min_date = (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)
            this.supp_info_detail.supplier_cost = null
            this.suppInfoDialog = false
            localStorage.removeItem('supp_detail_id');
        },

        saveSuppInfo(){
            console.log(this.supp_info_detail)
                axios.post('/saveSuppInfo', {params: this.supp_info_detail ,supp_no : this.supp_no, local_supp_id : localStorage.getItem("supp_detail_id")} )
                .then(response =>{
                    this.closeSuppInfoDialog()
                    this.viewPR(JSON.parse(localStorage.getItem('params')))
                })
                .catch(error =>{
                        console.log(error.response);
                })
                .finally(() => {

                });
        },

        getSelectedFinalSupp(item){
                axios.post('/getSelectedFinalSupp', {params: item} )
                .then(response =>{
                        console.log(response.data)
                })
                .catch(error =>{
                        console.log(error.response);
                })
                .finally(() => {

                });
        },

        checkIfNoSupplier(){
               axios.get('/checkIfNoSupplier', {params:this.viewedItems})
              .then(response =>{
                    if(response.data == 'Good'){
                        this.saveEditedSupp()
                    } else {
                        this.missingInfoSupp = true
                    }
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

            });
        },

        getColorForStatus(params){
            if(params == 'PR APPROVED'){
                return 'green'
            } else {
                return 'orange'
            }
        },

    checkIsItemDisabled(item) {
        let arr = [this.viewedItems[this.editedIndex].supplier_one, this.viewedItems[this.editedIndex].supplier_two, this.viewedItems[this.editedIndex].supplier_three]
        return arr.includes(item.text)
      },

     changeData_suppOne(){
        Object.assign(this.viewedItems[this.editedIndex],this.editedItem)
        console.log(this.viewedItems[this.editedIndex])
    },

    getColorPreferredOne(item){
        if(item.SUPPLIER_ONE == 1){
            return 'blue--text'
        }
    },
    getColorPreferredTwo(item){
        if(item.SUPPLIER_TWO == 1){
            return 'blue--text'
        }
    },
    getColorPreferredThree(item){
        if(item.SUPPLIER_THREE == 1){
            return 'blue--text'
        }
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

  .position_add{
   position: fixed;
   bottom: 15px;
   right: 37px;
  }
  .bold-font:{
    font-weight: bolder;
}
</style>
