
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
                      <v-data-table
                        :headers="headers"
                        :items="users"
                        hide-default-footer
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
                        </v-toolbar>
                        </template>
                        <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editItem(item)"
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
                headers: [
                    {
                    text: 'Employee Name',
                    align: 'start',
                    value: 'name',
                    },
                    { text: 'Email', value: 'email' },
                    { text: 'Created at', value: 'created_at' },
                    { text: 'Updated at', value: 'updated_at' },
                    { text: 'Actions', value: 'actions', sortable: false },
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
                  }
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
        this.dialog = true
      },

      deleteItem (item) {
        this.editedIndex = this.users.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
      },

      deleteItemConfirm () {
        this.users.splice(this.editedIndex, 1)
        this.closeDelete()
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      save () {
        if (this.editedIndex > -1) {
          Object.assign(this.users[this.editedIndex], this.editedItem)
        } else {
          this.users.push(this.editedItem)
        }
        this.close()
      },

      /////////////////////////////////////////////////////

      getUserPermission(email){
          axios.get('/getUserAuthorization',{params : {'email': email}})
              .then(response =>{
                this.checkbox = response.data
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

</style>