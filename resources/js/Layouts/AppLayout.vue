<template>
    <v-app id="inspire" dark app>
        <!-- <Nav/> -->
        <div v-if="!route().current('welcome') && !route().current('login') && !route().current('register') && !route().current('password.request') && !route().current('password.reset')">
        <Sidebar :drawer='drawer' />
        </div>
        <div v-if="!route().current('welcome') && !route().current('login') && !route().current('register') && !route().current('password.request') && !route().current('password.reset')">
            <Header @handleDrawer="drawer = !drawer"/>
        </div>
            <v-main :class="route().current('welcome') || route().current('login') || route().current('register') || route().current('password.request') || route().current('password.reset') ? 'background-color : yellow' :  'background-color : primary'">
                <v-container class="py-8 px-6" fluid>
                        <v-row>
                            <v-col>
                                <slot></slot>
                            </v-col>
                        </v-row>
                </v-container>
            </v-main>
    </v-app>
</template>

<script>
    import Nav from '../components/Nav'
    import Sidebar from '../components/Sidebar'
    import Header from '../components/Header'
    export default {
        components: {Sidebar,Header},
        data: ()=> ({
            drawer: true,
        }),
    }
</script>

<style>
    html { overflow-y: auto; }
</style>
