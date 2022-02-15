

<template>
  <nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
        <div class="col-3 align-self-start nav-item">
            <div class="morphing"  data-text="Burdy Simon" style="margin-left: 18% ;  "  href="#"> Burdy Simon</div>
            <!-- <div class="morphing  col"  data-text="Burdy Simon" style="margin-left: 18%"  href="#"> <p  style=" font-family: Overcame;">Burdy Simon</p> </div> -->
        </div>
    </div>
  </nav>
</template>
<script>

export default ({
    name: "Navbar",
     data() {
         return {

         }
     }
})
</script>

<style scoped lang="scss">

@font-face { font-family: OvercameBold; src: url('../../../public/font/Overcame/OvercameDemoBold.ttf'); } 


.transformed {
  font-family: OvercameBold;
  padding: 10px;
  clip-path: inset(-200% -50% 0);
  color: #0000;
}

.transformed:before,
.transformed:after {
  font-family: OvercameBold;
  content: attr(data-text);
  position: absolute;
  padding: inherit;
  color: #000;
  inset: 0;
  transition:.4s;
  transform-origin: bottom;
}
.transformed:after {
  transform: rotate(180deg);
  background: #000;
  color: #fff;
  opacity: 0;
}
.transformed:hover:before{
  transform: rotate(-180deg);
}
.transformed:hover:after{
  transform: rotate(0);
  opacity: 1;
}



.transformed {
  display:inline-block;
  font-size:22px;
  font-family:sans-serif;
  margin:25px;
  cursor:pointer;
  position: relative;
}
.transformed:before,
.transformed:after {
  pointer-events: none;
}


.morphing {
  padding-bottom: 15px;
  font-family: OvercameBold;
  display:inline-block;
  font-size:25px;
  font-family:sans-serif;
  // margin:25px;
  cursor:pointer;
  position: relative;
}
.morphing:before,
.morphing:after {
  pointer-events: none;
}



.morphing {
  //  padding:8px;
  padding-bottom: 10px;
  font-family: OvercameBold;
   box-shadow:0 0 0px 80px var(--c,#0000);
   background:var(--c,#0000);
  transition: 0.3s,box-shadow 0.3s 0.3s,background 0.3s 0.3s,color 0.3s 0.3s;
   clip-path: polygon(0 0,10px 0,10px 0,100% 0,100% 50%,100% 100%,10px 100%,10px 100%,0 100%,0 calc(50% + 10px),0 calc(50% + 10px),0 calc(50% + 10px),0 calc(50% - 10px),0 calc(50% - 10px),0 calc(50% - 10px));
 }
 .morphing:hover {
  --c:#000;
  color:#fff;
  transition: 0.3s,clip-path 0.4s 0.3s; 
   clip-path: polygon(0 -20px,20px -30px,20px 0,calc(100% - 10px) 0,calc(100% + 20px) 50%,calc(100% - 10px) 100%,20px 100%,20px calc(100% + 30px),0 calc(100% + 20px),0 calc(50% + 10px),-35px calc(50% + 10px),-40px calc(50% + 15px),-40px calc(50% - 15px),-35px calc(50% - 10px),0 calc(50% - 10px))
 }



body {
  text-align:center;
}





</style>