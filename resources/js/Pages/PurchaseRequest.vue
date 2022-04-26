<template>
   <app-layout>
        <div v-if="userType=='REQUESTOR'">
            <requestor></requestor>
        </div>
        <div v-if="userType=='ADMIN'">
            <admin></admin>
        </div>
   </app-layout>
</template>

<script>
    import AppLayout from '../Layouts/AppLayout'
    import Requestor from './PurchaseRequestByUser/RequestorPR'
    import Admin from './PurchaseRequestByUser/AdminPR'
    export default {
        components: {
            AppLayout,
            Requestor,
            Admin
        },
            data: () => ({
                userType : null
    }),

    created: function(){
        this.getUserPositionPR()
    },

    computed: {

    },

    watch: {

    },

    methods: {

        getUserPositionPR(){
            axios.get('/getUserPositionPR')
              .then(response =>{
                  this.userType = response.data
              })
              .catch(error =>{
                    console.log(error.response);
              })
              .finally(() => {

          });
        }

        },
    }
</script>
