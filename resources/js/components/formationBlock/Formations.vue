<template>
<div class="container">
    <div class="timeline clearfix">
        <div class="vertical-line">
            <formation
                v-for="formation in formations"
                :key="formation.id"
                :formationRow="formation"
            ></formation>
        </div>
    </div>
</div>
</template>
<script>


export default ({
    name:"Formations",
    async beforeMount() {
        this.formations = await this.load(); 
    },
   data() {
       return {
           formations: [],
       }
   },
   methods: {
        /**
         * Permet de charger toutes les expériences
         */
        load(){
            return this.$http.get("formations");
        },

     
    },
})
</script>
<style scoped>
    @import "../../../css/app.css";
    @import '../../../css/timeline.css';
</style>