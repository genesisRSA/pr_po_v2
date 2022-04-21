<template>
    <app-layout>
        <v-container class="my-15">
            <h2 class="text-center mb-3">Login</h2>
            <v-card
                class="d-flex justify-center align-center mx-auto pa-10"
                max-width="500"
            >
                <v-row>
                    <v-col class="text-center">
                        <span v-if="form.errors">
                            <v-list>
                                <v-list-item v-for="(items, i) in form.errors"
                                    :key="i">
                                <v-list-item-icon class="mx-auto">
                                    <v-icon color="red" left small>mdi-exclamation-thick</v-icon>
                                </v-list-item-icon>
                                    <v-list-item-title v-text="items" class="error-text-color"></v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </span>
                        <v-form ref="form" v-model="valid">

                            <v-text-field
                                v-model="form.email"
                                label="E-mail"
                                :rules="emailRules"
                            ></v-text-field>

                            <v-text-field
                                v-model="form.password"
                                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                                :rules="passwordRules"
                                :type="show ? 'text' : 'password'"
                                label="Password"
                                @click:append="show = !show"
                            ></v-text-field>
                            <h5 class="text-left mt-1">
                                <inertia-link :href="route('password.request')">Forgot Password?</inertia-link>
                            </h5>
                            <v-btn
                                color="blue"
                                class="mt-3"
                                type="submit"
                                :disabled="form.processing || !form.email || !form.password || !valid"
                                @click.prevent="login"
                            >
                                Login
                            </v-btn>
                        </v-form>
                    </v-col>
                </v-row>
            </v-card>
        </v-container>
    </app-layout>
</template>

<script>
    import AppLayout from '../../Layouts/AppLayout'
    export default {
        components: {AppLayout},
        data() {
            return {
                valid : true,
                form: this.$inertia.form({
                    email: '',
                    password: '',
                }),
                show: false,
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                passwordRules: [
                    v => !!v || 'Password is required',
                ],
            }
        },
        methods: {
            validate () {
                this.$refs.form.validate()
            },
            login() {
                this.form.post(this.route('login'), {
                    onFinish: () => this.form.reset('password', 'email')
                })
            }
        },
    }
</script>
<style>
    .error-text-color{
        color: #ff5252 !important;
    }
</style>
