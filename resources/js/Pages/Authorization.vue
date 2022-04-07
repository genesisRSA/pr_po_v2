
<template>
    <app-layout>
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
                        :items="users"
                        hide-default-footer
                        :search="search"
                        :page.sync="page"
                        :items-per-page="itemsPerPage"
                        @page-count="pageCount = $event"
                        sort-by="name"
                        class="elevation-1"
                    >
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
        </v-row>

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
    }),

    created: function(){
       this.initialize()
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
      }
    },

    methods: {
      initialize () {

              axios.get('/getEmpUser')
              .then(response =>{
                    this.users = response.data
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

      deleteItem (item) {
        this.editedIndex = this.users.indexOf(item)
        //this.editedItem = Object.assign({}, item)
        this.selectedUserPerm = item.email
        this.dialogDelete = true
      },

      deleteItemConfirm () {
        this.deleteUser(this.selectedUserPerm)
        this.closeDelete()
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.selectedUserPerm = ''
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.selectedUserPerm = ''
        })
      },

      save () {
        this.addOrEditUserPermission(this.selectedUserPerm)
        this.close()
      },

      /////////////////////////////////////////////////////

      getUserPermission(email){
          axios.get('/getUserAuthorization',{params : {'email': email}})
              .then(response =>{
                if(response.data == 'none'){
                  this.defaultChkBox()
                } else {
                  this.checkbox = response.data
                }
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
        }
              axios.post('/addOrEditUserPermission',{ params })
              .then(response =>{

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
        this.dialogVoid = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
          this.selectedUserPerm = ''
        })
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
</style>