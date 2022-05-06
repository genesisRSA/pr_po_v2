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
    import Requestor from './PurchaseOrderByUser/RequestorPO'
    import Admin from './PurchaseOrderByUser/AdminPO'
    import Buyer from './PurchaseOrderByUser/BuyerPO'
    import Purchase from './PurchaseOrderByUser/ManagerPO'
    import President from './PurchaseOrderByUser/PresidentPO'
    import Ceo from './PurchaseOrderByUser/CeoPO'
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
        this.getUserPositionPO()
    },

    computed: {

    },

    watch: {

    },

    methods: {

         getUserPositionPO(){
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