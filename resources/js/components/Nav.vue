<template>
    <div>
        <v-app-bar app>
            <v-container class="d-flex">
                <v-toolbar-title></v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items text class="hidden-xs-only">
                    <inertia-link :href="route('welcome')" :class="route().current('welcome') ? 'text-decoration' : 'text-decoration-none'" >
                        <v-btn text color="blue" class="newButton">
                            <v-icon color="blue lighten-3" left small>mdi-home-city</v-icon>
                            Home
                        </v-btn>
                    </inertia-link>
                </v-toolbar-items>
                <v-divider vertical></v-divider>
                <v-toolbar-items v-if="$page.props.user" text class="hidden-xs-only">
                    <inertia-link :href="route('dashboard')" :class="route().current('dashboard') ? 'text-decoration' : 'text-decoration-none'" >
                        <v-btn text color="blue" class="newButton">
                            <v-icon color="blue lighten-3" left small>mdi-speedometer</v-icon>
                            Dashboard
                        </v-btn>
                    </inertia-link>
                    <v-divider vertical></v-divider>

                        <v-btn text @click="logout_dialog=!logout_dialog" class="newButton">
                            <v-icon color="blue lighten-3" left small>mdi-logout</v-icon>
                            Logout
                        </v-btn>

                </v-toolbar-items>
                <v-toolbar-items text v-else class="hidden-xs-only">
                    <inertia-link :href="route('login')" :class="route().current('login') ? 'text-decoration' : 'text-decoration-none'" >
                        <v-btn text color="blue" class="newButton">
                            <v-icon color="blue lighten-3" left small>mdi-login</v-icon>
                            Login
                        </v-btn>
                    </inertia-link>
                    <v-divider vertical></v-divider>
                    <inertia-link :href="route('register')" :class="route().current('register') ? 'text-decoration' : 'text-decoration-none'" >
                        <v-btn text color="blue" class="newButton">
                        <v-icon color="blue lighten-3" left small>mdi-text-box-check-outline</v-icon>
                            Register
                        </v-btn>
                    </inertia-link>
                </v-toolbar-items>
                <v-toolbar-items class="hidden-sm-and-up">
                    <v-app-bar-nav-icon @click="drawer = !drawer" class="navIconforDash"></v-app-bar-nav-icon>
                </v-toolbar-items>
            </v-container>
        </v-app-bar>

        <v-navigation-drawer
            v-model="drawer"
            temporary
            app
            right
        >
            <v-list
                nav
                dense
                v-if="$page.props.user"
            >
                <v-list-item-group
                    v-model="group"
                >

                    <v-list-item>
                        <v-btn text class="sideButton">
                            <v-icon color="blue lighten-3" left small>mdi-home-city</v-icon>
                            <inertia-link :href="route('welcome')" :class="route().current('welcome') ? 'text-decoration' : 'text-decoration-none'">Home</inertia-link>
                        </v-btn>
                    </v-list-item>

                    <v-list-item>
                        <v-btn text class="sideButton">
                            <v-icon color="blue lighten-3" left small>mdi-speedometer</v-icon>
                            <inertia-link :href="route('dashboard')" :class="route().current('dashboard') ? 'text-decoration' : 'text-decoration-none'">Dashboard</inertia-link>
                        </v-btn>
                    </v-list-item>

                    <v-list-item>
                        <form class="d-inline-flex align-center" @submit.prevent="logout">
                            <v-btn text type="submit" class="sideButton">
                                <v-icon color="blue lighten-3" left small>mdi-logout</v-icon>
                                Logout
                            </v-btn>

                        </form>
                    </v-list-item>
                            <v-dialog transition="fab-transition"
                                v-model="logout_dialog"
                                max-width="290"
                                >
                                <v-card>
                                    <v-card-title class="text-h5">
                                    Are you sure you want to logout?
                                    </v-card-title>

                                    <v-card-text>
                                    </v-card-text>

                                    <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="green darken-1"
                                        text
                                        @click="logout()"
                                    >
                                        Yes
                                    </v-btn>

                                    <v-btn
                                        color="green darken-1"
                                        text
                                        @click="logout_dialog = false"
                                    >
                                        No
                                    </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                </v-list-item-group>
            </v-list>

            <v-list
                nav
                dense
                v-else
            >
                <v-list-item-group
                    v-model="group"
                >

                    <v-list-item>
                        <v-btn text class="sideButton">
                            <inertia-link :href="route('welcome')" :class="route().current('welcome') ? 'text-decoration' : 'text-decoration-none'">Home</inertia-link>
                        </v-btn>
                    </v-list-item>

                    <v-list-item>
                        <v-btn text class="sideButton">
                            <inertia-link :href="route('login')" :class="route().current('login') ? 'text-decoration' : 'text-decoration-none'">Login</inertia-link>
                        </v-btn>
                    </v-list-item>

                    <v-list-item>
                        <v-btn text class="sideButton">
                            <inertia-link :href="route('register')" :class="route().current('register') ? 'text-decoration' : 'text-decoration-none'">Register</inertia-link>
                        </v-btn>
                    </v-list-item>

                </v-list-item-group>
            </v-list>

        </v-navigation-drawer>


    </div>
</template>

<script>
    export default {
        data: () => ({
            drawer: false,
            group: null,
            logout_dialog: false
        }),
        methods: {
            logout() {
                this.logout_dialog  = false;
                this.$inertia.post(route('logout'));
            }
        },
        watch: {
            group() {
                this.drawer = false
            }
        }
    }
</script>

<style scoped>
    .newButton {
        height: 200% !important;
        border-radius: 0 !important;
        position: relative !important;
        bottom: 15px !important;
    }
    .sideButton:hover:before {
        opacity: 0;
    }
    .navIconforDash{
        position: relative !important;
        left: 13px !important;
    }
</style>
