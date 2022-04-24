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
     <v-row justify="space-around">

                <v-col cols="auto">
                <v-form ref="form" v-model="valid">
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
                        @click="addRFQ()"
                        >
                        <v-icon>mdi-plus</v-icon>
                    </v-btn>
                    </template>
                    <template v-slot:default="dialog">
                    <v-card>
                        <v-toolbar
                        color="primary"
                        dark
                        >Add a Request for Quotation</v-toolbar>
                        <v-card-text>
                        <div class="mt-8">
                              <v-card>
                                <v-tabs
                                color="primary"
                                left
                                >
                                <v-tab>REQUEST DETAILS</v-tab>
                                <v-tab>SIGNATORIES</v-tab>

                                <v-tab-item class="mt-5">
                                    <v-container fluid>
                                    <v-row>
                                        <v-col cols="12" md="4">
                                            <v-text-field
                                                label="Request Quotation Code"
                                                v-model="pr_code"
                                                readonly
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="4" class="usage_pos">
                                            <v-select
                                            :items="usage"
                                            label="Purpose*"
                                            dense
                                            v-model="purpose"
                                            :rules="purposeRules"
                                            menu-props="auto"
                                            :menu-props="{ bottom: true, offsetY: true }"
                                            clearable
                                            ></v-select>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="12">
                                            <v-text-field
                                                label="Remarks"
                                                v-model="remarks"
                                                clearable
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="12">
                                            <div style="background-color: #2196F3; padding:10px;">
                                                <span style="color:white; font-weight:bold; letter-spacing: 2px;">Item Details</span>
                                            </div>
                                       </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <v-menu
                                                ref="menu"
                                                v-model="menu"
                                                :close-on-content-click="false"
                                                :return-value.sync="delivery_date"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="auto"
                                            >
                                                <template v-slot:activator="{ on, attrs }">
                                                <v-text-field
                                                    v-model="delivery_date"
                                                    label="Delivery Date"
                                                    prepend-icon="mdi-calendar"
                                                    readonly
                                                    v-bind="attrs"
                                                    v-on="on"
                                                ></v-text-field>
                                                </template>
                                                <v-date-picker
                                                v-model="delivery_date"
                                                no-title
                                                scrollable
                                                :min="min_date"
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
                                                    @click="$refs.menu.save(delivery_date)"
                                                >
                                                    OK
                                                </v-btn>
                                                </v-date-picker>
                                            </v-menu>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <v-select
                                            :items="item_code"
                                            label="Item Code*"
                                            dense
                                            v-model="selectedItemCode"
                                            menu-props="auto"
                                            :menu-props="{ bottom: true, offsetY: true }"
                                            clearable
                                            :rules="itemcodeRules"
                                            :item-text="item_code.text"
                                            :item-value="item_code.value"
                                            ></v-select>
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                label="Description"
                                                clearable
                                                v-model="description"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="6">
                                            <v-select
                                            :items="units"
                                            label="Unit of Measure*"
                                            dense
                                            menu-props="auto"
                                            clearable
                                            v-model="unit_of_measure"
                                            :menu-props="{ bottom: true, offsetY: true }"
                                            :item-text="units.text"
                                            :item-value="units.value"
                                            :rules="unitmeasureRules"
                                            ></v-select>
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <v-text-field style="position:relative; top:-18px;"
                                                label="Quantity*"
                                                v-model="quantity"
                                                :rules="quantityRules"
                                                clearable
                                                @keypress="isNumber($event, quantity)"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="2">
                                            <v-btn color="primary" @click="pushItem" :disabled="!valid">
                                                <v-icon color="white">mdi-plus-circle</v-icon>
                                                Add Item
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="12">
                                            <div style="background-color: #2196F3; padding:10px;">
                                                <span style="color:white; font-weight:bold; letter-spacing: 2px;">Item List</span>
                                            </div>
                                       </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" md="12">
                                            <v-data-table
                                                :headers="headers"
                                                hide-default-footer
                                                :items="data_items"
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
                                </v-tab-item>
                                <v-tab-item class="mt-5">
                                    <v-container fluid>
                                    <v-row>
                                    </v-row>
                                    </v-container>
                                </v-tab-item>
                                </v-tabs>
                            </v-card>
                        </div>
                        </v-card-text>
                        <v-card-actions class="justify-end">
                        <v-btn
                         color="primary"
                         :disabled = 'data_items.length > 0 ? false : true'
                         @click="submitRFQ()"
                        >Save</v-btn>
                        <v-btn
                            text
                            @click="dialog.value = close()"
                        >Close</v-btn>
                        </v-card-actions>
                    </v-card>
                    </template>
                </v-dialog>
                </v-form>
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
                valid: true,
                dialog : false,
                usage : ['office use','project use'],
                units : [
                    { text: 'Millimeter(s)     (mm.)', value: 'mm' },
                    { text: 'Centimeter(s)     (cm.)', value: 'cm' },
                    { text: 'Inch(es)     (in.)', value: 'in' },
                    { text: 'Foot/Feet     (ft.)', value: 'ft' },
                    { text: 'Yard(s)     (yd.)', value: 'yd' },
                    { text: 'Meter(s)     (m.)', value: 'm' },
                    { text: 'Kilometer(s)     (km.)', value: 'km' },
                    { text: 'Mile(s)     (mi.)', value: 'mi' },
                 ],
                pr_code: '',
                purpose: '',
                remarks: '',
                quantity: '',
                unit_of_measure: '',
                selectedItemCode: '',
                description: '',
                menu: false,
                min_date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                delivery_date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                item_code : [
                    { text: 'ITEM-MAS-0007', value: 'ITEM-MAS-0007' },
                    { text: 'ITEM-MAS-0008', value: 'ITEM-MAS-0008' },
                    { text: 'ITEM-MAS-0009', value: 'ITEM-MAS-0009' },
                    { text: 'ITEM-MAS-00010', value: 'ITEM-MAS-00010' },
                ],
                itemcodeRules: [
                    v => !!v || 'Item Code is required',
                ],
                purposeRules: [
                    v => !!v || 'Purpose must be specified',
                ],
                unitmeasureRules: [
                    v => !!v || 'Unit of measure is required',
                ],
                quantityRules: [
                    v => !!v || 'Quantity must be specified',
                ],

                headers: [
                        {
                        text: 'PR Code',
                        align: 'start',
                        sortable: false,
                        value: 'pr_code',
                        },
                        { text: 'Item Code', value: 'item_code' },
                        { text: 'Item Description', value: 'description' },
                        { text: 'Quantity', value: 'quantity' },
                        { text: 'Unit of Measure', value: 'unit_of_measure' },
                        { text: 'Delivery Date', value: 'delivery_date' },
                        { text: 'Actions', value: 'actions', sortable: false },
                ],
                data_items: [],
                successSnackbar : false,

    }),

    created: function(){
       console.log(this.data_items)
       this.getRandomRFQCode()
    },

    computed: {

    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    methods: {
          close(){
              this.dialog = false;
              this.rfqs=[],
              this.$nextTick(() => {
                   this.$refs.form.reset()
                   this.data_items = [];
              })
          },
          addRFQ(){
              this.getRandomRFQCode()
          },
          pushItem(){
              this.data_items.push({pr_code : this.pr_code,
                              item_code : this.selectedItemCode,
                              description : this.description,
                              quantity : this.quantity,
                              unit_of_measure : this.unit_of_measure,
                              delivery_date : this.delivery_date
              })
              this.selectedItemCode = ''
              this.description = ''
              this.unit_of_measure = ''
              this.quantity = ''
              this.$refs.form.resetValidation()
          },

          deleteItem(data){
              let selectedDelete = this.data_items.indexOf(data)
              this.data_items.splice(selectedDelete,1)
          },

          getRandomRFQCode(){

              axios.get('/getRandomRFQCode')
              .then(response =>{
                    this.pr_code = response.data
                    this.delivery_date = (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
          },

          submitRFQ(){

            axios({
                url: '/submitRFQ',
                method: 'GET',
                params:  this.data_items ,
                responseType: 'blob', // important
            }).then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', this.pr_code+'.pdf');
                document.body.appendChild(link);
                link.click();
                this.successSnackbar = true;
            }).catch(error =>{
                    console.log(error.response);
            }).finally(() => {
                  this.close()
            });
          },
          isNumber(event, quantity) {
            if (!/\d/.test(event.key) &&
                (event.key !== "." || /\./.test(quantity))
            )
                return event.preventDefault();
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
.usage_pos{
   position: relative;
   bottom: -18px;
}
</style>
