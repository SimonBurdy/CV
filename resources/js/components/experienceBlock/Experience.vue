<template>
    <div id="post-1" class="vesti-col timeline-post">
        <div class="vesti-content-wrapper">
            <div class="photo">
            <img :src="'/storage/experiences/'+experience.logo">
                <div class="vesti-date-wrapper">
                    <div class="vesti-date">
                        <span class="year text-wrap" >{{ experience.year}}</span>
                        <br>
                        <span class="month text-wrap" >{{ experience.month}}
                        </span>
                    </div>
                </div>
            </div>
            <div class="vesti-desc">
                <a class="desc-a" >
                   <h4>{{experience.name}} chez {{experience.location}}</h4>
                </a>
                <p>{{experience.notes}}</p>
            </div>
        </div>
    </div>
    <!-- <div id="post-2" class="vesti-col timeline-post">
            <div class="vesti-content-wrapper">
                <div class="photo">
                    <img src="http://res.cloudinary.com/do5ht5y0y/image/upload/v1501322753/post-img-2_zpse1ce0jta_sktijn.jpg">
                    <div class="vesti-date-wrapper">
                        <div class="vesti-date">
                            <span class="year" >2019</span>
                            <br>
                            <span class="month" >Juin/Juillet</span>
                        </div>
                    </div>
                </div>
                <div class="vesti-desc">
                    <a class="desc-a" href="#">
                        <h4>CDD chez Sante BTP </h4>
                    </a>
                    <p>CDD de 1 mois : Création du registre des données en rapport avec le RGPD</p>
                </div>
            </div>
        </div>
        <div id="post-3" class="vesti-col timeline-post">
            <div class="vesti-content-wrapper">
                <div class="photo">
                    <img src="http://res.cloudinary.com/do5ht5y0y/image/upload/v1501322753/post-img-2_zpse1ce0jta_sktijn.jpg">
                    <div class="vesti-date-wrapper">
                        <div class="vesti-date">
                            <span class="year"  >2021</span>
                            <br>
                            <span class="month"  >Juin/AoûT</span>
                        </div>
                    </div>
                </div>
                <div class="vesti-desc">
                    <a class="desc-a" href="#">
                        <h4>Stage Devellopement Weezea </h4>
                    </a>
                    <p>Stage de 3 mois : Participation au dévellopement Back et Front d'un platform de Goodies ainsi qu'a l'amélioration de la platform Back interne  de Weezea  </p>
                </div>
            </div>
        </div>
        <div id="post-4" class="vesti-col timeline-post">
            <div class="vesti-content-wrapper">
                <div class="photo">
                <img src="http://res.cloudinary.com/do5ht5y0y/image/upload/v1501322753/post-img-2_zpse1ce0jta_sktijn.jpg">
                    <div class="vesti-date-wrapper">
                        <div class="vesti-date">
                            <span class="year">2021</span>
                            <br>
                            <span class="month">Septembre</span>
                        </div>
                    </div>
                </div>
                <div class="vesti-desc">
                    <a class="desc-a" href="#">
                        <h4>Alternance chez Weezea </h4>
                    </a>
                    <p>Début d'alternance en tant que déveloper Fullstack chez Weezea</p>
                </div>
            </div>
        </div> -->
</template>
<script>


export default({
    name:"Experience",
    props:["experienceRow"],
    async beforeMount() {
        this.experience =  this.experienceRow; 
       this.experience.year  = this.processDates().year;
       this.experience.month = this.processDates().month;
    },
    data() {
        return {
            experience:{},
        }
    },
    methods : {
           /**
         * Compute les date
         */
          processDates(){
            const year =  date => date.slice(0,4);
            const month = date => date.slice(5,7);

            const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Decembre"
            ];

            const  yearFrom = year(this.experience.from);
            const yearTo = year(this.experience.to);
           
            const monthFrom = month(this.experience.from);
            const monthTo = month(this.experience.to)
      
            let data = { };

            if(yearFrom === yearTo){
                data.year = yearFrom;
            }else{
                data.year =  yearFrom +" / "+ yearTo;
            }

            if(monthFrom === monthTo){
                data.month =  monthNames[monthFrom < 1 ? 11 : monthFrom -1];
            }else{
                data.month = monthNames[monthFrom < 1 ? 11 : monthFrom -1] + " / " + monthNames[monthTo > 1 ? 11 : monthTo -1];
            }

          
            return data;
           
        }
    },
    computed : {
        
     
      

    }
})
</script>
<style scoped>
@import '../../../css/timeline.css';

</style>