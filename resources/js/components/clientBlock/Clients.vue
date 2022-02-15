<template>
  <div class="container-fluid">
    <div class="col-12">
      <div class="row mb-3 text-center">
           <div class="col align-self-center">
                 <h4 >Déjà {{clients.length}} clients nous font confiance ... </h4>
            </div>
        
      </div>
      <div class="row justify-content-center">
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

.container-fluid {
    margin-top:50px;
}
</style>