<template>
   <app-layout>
        <div v-if="userType=='REQUESTOR'">
            <requestor></requestor>
        </div>
        <div v-if="userType=='ADMIN'">
            <admin></admin>
        </div>
        <div v-if="userType=='BUYER'">
            <buyer></buyer>
        </div>
        <div v-if="userType=='PURCHASE MNGR.'">
            <purchase></purchase>
        </div>
        <div v-if="userType=='PRESIDENT'">
            <president></president>
        </div>
        <div v-if="userType=='CEO'">
            <ceo></ceo>
        </div>
   </app-layout>
</template>

<script>
    import AppLayout from '../Layouts/AppLayout'
    import Requestor from './PurchaseRequestByUser/RequestorPR'
    import Admin from './PurchaseRequestByUser/AdminPR'
    import Buyer from './PurchaseRequestByUser/BuyerPR'
    import Purchase from './PurchaseRequestByUser/ManagerPR'
    import President from './PurchaseRequestByUser/PresidentPR'
    import Ceo from './PurchaseRequestByUser/CeoPR'
    export default {
        components: {
            AppLayout,
            Purchase,
            Requestor,
            Admin,
            Buyer,
            President,
            Ceo
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
