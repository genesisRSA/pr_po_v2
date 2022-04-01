
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
                  <v-container>
                  <div v-if="hasNoSitePermission=='has permission'">
                    <v-row>
                        <v-col
                        cols="12"
                        sm="6"
                        md="3"
                        >
                        <v-sheet
                        color="white"
                        elevation="1"
                        height="100"
                        width="370">
                        <v-hover v-slot="{ hover }">
                          <v-card
                            class="mx-auto"
                            color="grey lighten-4"
                            max-width="600"
                          >
                            <v-img
                              :aspect-ratio="16/9"
                              src="https://www.franchisemarket.ph/application/files/2315/8590/1658/wholesale-supplier.jpg"
                            >
                              <v-expand-transition>
                                <div
                                  v-if="hover"
                                  class="d-flex transition-fast-in-fast-out primary darken-2 v-card--reveal text-h2 white--text"
                                  style="height: 100%;"
                                >
                                  Suppliers
                                </div>
                              </v-expand-transition>
                            </v-img>
                            <v-card-text
                              class="pt-6"
                              style="position: relative;"
                            >
                              <v-btn
                                absolute
                                color="primary"
                                class="white--text"
                                fab
                                large
                                right
                                top
                              >
                                <v-icon>mdi-eye</v-icon>
                              </v-btn>
                              <div class="font-weight-light grey--text text-h6 mb-2">
                                For the resourcing materials.
                              </div>
                              <h3 class="text-h4 font-weight-light primary--text mb-2">
                                Suppliers
                              </h3>
                            </v-card-text>
                          </v-card>
                        </v-hover>
                        </v-sheet>
                        </v-col>
                        <v-col
                        cols="12"
                        sm="6"
                        md="3"
                        >
                        <v-sheet
                        color="white"
                        elevation="1"
                        height="100"
                        width="370">
                        <v-hover v-slot="{ hover }">
                          <v-card
                            class="mx-auto"
                            color="grey lighten-4"
                            max-width="600"
                          >
                            <v-img
                              :aspect-ratio="16/9"
                              src="https://www.re-thinkingthefuture.com/wp-content/uploads/2020/06/A1075-10-Different-construction-materials-around-the-globe-Steel-Image-1.jpg"
                            >
                              <v-expand-transition>
                                <div
                                  v-if="hover"
                                  class="d-flex transition-fast-in-fast-out primary darken-2 v-card--reveal text-h2 white--text"
                                  style="height: 100%;"
                                >
                                  Materials
                                </div>
                              </v-expand-transition>
                            </v-img>
                            <v-card-text
                              class="pt-6"
                              style="position: relative;"
                            >
                              <v-btn
                                absolute
                                color="primary"
                                class="white--text"
                                fab
                                large
                                right
                                top
                              >
                                <v-icon>mdi-eye</v-icon>
                              </v-btn>
                              <div class="font-weight-light grey--text text-h6 mb-2">
                                For building things up.
                              </div>
                              <h3 class="text-h4 font-weight-light primary--text mb-2">
                                Materials
                              </h3>
                            </v-card-text>
                          </v-card>
                        </v-hover>
                        </v-sheet>
                        </v-col>
                        <v-col
                        cols="12"
                        sm="6"
                        md="3"
                        >
                        <v-sheet
                        color="white"
                        elevation="1"
                        height="100"
                        width="370">
                        <v-hover v-slot="{ hover }">
                          <v-card
                            class="mx-auto"
                            color="grey lighten-4"
                            max-width="600"
                          >
                            <v-img
                              :aspect-ratio="16/9"
                              src="https://www.industrios.com/images/news/original/Purchase%20requisition.jpg"
                            >
                              <v-expand-transition>
                                <div
                                  v-if="hover"
                                  class="d-flex transition-fast-in-fast-out primary darken-2 v-card--reveal text-h4 white--text"
                                  style="height: 100%;"
                                >
                                      <ul style="list-style-type:none; position: relative; right:50px;">
                                        <li>Pending : 5</li>
                                        <li>Approved : 8</li>
                                        <li>Received : 15</li>
                                        <li>Declined : 2</li>
                                      </ul>
                                </div>
                              </v-expand-transition>
                            </v-img>
                            <v-card-text
                              class="pt-6"
                              style="position: relative;"
                            >
                              <v-btn
                                absolute
                                color="primary"
                                class="white--text"
                                fab
                                large
                                right
                                top
                              >
                                <v-icon>mdi-eye</v-icon>
                              </v-btn>
                              <div class="font-weight-light grey--text text-h6 mb-2">
                                 Request the purchase of goods or services on behalf of the company
                              </div>
                              <h3 class="text-h4 font-weight-light primary--text mb-2">
                                Purchase Request
                              </h3>
                            </v-card-text>
                          </v-card>
                        </v-hover>
                        </v-sheet>
                        </v-col>
                        <v-col
                        cols="12"
                        sm="6"
                        md="3"
                        >
                        <v-sheet
                        color="white"
                        elevation="1"
                        height="100"
                        width="370">
                        <v-hover v-slot="{ hover }">
                          <v-card
                            class="mx-auto"
                            color="grey lighten-4"
                            max-width="600"
                          >
                            <v-img
                              :aspect-ratio="16/9"
                              src="https://a.storyblok.com/f/67269/1000x667/bb052a46e5/purchaseorders_shutterstock_304860230.jpg"
                            >
                              <v-expand-transition>
                                <div
                                  v-if="hover"
                                  class="d-flex transition-fast-in-fast-out primary darken-2 v-card--reveal text-h4 white--text"
                                  style="height: 100%;"
                                >
                                      <ul style="list-style-type:none; position: relative; right:50px;">
                                        <li>Pending : 3</li>
                                        <li>Approved : 2</li>
                                        <li>Received : 10</li>
                                        <li>Declined : 4</li>
                                      </ul>
                                </div>
                              </v-expand-transition>
                            </v-img>
                            <v-card-text
                              class="pt-6"
                              style="position: relative;"
                            >
                              <v-btn
                                absolute
                                color="primary"
                                class="white--text"
                                fab
                                large
                                right
                                top
                              >
                                <v-icon>mdi-eye</v-icon>
                              </v-btn>
                              <div class="font-weight-light grey--text text-h6 mb-2">
                                Finalize your canvassed goods with the PO.
                              </div>
                              <h3 class="text-h4 font-weight-light primary--text mb-2">
                                Purchase Order
                              </h3>
                            </v-card-text>
                          </v-card>
                        </v-hover>
                        </v-sheet>
                        </v-col>
                    </v-row>
                    </div>
                    <div v-else>
                        <p>
                            {{ noPermMSG }}
                        </p>
                    </div>
                  </v-container>
               </v-card-text>
            </v-card>
          </v-col>
        </v-row>
        <!-- <v-btn
              color="success"
              fab
              x-large
              dark
              class="position_add"
            >
              <v-icon>mdi-plus</v-icon>
        </v-btn> -->
    </app-layout>
</template>

<script>
    import AppLayout from '../Layouts/AppLayout'
    export default {
        components: {
            AppLayout,
        },
            data: () => ({


           hasNoSitePermission : null,
           noPermMSG: '',

      cards: ['My Dashboard'],

    }),

    created: function(){
        this.ifNoSitePermission()
    },

    computed: {

    },

    watch: {

    },

    methods: {
      

            ifNoSitePermission(){

              axios.get('/getSitePermission')
              .then(response =>{
                    this.hasNoSitePermission = response.data

                    if(this.hasNoSitePermission != "has permission"){
                      this.noPermMSG = "Seems like you don't have enough permission to access the features..."
                    }
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {
                  
              });
          },
          
        },
    }
</script>

<style>
.v-card--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  opacity: 1;
  position: absolute;
  width: 100%;
}
.stick{
    position: sticky;
}
.color_for_text{
    color: #0000B9 !important;
}
.color_for_card_text{
   color: #0000B9;
}
.switch_class{
   position: relative;
   bottom: -10px;
}
.position_add{
   position: fixed;
   bottom: 15px;
   right: 37px;
}
</style>


