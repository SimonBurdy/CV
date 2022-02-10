<template>
    <div class="card">
        <h3 class="title">{{project.title}} chez {{project.client.name}}</h3>
        <div class="bar">
            <div class="emptybar">
            </div>
            <div class="filledbar">

            </div>
        </div>
        <div class="row content">
            <div class="row thumbnail align-center">
            <img  class="img-thumbnail" src="http://res.cloudinary.com/do5ht5y0y/image/upload/v1501322753/post-img-2_zpse1ce0jta_sktijn.jpg" alt="">
            </div>
            <div class="row info">
                <p>{{project.description}} 
                </p>
            </div>
        </div>
        <!-- <div class="circle">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg">
            <circle class="stroke" cx="60" cy="60" r="50"/>
            </svg>
        </div> -->
        
    </div>
    <!-- <div class="card">
          <h3 class="title">Projet Application</h3>
          <div class="bar">
              <div class="emptybar">

              </div>
              <div class="filledbar">

              </div>
          </div>
          <div class="row content">
              <div class="row thumbnail align-center">
                <img  class="img-thumbnail" src="http://res.cloudinary.com/do5ht5y0y/image/upload/v1501322753/post-img-2_zpse1ce0jta_sktijn.jpg" alt="">
              </div>
              <div class="row info">
                  <p>Rogatus ad ultimum admissusque in consistorium ambage nulla praegressa inconsiderate et leviter
                proficiscere inquit ut praeceptum est, Caesar sciens quod si cessaveris, et tuas et palatii tui
                </p>
              </div>
          </div>
         
      </div>
      <div class="card">
          <h3 class="title">Projet Api</h3>
          <div class="bar">
              <div class="emptybar">

              </div>
              <div class="filledbar">

              </div>
          </div>
          <div class="row content">
              <div class="row thumbnail align-center">
                <img  class="img-thumbnail" src="http://res.cloudinary.com/do5ht5y0y/image/upload/v1501322753/post-img-2_zpse1ce0jta_sktijn.jpg" alt="">
              </div>
              <div class="row info">
                  <p>Rogatus ad ultimum admissusque in consistorium ambage nulla praegressa inconsiderate et leviter
                proficiscere inquit ut praeceptum est, Caesar sciens quod si cessaveris, et tuas et palatii tui
                </p>
              </div>
          </div>
        
      </div>
      <div class="card">
          <h3 class="title">Projet Platform</h3>
          <div class="bar">
              <div class="emptybar">

              </div>
              <div class="filledbar">

              </div>
          </div>
          <div class="row content">
            <div class="row thumbnail align-center">
                <img  class="img-thumbnail" src="http://res.cloudinary.com/do5ht5y0y/image/upload/v1501322753/post-img-2_zpse1ce0jta_sktijn.jpg" alt="">
            </div>
            <div class="row info">
                 <p>Rogatus ad ultimum admissusque in consistorium ambage nulla praegressa inconsiderate et leviter
               proficiscere inquit ut praeceptum est, Caesar sciens quod si cessaveris, et tuas et palatii tui
               </p>
            </div>
         
          </div>
           <div class="circle">
              <svg version="1.1" xmlns="http://www.w3.org/2000/svg">
              <circle class="stroke" cx="60" cy="60" r="50"/>
              </svg>
          </div> -->
</template>
<script>


export default ({
    name:"Project",
    props:["projectRow"],
    async beforeMount() {
        this.project =  this.projectRow; 
    },
    data() {
        return {
            project:{},
        }
    },
})
</script>

<style scoped>

@import "../../../css/app.css";
.container {
  height: 100%;
  width: 100%;
  display: flex;
  margin-bottom: 100px;
  
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
  left: 100px;
  margin-top: 10px;
}

.card:not(:first-child) {
    margin-left: -50px;
}

.card:hover {
  /* transform: translate(20px,-80px); */
  transform: translateY(-80px);
  transition: 0.4s ease-out;
}

.card:hover ~ .card {
  position: relative;
  left: 100px;
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