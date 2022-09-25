
<template>
    <app-layout>
        <v-row>
          <v-col
            v-for="card in cards"
            :key="card"
            cols="12"
          >
            <v-card min-height="800">
            <v-img lazy-src="https://picsum.photos/id/11/10/6"
                min-height="800"
                max-width="1900"
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
                        :items="users"
                        hide-default-footer
                        :search="search"
                        :page.sync="page"
                        :items-per-page="itemsPerPage"
                        @page-count="pageCount = $event"
                        class="elevation-1"
                    >
                       <template v-slot:item.name="{ item }">
                                    <span
                                    :class="getColor(item.position)"
                                    >
                                    {{ item.name }}
                                    </span>
                                    <v-icon v-if="item.position != null && (item.position.position =='PRESIDENT' || item.position.position =='CEO' || item.position.position =='PURCHASE MNGR.' || item.position.position =='RTI APPROVER' || item.position.position =='RSA APPROVER')"
                                        small
                                        class="mr-2"
                                        v-bind="attrs"
                                        v-on="on"
                                        color="blue"
                                    >
                                        mdi-account-check
                                    </v-icon>
                        </template>
                        <template v-slot:item.company="{ item }">
                                    <span
                                    :class="(item.company == 'RSA' || item.company == 'RTI') ? '' : 'red--text'"
                                    >
                                    {{ item.company }}
                                    </span>
                        </template>
                        <template v-slot:top>
                        <v-toolbar
                            flat
                        >
                            <v-spacer></v-spacer>
                            <v-dialog
                            v-model="dialog"
                            max-width="500px"
                            >
                            <v-card>
                                <v-card-title>
                                      <span class="text-h5">{{ formTitle }}</span>
                                </v-card-title>
                                <v-card-title>
                                    <v-col cols="12"
                                        sm="6"
                                        md="6">
                                      <v-select placeholder='Select User Config'
                                      :items='itemsForUserConfig' @input='getUserConfig($event)' v-model="selectedUserConfig">
                                      </v-select>
                                    </v-col>
                                </v-card-title>

                                <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" md="12">
                                              <div style="background-color: #2196F3; padding:10px;">
                                                  <span style="color:white; font-weight:bold; letter-spacing: 2px;">Request for Quotation module</span>
                                              </div>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[0].view_rfq"
                                      label="Can View?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[0].add_rfq"
                                      label="Can Add?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[0].update_rfq"
                                      label="Can Update?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[0].delete_rfq"
                                      label="Can Delete?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="12">
                                              <div style="background-color: #2196F3; padding:10px;">
                                                  <span style="color:white; font-weight:bold; letter-spacing: 2px;">Purchase Request module</span>
                                              </div>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[1].view_pr"
                                      label="Can View?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[1].add_pr"
                                      label="Can Add?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[1].update_pr"
                                      label="Can Update?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[1].delete_pr"
                                      label="Can Delete?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="12">
                                              <div style="background-color: #2196F3; padding:10px;">
                                                  <span style="color:white; font-weight:bold; letter-spacing: 2px;">Purchase Order module</span>
                                              </div>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[2].view_po"
                                      label="Can View?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[2].add_po"
                                      label="Can Add?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[2].update_po"
                                      label="Can Update?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[2].delete_po"
                                      label="Can Delete?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="12">
                                              <div style="background-color: #2196F3; padding:10px;">
                                                  <span style="color:white; font-weight:bold; letter-spacing: 2px;">Data Management module</span>
                                              </div>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[3].view_dm"
                                      label="Can View?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[3].add_dm"
                                      label="Can Add?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[3].update_dm"
                                      label="Can Update?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="3"
                                    >
                                    <v-checkbox
                                      v-model="checkbox[3].delete_dm"
                                      label="Can Delete?"
                                      disabled
                                    ></v-checkbox>
                                    </v-col>
                                    </v-row>
                                </v-container>
                                </v-card-text>

                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="close"
                                >
                                    Cancel
                                </v-btn>
                                <v-btn
                                    color="blue darken-1"
                                    text
                                    @click="save"
                                >
                                    Save
                                </v-btn>
                                </v-card-actions>
                            </v-card>
                            </v-dialog>
                            <v-dialog v-model="dialogDelete" max-width="500px">
                            <v-card>
                                <v-card-title class="text-h5">Are you sure you want to delete this user?</v-card-title>
                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                                <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                                <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-card>
                            </v-dialog>
                            <v-dialog v-model="dialogVoid" max-width="500px">
                            <v-card>
                                <v-card-title class="justify-center void-text"><h5>This user's <span style="color : red !important;">all</span> permission would be reverted.</h5><br><h5> Do you wish to continue?</h5></v-card-title>
                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="closeVoid">Cancel</v-btn>
                                <v-btn color="blue darken-1" text @click="voidItemConfirm">OK</v-btn>
                                <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-card>
                            </v-dialog>
                        </v-toolbar>
                        </template>
                        <template v-slot:item.actions="{ item }">

                        <div class='d-flex' v-if='item.role_as==0'>

                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                              <v-icon
                                  small
                                  class="mr-2"
                                  @click="editItem(item)"
                                  v-bind="attrs"
                                  v-on="on"
                              >
                                  mdi-pencil
                              </v-icon>
                          </template>
                          <span>Edit Permission</span>
                        </v-tooltip>


                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                              <v-icon
                                  small
                                  class="mr-2"
                                  @click="voidItem(item)"
                                  v-bind="attrs"
                                  v-on="on"
                              >
                                  mdi-lock-reset
                              </v-icon>
                          </template>
                          <span>Void Permission</span>
                        </v-tooltip>

                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                              <v-icon
                                  small
                                  @click="deleteItem(item)"
                                  v-bind="attrs"
                                  v-on="on"
                              >
                                  mdi-delete
                              </v-icon>
                          </template>
                          <span>Delete User</span>
                        </v-tooltip>
                        </div>
                        <div v-else>
                              <v-icon
                                  small
                                  class="mr-2"
                              >
                                  mdi-shield-account
                              </v-icon>
                              <span style='position: relative; right: 8px;'>ADMIN</span>
                        </div>

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
        </v-row>

         <v-row>
          <v-col
            cols="12"
          >
            <v-card min-height="800">
            <v-img lazy-src="https://picsum.photos/id/11/10/6"
                max-height="800"
                max-width="1900"
                src="https://img.wallpapersafari.com/desktop/1600/900/81/18/WewaSt.jpg">
            <div class="mt-5"></div>
              <v-subheader><h1 class="mt-5">{{ deptName }}</h1></v-subheader>
              <v-card-text>
              <v-divider class='mb-5'></v-divider>
                    <v-row justify="start">
                    <v-col
                          cols="12"
                          sm="6"
                          md="3"
                          class='mt-4'
                        >
                      <v-btn color='primary' @click='addDept()'>
                        Add Department
                      </v-btn>
                      </v-col>
                      <v-spacer></v-spacer>
                        <v-col
                          cols="12"
                          sm="6"
                          md="3"
                        >
                    <v-text-field
                        v-model="searchForDept"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                        class="mb-10"
                      ></v-text-field>
                      </v-col>
                    </v-row>

                      <v-data-table
                        :headers="headersForDept"
                        :items="dept"
                        hide-default-footer
                        :search="searchForDept"
                        :page.sync="pageForDept"
                        :items-per-page="itemsPerPageForDept"
                        @page-count="pageCountForDept = $event"
                        sort-by="name"
                        class="elevation-1"
                    >

                        <template v-slot:item.dept_head_name="{ item }">
                            <span
                            :class="getColorDeptHead(item.dept_head_name)"
                            >
                            {{ item.dept_head_name }}
                            </span>
                        </template>

                        <template v-slot:item.actions="{ item }">

                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                              <v-icon
                                  small
                                  class="mr-2"
                                  @click="editDept(item)"
                                  v-bind="attrs"
                                  v-on="on"
                              >
                                  mdi-pencil
                              </v-icon>
                          </template>
                          <span>Edit Department</span>
                        </v-tooltip>


                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                              <v-icon
                                  small
                                  @click="deleteDept(item)"
                                  v-bind="attrs"
                                  v-on="on"
                              >
                                  mdi-delete
                              </v-icon>
                          </template>
                          <span>Delete Department</span>
                        </v-tooltip>

                        </template>
                    </v-data-table>
                    <div class="text-center pt-2">
                    <v-pagination
                        v-model="pageForDept"
                        :length="pageCountForDept"
                        circle
                        :total-visible="7"
                    ></v-pagination>
                    </div>
               </v-card-text>
               </v-img>
            </v-card>
          </v-col>
        </v-row>

        <v-dialog v-model="editDialogForDept" transition="dialog-top-transition"
            max-width="800">
            <v-card>
                <v-toolbar
                color="primary"
                dark>
                Edit Department
                </v-toolbar>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="12">
                                        <div style="background-color: #2196F3; padding:10px;">
                                            <span style="color:white; font-weight:bold; letter-spacing: 2px;">Edit Department</span>
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
                            label='Department Code'
                            v-model="modelForDept.dept_code"
                            @input="(val) => (modelForDept.dept_code ? modelForDept.dept_code = modelForDept.dept_code.toUpperCase() : null)" readonly>
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Department Name'
                            v-model="modelForDept.dept_name"
                            @input="(val) => (modelForDept.dept_name ? modelForDept.dept_name = modelForDept.dept_name.toUpperCase() : null)">
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-select
                                label="Department Head"
                                clearable
                                :items='deptHeadOptions'
                                :item-text="deptHeadOptions.text"
                                :item-value="deptHeadOptions.value"
                                v-model='modelForDept.dept_head'>
                            </v-select>
                            </v-col>
                        </v-row>

                        </v-container>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn
                         color="primary"
                         :disabled="(modelForDept.dept_code == '' || modelForDept.dept_name == '' || (modelForDept.dept_head == '' || modelForDept.dept_head == null) ) || ( modelForDept.dept_code == selectedDept.dept_code && modelForDept.dept_name == selectedDept.dept_name && modelForDept.dept_head == selectedDept.dept_head )"
                         @click="updateDept()"
                        >Save Changes</v-btn>
                        <v-btn
                                text
                                @click="closeDialogEditDept()"
                            >Close
                        </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogAddDept" transition="dialog-top-transition"
            max-width="800">
            <v-card>
                <v-toolbar
                color="primary"
                dark>
                Add Department
                </v-toolbar>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="12">
                                        <div style="background-color: #2196F3; padding:10px;">
                                            <span style="color:white; font-weight:bold; letter-spacing: 2px;">Add Department</span>
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
                            label='Department Code'
                            v-model="modelForDept.dept_code"
                            @input="(val) => (modelForDept.dept_code ? modelForDept.dept_code = modelForDept.dept_code.toUpperCase() : null)">
                            </v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="4"
                            >
                            <v-text-field
                            label='Department Name'
                            v-model="modelForDept.dept_name"
                            @input="(val) => (modelForDept.dept_name ? modelForDept.dept_name = modelForDept.dept_name.toUpperCase() : null)">
                            </v-text-field>
                            </v-col>
                        </v-row>

                        </v-container>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn
                         color="primary"
                         :disabled="( (modelForDept.dept_code == '' || modelForDept.dept_code == null) || ( modelForDept.dept_name == '' || modelForDept.dept_name == null) )"
                         @click="addConfirmDept()"
                        >Save</v-btn>
                        <v-btn
                                text
                                @click="closeDialogAddDept()"
                            >Close
                        </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogDeleteDept" max-width="500px">
                            <v-card>
                                <v-card-title class="text-h5">Are you sure you want to delete this user?</v-card-title>
                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="closeDeleteDept">Cancel</v-btn>
                                <v-btn color="blue darken-1" text @click="deleteDeptConfirm">OK</v-btn>
                                <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-card>
          </v-dialog>
    </app-layout>
