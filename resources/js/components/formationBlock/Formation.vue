<template>
    <div id="post-1" class="vesti-col timeline-post">
        <div class="vesti-content-wrapper">
            <div class="photo">
            <img :src="'/storage/formations/'+formation.logo">
                <div class="vesti-date-wrapper">
                    <div class="vesti-date">
                        <span class="year text-wrap" >{{ formation.year}}</span>
                        <br>
                    </div>
                </div>
            </div>
            <div class="vesti-desc">
                <a class="desc-a" href="#">
                    <h4>{{formation.location}} {{formation.name}} 

                    </h4>
                </a>
                <p>{{formation.notes}} </p>
            </div>
        </div>

            <!-- <div id="post-2" class="vesti-col timeline-post">
                <div class="vesti-content-wrapper">
                    <div class="photo">
                        <img src="http://res.cloudinary.com/do5ht5y0y/image/upload/v1501322753/post-img-2_zpse1ce0jta_sktijn.jpg">
                        <div class="vesti-date-wrapper">
                            <div class="vesti-date">
                                <span class="year" >2017</span>
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="vesti-desc">
                        <a class="desc-a" href="#">
                            <h4>Faculte Catholique de Medecine de Lille PACES </h4>
                        </a>
                         <p>Echec en Première année de Médecine </p>
                    </div>
                </div>
            </div>
            <div id="post-3" class="vesti-col timeline-post">
                <div class="vesti-content-wrapper">
                    <div class="photo">
                        <img src="http://res.cloudinary.com/do5ht5y0y/image/upload/v1501322753/post-img-2_zpse1ce0jta_sktijn.jpg">
                        <div class="vesti-date-wrapper">
                            <div class="vesti-date">
                                <span class="year" >2018-2021</span>
                              
                            </div>
                        </div>
                    </div>
                    <div class="vesti-desc">
                        <a class="desc-a" href="#">
                            <h4>FGES Lille Licence Science Du Numerique </h4>
                        </a>
                        <p>Obtention de la Licence Science du Numérique ( SDN ) , 
                        la SDN est une Licence Généraliste majoritairement basée sur la découverte
                        et l'apprentissage de nombreux languages de programation , la connaissance UX et l'algorythmie </p>
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
                            </div>
                        </div>
                    </div>
                    <div class="vesti-desc">
                        <a class="desc-a" href="#">
                            <h4>FGES Lille primiere annee Master Cyber</h4>
                        </a>
                        <p>Début d'alternance en tant que déveloper Fullstack chez Weezea</p>
                    </div>
                </div>
            </div> -->
    </div>
</template>
<script>


export default ({
    name:"Formation",
    props:["formationRow"],
    async beforeMount() {
        this.formation = this.formationRow; 
        this.formation.year  = this.processDates().year;
        this.formation.month = this.processDates().month;
      
    },
   data() {
       return {
           formation : {},
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

            const  yearFrom = year(this.formation.from);
            const yearTo = year(this.formation.to);
           
            const monthFrom = month(this.formation.from);
            const monthTo = month(this.formation.to)
       
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
})
</script>
<style scoped>
    @import "../../../css/app.css";
    @import '../../../css/timeline.css';
</style>