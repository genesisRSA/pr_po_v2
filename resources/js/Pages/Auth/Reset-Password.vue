<template>
    <app-layout>
        <v-container class="my-15">
            <h2 class="text-center mb-3">Reset Password</h2>
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
                                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                :rules="passwordRules"
                                :type="show1 ? 'text' : 'password'"
                                name="input-10-2"
                                label="Password"
                                @click:append="show1 = !show1"
                            ></v-text-field>

                            <v-text-field
                                v-model="form.password_confirmation"
                                :rules="passwordConfirmationRule"
                                :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="show2 ? 'text' : 'password'"
                                name="input-10-2"
                                label="Confirm Password"
                                @click:append="show2 = !show2"
                            ></v-text-field>

                            <v-btn
                                color="blue"
                                class="mt-5"
                                :disabled="!valid"
                                type="submit"
                                @click.prevent="reset_password"
                            >
                                Change Password
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
        props: {props_email : String, token : Text},
        data() {
            return {
                valid : true,
                form: this.$inertia.form({
                    token: this.token,
                    email: '',
                    password: '',
                    password_confirmation: ''
                }),
                show1: false,
                show2: false,
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                passwordRules: [
                    v => !!v || 'Password is required',
                    v => (v && v.length >= 8) || 'Password must be atleast 8 characters',
                ],
            }
        },
        computed: {
                passwordConfirmationRule() {
                return [
                    this.form.password === this.form.password_confirmation || "Password must be match",
                    v => !!v || 'You must confirm your password.'
                ]
            }
        },
        methods: {
                validate () {
                this.$refs.form.validate()
            },
            reset_password() {
                this.form.post(this.route('password.update'), {
                    onFinish: () => this.$refs.form.reset('password', 'email', 'password_confirmation')
                })
            }
        }

    }
</script>
<style>
    .error-text-color{
        color: #ff5252 !important;
    }
</style>
