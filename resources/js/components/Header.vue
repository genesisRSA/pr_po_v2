<template>
  <v-app-bar app elevate-on-scroll elevation="3" color="yellow" dark>
      <v-app-bar-nav-icon @click="$emit('handleDrawer')" color="primary"></v-app-bar-nav-icon>
            <v-toolbar-title class='toolbar_title'></v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon color="primary">
        <v-icon>mdi-magnify</v-icon>
      </v-btn>

      <v-btn icon color="primary">
        <v-icon>mdi-cog</v-icon>
      </v-btn>

      <v-btn icon color="primary">
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
  </v-app-bar>
  </template>

  <style>
      .toolbar_title{
         font-weight: bold;
      }
  </style>
