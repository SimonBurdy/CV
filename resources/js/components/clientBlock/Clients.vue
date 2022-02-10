<template>
  <div class="container">
    <div class="row">
      <div class="mb-3">
         <h4 class="text-center" >Déjà {{clients.length}} clients nous font confiance ... </h4>
      </div>
      <div class="row justify-content-between">
        <client
        v-for="client in clients"
            :key="client.id"
            :clientRow="client"
            >

        </client>
        </div>
    </div>
  </div>
</template>
<script>


export default ({
    name:"Clients",
     async beforeMount() {
        this.clients = await this.load(); 
        console.log(" this.clients " ,  this.clients )
    },
    data() {
        return {
            clients : []
        }
    },
    methods: {
        /**
         * Permet de charger toutes les expériences
         */
        load(){
            return this.$http.get("clients");
        },

     
    },
})
</script>

<style scoped>


</style>