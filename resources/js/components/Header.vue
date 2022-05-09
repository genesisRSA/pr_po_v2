<template>
  <v-app-bar app elevate-on-scroll elevation="3" color="yellow" dark>
      <v-app-bar-nav-icon @click="$emit('handleDrawer')" color="primary"></v-app-bar-nav-icon>
            <v-toolbar-title class='toolbar_title'></v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon color="primary" large>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>
        <div class="text-center">
            <v-menu offset-y min-width="300" max-height="350">
            <template v-slot:activator="{ on, attrs }">
                <v-badge
                 color="error"
                overlap
                content="4">
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
                </v-badge>
            </template>
        <v-card>
          <v-list-item-content class="justify-center">
            <div class="mx-auto text-center">
                    <h3>Daily Notifications <v-icon color="primary">mdi-alert-circle-check</v-icon></h3>
              <v-divider class="my-3"></v-divider>

              <template v-for="(item, index) in items">
              <v-list-item class="text-left">
                  <v-list-item-title>{{ item.title }}</v-list-item-title>
              </v-list-item>
              <v-divider class="my-3" v-if="index != items.length - 1"></v-divider>
              </template>

            </div>
          </v-list-item-content>
        </v-card>
            </v-menu>
        </div>


      <v-btn icon color="primary" large>
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
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
        { title: 'CW-02 was about to expired on May 09, 2022' },
        { title: 'DN-03 was about to expired on June 09, 2022' },
        { title: 'PA-04 was about to expired on June 12, 2022' },
        { title: 'CW-05 was about to expired on June 23, 2022' },
      ],
    }),
  }
</script>

  <style>
      .toolbar_title{
         font-weight: bold;
      }
  </style>
