<template>
<div class="container">
  <div class="timeline clearfix">
    <div class="vertical-line">
        <experience
        v-for="experience in experiences"
        :key="experience.id"
        :experienceRow="experience"

        ></experience>
    </div>
  </div>
</div>
</template>
<script>


export default({
    name:"Experiences",
    async beforeMount() {
        this.experiences = await this.load(); 
    },
    data() {
        return {
            experiences : []
        }
    },
    methods: {
        /**
         * Permet de charger toutes les expériences
         */
        load(){
            return this.$http.get("experiences");
        },

     
    },
})
</script>
<style scoped>
@import '../../../css/timeline.css';

</style>