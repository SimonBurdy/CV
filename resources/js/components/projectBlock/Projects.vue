<template>
  <div class="container-fluid">
    <div class="col-12">
      <div class="row justify-content-center">
        <div class="col-3 text-center ">
            <h4 class=" allsides-3" >Projects</h4>
        </div>
      </div>
      <div class="row justify-content-center">
        <project
        v-for="project in projects"
          :key="project.id"
          :projectRow="project"
          >
        </project>
      </div>
    </div>
  </div>
</template>
<script>


export default ({
    name:"Projects",
     async beforeMount() {
        this.projects = await this.load(); 
    },
    data() {
        return {
            projects : []
        }
    },
    methods: {
        /**
         * Permet de charger toutes les expériences
         */
        load(){
            return this.$http.get("projects");
        },

     
    },
})
</script>

<style scoped>

@import "../../../css/app.css";



.allsides-3 {
  background:
    linear-gradient(currentColor 0 0) top,
    linear-gradient(currentColor 0 0) left,
    linear-gradient(currentColor 0 0) bottom,
    linear-gradient(currentColor 0 0) right;
  background-size: var(--d, 0) 3px, 3px var(--d, 0);
  background-repeat: no-repeat;
  transition: 0.5s;
}
.allsides-3:hover {
  --d: 100%;
}


.allsides-3 {
  display:inline-block;
  font-size:30px;
  font-family: OvercameRegular;
  margin-top:0%;
  cursor:pointer;
  padding: 0px 9px 9px 9px ;
}
.allsides-3  {
  color:black;
}
.container-fluid {
  height: 100%;
  width: 100%;
  display: flex;
  margin-bottom: 200px;
  
}

.card {
  top: 80px;
  display: flex;
  height: 560px;
  width: 400px;
  background-color: #17141d;
  border-radius: 10px;
  box-shadow: -1rem 0 3rem #000;
/*   margin-left: -50px; */
  transition: 0.4s ease-out;
  position: relative;
  margin-top: 10px;
}

.card:hover {
  /* transform: translate(20px,-80px); */
  transform: translateY(-80px);
  transition: 0.4s ease-out;
}

.card:hover ~ .card {
  position: relative;
  /* left: 100px; */
  transition: 0.4s ease-out;
}

.title {
  color: white;
  font-weight: 300;
  position: absolute;
  left: 20px;
  top: 15px;
}

.bar {
  position: absolute;
  top: 100px;
  left: 20px;
  height: 5px;
  width: 150px;
}

.emptybar {
  background-color: #2e3033;
  width: 100%;
  height: 100%;
}

.filledbar {
  position: absolute;
  top: 0px;
  z-index: 3;
  width: 0px;
  height: 100%;
  background: rgb(0,154,217);
  background: linear-gradient(90deg, rgba(0,154,217,1) 0%, rgba(217,147,0,1) 65%, rgba(255,186,0,1) 100%);
  transition: 0.6s ease-out;
}

.card:hover .filledbar {
  width: 120px;
  transition: 0.4s ease-out;
}

.circle {
  position: absolute;
  top: 150px;
  left: calc(50% - 60px);
}

.stroke {
  stroke: white;
  stroke-dasharray: 360;
  stroke-dashoffset: 360;
  transition: 0.6s ease-out;
}

svg {
  fill: #17141d;
  stroke-width: 2px;
}

.card:hover .stroke {
  stroke-dashoffset: 100;
  transition: 0.6s ease-out;
}


.content{
  padding: 150px 5px 0px 10px;
  color: white;
}


.thumbnail {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 85%;
}
.info {
  margin: 50px 0px 0px 0px ;
}
</style>