</template>

<script>
    import AppLayout from '../Layouts/AppLayout'
    export default {
        components: {
            AppLayout,
        },
            data: () => ({
                cards: ["Employee's Permission"],
                dialog: false,
                dialogDelete: false,
                dialogVoid: false,
                page: 1,
                pageCount: 0,
                itemsPerPage: 10,
                search: '',
                headers: [
                    { text: 'Employee ID', value: 'emp_id', class: "yellow" },
                    {
                    text: 'Employee Name',
                    align: 'start',
                    value: 'name',
                    class: "yellow"
                    },
                    { text: 'Company', value: 'company', class: "yellow"},
                    { text: 'Email', value: 'email', class: "yellow"},
                    { text: 'Created at', value: 'created_at', class: "yellow" },
                    { text: 'Updated at', value: 'updated_at', class: "yellow" },
                    { text: 'Actions', value: 'actions', sortable: false, class: "yellow" },
                ],
                users: [],
                checkbox:[
                  {
                    view_rfq: false,
                    add_rfq: false,
                    update_rfq: false,
                    delete_rfq: false,
                  },
                  {
                    view_pr: false,
                    add_pr: false,
                    update_pr: false,
                    delete_pr: false,
                  },
                  {
                    view_po: false,
                    add_po: false,
                    update_po: false,
                    delete_po: false,
                  },
                  {
                    view_dm: false,
                    add_dm: false,
                    update_dm: false,
                    delete_dm: false,
                  },
                ],
                editedIndex: -1,
                editedItem: {
                    name: '',
                    email: '',
                },
                defaultItem: {
                    name: '',
                    email: '',
                },
                selectedUserPerm : '',

////////////////////department list///////////////////
                pageForDept: 1,
                pageCountForDept: 0,
                itemsPerPageForDept: 10,
                searchForDept: '',
                deptName : 'Departments',
                headersForDept: [
                    { text: 'Department Code', value: 'dept_code', class: "yellow" },
                    {
                    text: 'Department Name',
                    align: 'start',
                    value: 'dept_name',
                    class: "yellow"
                    },
                    { text: 'Dept. Head', value: 'dept_head_name', class: "yellow" },
                    { text: 'Actions', value: 'actions', sortable: false, class: "yellow" },
                ],

                dept:[],
                editDialogForDept: false,
                dialogDeleteDept: false,
                dialogAddDept: false,

                modelForDept: {
                  dept_code: null,
                  dept_name: null,
                  dept_head: null,
                },

                selectedDept:{},

                itemsForUserConfig: ['REQUESTOR','BUYER','ADMIN MNGR.','RTI APPROVER','RSA APPROVER','PRESIDENT','CEO'],
                selectedUserConfig: '',

                deptHeadOptions : []
    }),

    created: function(){
       this.initialize()
       this.getAvailableDept()
    },

    computed: {
      formTitle () {
        return 'Configure Permission'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
      dialogVoid (val) {
        val || this.closeVoid()
      },
      editDialogForDept (val){
        val || this.closeDialogEditDept()
      },
      dialogAddDept (val){
        val || this.closeDialogAddDept()
      }
    },

    methods: {
      initialize () {

              axios.get('/getEmpUser')
              .then(response =>{
                    this.users = response.data[0]
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });

      },
      getAvailableDept(){
              axios.get('/getAvailableDept')
              .then(response =>{
                    this.dept = response.data[0]
                    this.deptHeadOptions = response.data[1]
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
      },

        editItem (item) {
        this.editedIndex = this.users.indexOf(item)
        //this.editedItem = Object.assign({}, item)
        //console.log(item.email)
        this.getUserPermission(item.email)
        this.selectedUserPerm = item.email
        this.dialog = true
      },

      editDept(item){
        this.editDialogForDept = true
        this.modelForDept = Object.assign({}, item)
        this.selectedDept= Object.assign({}, item)
      },

      deleteItem (item) {
        this.editedIndex = this.users.indexOf(item)
        //this.editedItem = Object.assign({}, item)
        this.selectedUserPerm = item.email
        this.dialogDelete = true
      },

      deleteDept(item){
        this.dialogDeleteDept = true
        this.selectedDept = Object.assign({}, item)
      },

      deleteItemConfirm () {
        this.deleteUser(this.selectedUserPerm)
        this.closeDelete()
      },

      deleteDeptConfirm(){
        let params = {
          'dept' : this.selectedDept
        }
              axios.post('/deleteDeptConfirm',{ params })
              .then(response =>{
                  this.dialogDeleteDept = false
                  this.getAvailableDept();
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

          });
      },

      close () {
        this.initialize()
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.selectedUserPerm = ''
          this.selectedUserConfig = ''
        })
      },

      closeDialogAddDept(){
        this.dialogAddDept = false
        this.modelForDept = {}
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.selectedUserPerm = ''
        })
      },

      closeDeleteDept(){
        this.dialogDeleteDept = false
      },

      save () {
        this.addOrEditUserPermission(this.selectedUserPerm)
        this.close()
      },

      /////////////////////////////////////////////////////

      getUserPermission(email){
          axios.get('/getUserAuthorization',{params : {'email': email}})
              .then(response =>{
                if(response.data[0] == 'none'){
                  this.defaultChkBox()
                } else {
                  this.checkbox = response.data[0]
                }
                this.selectedUserConfig = (response.data[1] == 'PURCHASE MNGR.' ? 'ADMIN MNGR.' : response.data[1])
                //console.log(response.data)
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

          });
      },


      addOrEditUserPermission(email){
        let params = {
          'email' : email,
          'chk' : this.checkbox,
          'selected' : this.selectedUserConfig
        }
            axios.post('/addOrEditUserPermission',{ params })
              .then(response =>{
                  //console.log(response.data)
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

          });
      },

      addConfirmDept(){
        let params = {
          'dept' : this.modelForDept
        }
              axios.post('/addConfirmDept',{ params })
              .then(response =>{
                this.dialogAddDept = false
                this.getAvailableDept()
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

          });
      },

      defaultChkBox(){
        this.checkbox[0].view_rfq = false;
        this.checkbox[0].add_rfq= false;
        this.checkbox[0].update_rfq = false;
        this.checkbox[0].delete_rfq = false;

        this.checkbox[1].view_pr = false;
        this.checkbox[1].add_pr = false;
        this.checkbox[1].update_pr = false;
        this.checkbox[1].delete_pr = false;

        this.checkbox[2].view_po = false;
        this.checkbox[2].add_po = false;
        this.checkbox[2].update_po = false;
        this.checkbox[2].delete_po = false;

        this.checkbox[3].view_dm = false;
        this.checkbox[3].add_dm = false;
        this.checkbox[3].update_dm = false;
        this.checkbox[3].delete_dm = false;
      },

       deleteUser(email){
              axios.post('/deleteUser',{ email })
              .then(response =>{
                     this.initialize()
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

          });
       },

      voidItem(item){
        this.editedIndex = this.users.indexOf(item)
        //this.editedItem = Object.assign({}, item)
        this.selectedUserPerm = item.email
        this.dialogVoid = true
       },

      voidItemConfirm () {
        this.voidSelectedUser(this.selectedUserPerm)
        this.closeVoid()
      },


      closeVoid(){
        this.initialize()
        this.dialogVoid = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.selectedUserPerm = ''
        })
      },

      closeDialogEditDept(){
        this.editDialogForDept = false
        this.modelForDept = {}
      },

      voidSelectedUser(user){
                axios.post('/voidUser',{ user })
              .then(response =>{
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

          });
      },

      updateDept(){
        let params = {
          'updated' : this.modelForDept,
        }
              axios.post('/updateDept',{ params })
              .then(response =>{
                  this.closeDialogEditDept()
                  this.getAvailableDept()
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

          });
      },

      addDept(){
        this.dialogAddDept = true
      },

      getUserConfig(params){
        if(params=='BUYER'){
          this.checkbox[0].view_rfq = false;
          this.checkbox[0].add_rfq= false;
          this.checkbox[0].update_rfq = false;
          this.checkbox[0].delete_rfq = false;

          this.checkbox[1].view_pr = true;
          this.checkbox[1].add_pr = true;
          this.checkbox[1].update_pr = true;
          this.checkbox[1].delete_pr = true;

          this.checkbox[2].view_po = true;
          this.checkbox[2].add_po = true;
          this.checkbox[2].update_po = true;
          this.checkbox[2].delete_po = true;

          this.checkbox[3].view_dm = true;
          this.checkbox[3].add_dm = true;
          this.checkbox[3].update_dm = true;
          this.checkbox[3].delete_dm = true;
        }

        if(params=='PRESIDENT'){
          this.checkbox[0].view_rfq = false;
          this.checkbox[0].add_rfq= false;
          this.checkbox[0].update_rfq = false;
          this.checkbox[0].delete_rfq = false;

          this.checkbox[1].view_pr = true;
          this.checkbox[1].add_pr = true;
          this.checkbox[1].update_pr = true;
          this.checkbox[1].delete_pr = true;

          this.checkbox[2].view_po = true;
          this.checkbox[2].add_po = true;
          this.checkbox[2].update_po = true;
          this.checkbox[2].delete_po = true;

          this.checkbox[3].view_dm = false;
          this.checkbox[3].add_dm = false;
          this.checkbox[3].update_dm = false;
          this.checkbox[3].delete_dm = false;
        }

        if(params=='CEO'){
          this.checkbox[0].view_rfq = false;
          this.checkbox[0].add_rfq= false;
          this.checkbox[0].update_rfq = false;
          this.checkbox[0].delete_rfq = false;

          this.checkbox[1].view_pr = true;
          this.checkbox[1].add_pr = true;
          this.checkbox[1].update_pr = true;
          this.checkbox[1].delete_pr = true;

          this.checkbox[2].view_po = true;
          this.checkbox[2].add_po = true;
          this.checkbox[2].update_po = true;
          this.checkbox[2].delete_po = true;

          this.checkbox[3].view_dm = false;
          this.checkbox[3].add_dm = false;
          this.checkbox[3].update_dm = false;
          this.checkbox[3].delete_dm = false;
        }

        if(params=='REQUESTOR'){
          this.checkbox[0].view_rfq = false;
          this.checkbox[0].add_rfq= false;
          this.checkbox[0].update_rfq = false;
          this.checkbox[0].delete_rfq = false;

          this.checkbox[1].view_pr = true;
          this.checkbox[1].add_pr = true;
          this.checkbox[1].update_pr = false;
          this.checkbox[1].delete_pr = true;

          this.checkbox[2].view_po = true;
          this.checkbox[2].add_po = true;
          this.checkbox[2].update_po = false;
          this.checkbox[2].delete_po = true;

          this.checkbox[3].view_dm = false;
          this.checkbox[3].add_dm = false;
          this.checkbox[3].update_dm = false;
          this.checkbox[3].delete_dm = false;
        }

        if(params=='ADMIN MNGR.'){
          this.checkbox[0].view_rfq = false;
          this.checkbox[0].add_rfq= false;
          this.checkbox[0].update_rfq = false;
          this.checkbox[0].delete_rfq = false;

          this.checkbox[1].view_pr = true;
          this.checkbox[1].add_pr = true;
          this.checkbox[1].update_pr = true;
          this.checkbox[1].delete_pr = true;

          this.checkbox[2].view_po = true;
          this.checkbox[2].add_po = true;
          this.checkbox[2].update_po = true;
          this.checkbox[2].delete_po = true;

          this.checkbox[3].view_dm = true;
          this.checkbox[3].add_dm = true;
          this.checkbox[3].update_dm = true;
          this.checkbox[3].delete_dm = true;
        }

        if(params=='RTI APPROVER'){
          this.checkbox[0].view_rfq = false;
          this.checkbox[0].add_rfq= false;
          this.checkbox[0].update_rfq = false;
          this.checkbox[0].delete_rfq = false;

          this.checkbox[1].view_pr = true;
          this.checkbox[1].add_pr = true;
          this.checkbox[1].update_pr = true;
          this.checkbox[1].delete_pr = true;

          this.checkbox[2].view_po = true;
          this.checkbox[2].add_po = true;
          this.checkbox[2].update_po = true;
          this.checkbox[2].delete_po = true;

          this.checkbox[3].view_dm = false;
          this.checkbox[3].add_dm = false;
          this.checkbox[3].update_dm = false;
          this.checkbox[3].delete_dm = false;
        }

        if(params=='RSA APPROVER'){
          this.checkbox[0].view_rfq = false;
          this.checkbox[0].add_rfq= false;
          this.checkbox[0].update_rfq = false;
          this.checkbox[0].delete_rfq = false;

          this.checkbox[1].view_pr = true;
          this.checkbox[1].add_pr = true;
          this.checkbox[1].update_pr = true;
          this.checkbox[1].delete_pr = true;

          this.checkbox[2].view_po = true;
          this.checkbox[2].add_po = true;
          this.checkbox[2].update_po = true;
          this.checkbox[2].delete_po = true;

          this.checkbox[3].view_dm = false;
          this.checkbox[3].add_dm = false;
          this.checkbox[3].update_dm = false;
          this.checkbox[3].delete_dm = false;
        }
      },
     getColor (calories) {

            if (calories != null && (calories.position =='PRESIDENT' || calories.position =='CEO' || calories.position =='PURCHASE MNGR.' || calories.position =='RTI APPROVER' || calories.position =='RSA APPROVER')) return 'blue--text'
            else return
     },

     getColorDeptHead(params){
        if (params == 'N/A') return 'red--text'
            else return
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
