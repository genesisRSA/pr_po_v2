<template>
    <app-layout>
        <v-container class="my-15">
        <h2 class="text-center mb-3">Reset Password Link</h2>
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
                                    :loading="isLoading"
                                    :disabled="isLoading"
                                >
                                </v-text-field>
                                <v-btn
                                    color="blue"
                                    class="mt-3"
                                    type="submit"
                                    :disabled="!valid || form.processing"
                                    @click.prevent="getEmail"
                                >
                                <v-icon color="blue lighten-3" left small>mdi-link-variant</v-icon>
                                    Generate Link
                                </v-btn>
                            </v-form>
                        </v-col>
                    </v-row>
            </v-card>

            <!-- status_alert -->
                <v-snackbar
                    v-model="snackbar"
                    color="success"
                    ref='snack'
                    top right
                    timeout="3000"
                    transition="slide-x-reverse-transition">
                    <v-icon>
                        mdi-checkbox-marked-circle
                    </v-icon>
                    <span class="success_text_pos">
                        {{ props_status }}
                    </span>
                    <template v-slot:action="{ attrs }">
                        <v-btn
                        color="white"
                        text
                        v-bind="attrs"
                        @click="snackbar = false"
                        >
                        <v-icon small>mdi-close</v-icon>
                        </v-btn>
                    </template>
                </v-snackbar>
        </v-container>
    </app-layout>
</template>
<script>
    import AppLayout from '../../Layouts/AppLayout'
    export default {
        props: {props_status : String},
        components: {AppLayout},
        data() {
            return {
                 snackbar : false,
                 valid : true,
                 isLoading : false,
                 form: this.$inertia.form({
                    email: '',
                 }),
                 emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
            }
        },
        created : function(){
        },
        computed: {

        },
        methods: {
            getEmail () {
                    this.isLoading=true;
                    this.form.post(this.route('password.email'), {
                        onFinish: () => {
                            this.$refs.form.reset(),
                            this.isLoading=false,

                            this.form.errors.email ? this.snackbar=false : this.snackbar=true
                     }
                })
            },
        }
    }
</script>
<style>

    .success_text_pos{
        position: relative;
        left: 10px;
    }

    .error-text-color{
        color: #ff5252 !important;
    }

</style>
