<template>
  <v-app-bar app elevate-on-scroll elevation="3" color="#76528BFF" dark>
      <v-app-bar-nav-icon @click="$emit('handleDrawer')"></v-app-bar-nav-icon>
            <v-toolbar-title>Title</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>

      <v-btn icon>
        <v-icon>mdi-heart</v-icon>
      </v-btn>

      <v-btn icon>
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
  </v-app-bar>
  </template>
