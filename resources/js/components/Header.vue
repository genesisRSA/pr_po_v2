<template>
  <v-app-bar app elevate-on-scroll elevation="3" color="yellow" dark>
      <v-app-bar-nav-icon @click="$emit('handleDrawer')" color="primary"></v-app-bar-nav-icon>
            <v-toolbar-title class='toolbar_title'></v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon color="primary" large>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>
        <div class="text-center" v-if='canViewNotif == "ALLOWED"'>

            <v-menu offset-y min-width="300" max-height="350" v-if="badgeC == 'new notif'">
                <template v-slot:activator="{ on, attrs }">

                      <v-badge
                      color="error"
                      overlap
                      :content="badgeC">
                      <v-btn
                      icon
                      color="primary"
                      dark
                      v-bind="attrs"
                      v-on="on"
                      large
                      @click="seeNotif()"
                      >
                      <v-icon>mdi-earth</v-icon>
                      </v-btn>
                      </v-badge>

                </template>
                <v-card>
                  <v-list-item-content class="justify-center">
                    <div class="mx-auto text-center">
                            <h3>Daily Notifications <v-icon color="primary">mdi-alert-circle-check</v-icon></h3>
                      <v-divider class="my-3"></v-divider>

                      <div v-if='items.length > 0'>
                      <template v-for="(item, index) in items">
                      <v-list-item class="text-left">
                          <v-list-item-title>{{ item.title }}</v-list-item-title>
                      </v-list-item>
                      <v-divider class="my-3" v-if="index != items.length - 1"></v-divider>
                      </template>
                      </div>
                      <div v-else>
                          <v-list-item class="text-left">
                              <v-list-item-title>No items where near expiry date so far.</v-list-item-title>
                          </v-list-item>
                      </div>

                    </div>
                  </v-list-item-content>
                </v-card>
            </v-menu>
            <div v-else>

            <v-menu offset-y min-width="300" max-height="350">
                <template v-slot:activator="{ on, attrs }">


                      <v-btn
                      icon
                      color="primary"
                      dark
                      v-bind="attrs"
                      v-on="on"
                      large
                      >
                      <v-icon>mdi-earth</v-icon>
                      </v-btn>


                </template>
                <v-card>
                  <v-list-item-content class="justify-center">
                    <div class="mx-auto text-center">
                            <h3>Daily Notifications <v-icon color="primary">mdi-alert-circle-check</v-icon></h3>
                      <v-divider class="my-3"></v-divider>

                      <div v-if='items.length > 0'>
                      <template v-for="(item, index) in items">
                      <v-list-item class="text-left">
                          <v-list-item-title>{{ item.title }}</v-list-item-title>
                      </v-list-item>
                      <v-divider class="my-3" v-if="index != items.length - 1"></v-divider>
                      </template>
                      </div>
                      <div v-else>
                          <v-list-item class="text-left">
                              <v-list-item-title>No items where near expiry date so far.</v-list-item-title>
                          </v-list-item>
                      </div>

                    </div>
                  </v-list-item-content>
                </v-card>
            </v-menu>

            </div>

        </div>

      <div v-if="canViewNotif=='ALLOWED'">
          <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon color="primary" large v-bind="attrs"
                      :href="route('costing')"
                      v-on="on">
                    <v-icon>mdi-clipboard-list</v-icon>
                  </v-btn>
                  </template>
                <span>Costing</span>
          </v-tooltip>
      </div>

  </v-app-bar>
  </template>


<script>
  export default {
    data: () => ({
      user: {
        initials: 'JD',
        fullName: 'John Doe',
        email: 'john.doe@doe.com',
      },
    items: [

      ],
    canViewNotif : null,
    badgeC : null
    }),

    created: function(){
        this.getUserForNotif()
        this.badgeCount()
    },

    methods: {

       getUserForNotif() {
               axios.get('/getUserForNotif')
              .then(response =>{
                    this.canViewNotif = response.data[0]
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
      },

      badgeCount(){
              axios.get('/getBadge')
              .then(response =>{
                    this.badgeC = response.data[0]
                    this.items = response.data[1]
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
      },

      seeNotif(){
              axios.get('/seeNotif')
              .then(response =>{

                        this.badgeC = response.data

              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

              });
      },



    }

  }
</script>

  <style>
      .toolbar_title{
         font-weight: bold;
      }
  </style>
