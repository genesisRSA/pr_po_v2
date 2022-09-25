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
        <div v-if="userType=='RTI APPROVER'">
            <rti-approver></rti-approver>
        </div>
        <div v-if="userType=='RSA APPROVER'">
            <rsa-approver></rsa-approver>
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
    import RtiApprover from './PurchaseOrderByUser/RTI_ApproverPO'
    import RsaApprover from './PurchaseOrderByUser/RSA_ApproverPO'
    export default {
        components: {
            AppLayout,
            Purchase,
            Requestor,
            Admin,
            Buyer,
            RtiApprover,
            RsaApprover,
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
