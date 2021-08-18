<template>
    <footer class="footer">
        <div class="container">
            <div class="row" >
                <div  class="col text-center">
                    <div class="allsides-1">Text numero  </div>
                </div>
                <div  class="col text-center">  
                        <div class="allsides-2">Me contacter</div>
                </div > 
                <div class="col text-center">
                    
                    <div class="allsides-3">Copyright BurdySimon</div> 
                </div>
                <div  class="col text-center">  
                        <div class="allsides-4">Mes reseaux</div>
                </div > 
            </div>
        </div>
    </footer>
</template>
<script>

export default({
    name:"CustomFooter",
    data() {
        return {
            
        }
    }
})
</script>
<style scoped>

@font-face { font-family: OvercameRegular; src: url('../../../public/font/Overcame/OvercameDemoRegular.ttf'); } 

.footer {
 
  
  bottom: 0;
  width: 100%;
  height: 100px;
  background-color: black;
}

.allsides-1 {
  
  background:
    linear-gradient(currentColor 0 0) 100% 0,
    linear-gradient(currentColor 0 0) 0 0,
    linear-gradient(currentColor 0 0) 0 100%,
    linear-gradient(currentColor 0 0) 100% 100%;
  background-size: var(--d, 0) 3px, 3px var(--d, 0);
  background-repeat: no-repeat;
  transition: 0.5s;
}
.allsides-1:hover {
  --d: 100%;
}

.allsides-2 {
  background:
    linear-gradient(currentColor 0 0) 0 0,
    linear-gradient(currentColor 0 0) 0 0,
    linear-gradient(currentColor 0 0) 100% 100%,
    linear-gradient(currentColor 0 0) 100% 100%;
  background-size: var(--d, 0) 3px, 3px var(--d, 0);
  background-repeat: no-repeat;
  transition: 0.5s;
}
.allsides-2:hover {
  --d: 100%;
}

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

.allsides-4 {
  background:
    linear-gradient(currentColor 0 0) var(--p, 100%) 0,
    linear-gradient(currentColor 0 0) 0 var(--d, 0),
    linear-gradient(currentColor 0 0) var(--d, 0) 100%,
    linear-gradient(currentColor 0 0) 100% var(--p, 100%);
  background-size: var(--d, 0) 3px, 3px var(--d, 0);
  background-repeat: no-repeat;
  transition: 0.5s, background-position 0s 0.5s;
}
.allsides-4:hover {
  --d: 100%;
  --p: 0%
}



.allsides-1 , .allsides-2 , .allsides-3 , .allsides-4{
  display:inline-block;
  font-size:15px;
  font-family: OvercameRegular;
  margin-top:0%;
  cursor:pointer;
  padding: 0px 9px 9px 9px ;
}
.allsides-1 , .allsides-2 , .allsides-3 , .allsides-4:nth-child(odd) {
  color:white;
}
body {
  text-align:center;
}
</style>