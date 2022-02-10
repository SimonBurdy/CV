<template>
    <div class="col-3">
        <img :src="'/storage/clients/'+client.logo" :alt="client.name" class="rounded" style="height: 100px ; max-width:250px">
    </div>
</template>
<script>


export default ({
    name:"Client",
    props:["clientRow"],
    async beforeMount() {
        this.client = this.clientRow; 
      
    },
   data() {
       return {
           client : {},
       }
   },
       methods : {
 
    },
})
</script>
<style scoped>
    @import "../../../css/app.css";
    @import '../../../css/timeline.css';
</style>