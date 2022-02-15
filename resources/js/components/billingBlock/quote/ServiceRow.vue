<template>
    <div class="container-fluid mt-2 pb-2">

        <div class="card">
                <button class="btn btn-block text-left" type="button" data-toggle="collapse" :data-target="'#info-articles'+serviceRow.v_id" aria-expanded="true" :aria-controls="'info-articles'+serviceRow.v_id">
                    <div class="card-header" :id="'info-articles-title'+serviceRow.v_id">
                        <h2 class="mb-0">
                            <div class="row  ml-2justify-content-between">
                                <div class="col-md-3 ">
                                    <h5 class="font-weight-bold">Information de l'article</h5>
                                </div>

                                <div class="col-md-9 aling-self-center" v-if="serviceRow.article" >
                                        <div class="row">
                                            <h5> {{serviceRow.article.desc }}</h5>
                                            <h6 class="ml-3 mt-1"> {{serviceRow.article.desc }}</h6>
                                        </div>
                                </div>
                            </div>
                        </h2>
                    </div>
                </button>

                <div :id="'info-articles'+serviceRow.v_id" class="collapse" aria-labelledby="info-articles" :data-parent="'#info-articles'+serviceRow.v_id">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4 input-group-sm d-inline-flex align-self-start w-auto ">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Service</span>
                                </div>
                                <div class="form-control"> 
                                    <v-autocomplete
                                        hide-no-data
                                        hide-selected
                                        style="z-index: 9999; max-width: 50%;"
                                        :items="items"
                                        :min-len="2"
                                        item-text="desc"
                                        v-model="serviceRow.article"
                                        :get-label="getLabel"
                                        :component-item="template"
                                        @update-items="updateItems"
                                        :wait="500"
                                        :autoSelectOneItem="false"
                                        placeholder="Service"
                                        >

                                    </v-autocomplete>
                                    <p v-if="rowFormErrors  && rowFormErrors['rows.'+row_index+'.article_id']" class="row help-block text-error">
                                        {{ rowFormErrors['rows.'+row_index+'.article_id'][0] }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4 offset-md-4 input-group-sm d-inline-flex align-self-start w-auto">
                                <div class="input-group-prepend">
                                    <span class="input-group-text  w-auto" >TVA</span>
                                </div>
                                <select 
                                    v-model="serviceRow.vat_rate"
                                    class="form-control w-auto"
                                    >
                                        <option
                                            :value="index"
                                            :key="'addRow-'+index"
                                            v-for="(value,index) in vat_rates"
                                        >
                                        {{value }}
                                        </option>
                                </select>
                                <p v-if="rowFormErrors  && rowFormErrors['rows.'+row_index+'.vat_rate']" class="help-block text-error">
                                    {{ rowFormErrors['rows.'+row_index+'.vat_rate'][0] }}
                                </p>
                            </div>
                        </div>


                        <div class="row mt-4  align-items-center  w-auto">  
                            <div class="col-md-4">
                                <div class="row input-group-sm d-inline-flex align-self-start w-auto ml-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" >Quantité</span>
                                    </div>
                                    <input  v-model="serviceRow.quantity" type="number" class="form-control w-auto" min="0" placeholder="2">
                                </div>    
                                <div class="row">
                                    <p v-if="rowFormErrors  && rowFormErrors['rows.'+row_index+'.quantity']" class="row help-block text-error">
                                                    {{ rowFormErrors['rows.'+row_index+'.quantity'][0] }}
                                    </p>
                                 </div>
                            </div>

                            <div class="col-md-4 input-group-sm d-inline-flex align-self-start w-auto">
                               
                                    <div class="input-group-prepend">
                                        <span class="input-group-text w-auto" >Unité</span>
                                    </div>
                                    <select 
                                        v-model="serviceRow.unity"
                                        class="form-control w-auto"
                                        >
                                            <option
                                                :value="index"
                                                :key="'addRow-'+index"
                                                v-for="(value,index) in units"
                                            >{{value }}
                                            </option>
                                    </select>
                              
                                <p v-if="rowFormErrors  && rowFormErrors['rows.'+row_index+'.unity']" class="row help-block text-error">
                                    {{ rowFormErrors['rows.'+row_index+'.unity'][0] }}
                                </p>
                            </div>

                            <div class="col-md-4">
                                <div class="row input-group-sm  d-inline-flex align-self-start w-auto ml-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" >Prix unitaire</span>
                                    </div>
                                    <input  v-model="serviceRow.unit_price" type="number" min="0" class="form-control w-auto h-auto" placeholder="300">  
                                </div > 
                                    <p v-if="rowFormErrors  && rowFormErrors['rows.'+row_index+'.unit_price']" class="help-block text-error">
                                        {{ rowFormErrors['rows.'+row_index+'.unit_price'][0] }}
                                    </p>  
                             
                            </div>
                    
                        </div>


                        <div class="row mt-3  align-items-center w-auto">  

                            <div class="col input-group-sm d-inline-flex align-self-start w-auto">
                                <div class="input-group-prepend ">
                                    <span class="input-group-text" >Geste commerciale</span>
                                </div>
                                <input v-model="serviceRow.discount_value" type="number" min="0" class="form-control w-auto h-auto"  placeholder="150">
                            </div>
                            <div class="col input-group-sm d-inline-flex align-self-start w-auto">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" >Devise</span>
                                </div>
                                <select  v-model="serviceRow.discount_unit" class="form-control w-auto h-auto" >
                                    <option value="euros">€</option>
                                    <option value="pc">%</option>
                                </select>
                            </div>
                            <div class="col input-group-sm d-inline-flex align-self-start w-auto">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" >Valeur réelle</span>
                                </div>
                                <input  v-model="serviceRow.discount_euro" type="number" class="form-control w-auto h-auto " min="0" placeholder="200" readonly>
                                <div class="input-group-append">
                                        <span class="input-group-text">€</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8 input-group-sm d-inline-flex align-self-start w-auto ">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span >Service</span>
                                    </div>
                                    <div class="col-md-8"> 
                                        <v-autocomplete
                                            hide-no-data
                                            hide-selected
                                            style="z-index: 9999; max-width: 50%;"
                                            :items="items"
                                            :min-len="2"
                                            item-text="desc"
                                            v-model="serviceRow.article"
                                            :get-label="getLabel"
                                            :component-item="template"
                                            @update-items="updateItems"
                                            :wait="500"
                                            :autoSelectOneItem="false"
                                            placeholder="Service"
                                            >

                                        </v-autocomplete>
                                        <p v-if="rowFormErrors  && rowFormErrors['rows.'+row_index+'.article_id']" class="row help-block text-error">
                                            {{ rowFormErrors['rows.'+row_index+'.article_id'][0] }}
                                        </p>
                                    </div>

                                </div>
                               
                            </div>
                            <div class="col-md-4 offset-md-4 input-group-sm d-inline-flex align-self-start w-auto">
                               <div class="row">
                                    <div class="col-md-6">
                                        <span>TVA</span>
                                    </div>
                                    <select 
                                        v-model="serviceRow.vat_rate"
                                        class="col-md-6"
                                        >
                                            <option
                                                :value="index"
                                                :key="'addRow-'+index"
                                                v-for="(value,index) in vat_rates"
                                            >
                                            {{value }}
                                            </option>
                                    </select>
                               </div>
                               <div class="row">
                                    <p v-if="rowFormErrors  && rowFormErrors['rows.'+row_index+'.vat_rate']" class="help-block text-error">
                                        {{ rowFormErrors['rows.'+row_index+'.vat_rate'][0] }}
                                    </p>    
                               </div>

                            </div>
                        </div>


                        <div class="row mt-4  align-items-center  w-auto">  
                            <div class="col-md-4">
                                <div class="row input-group-sm d-inline-flex align-self-start w-auto ml-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" >Quantité</span>
                                    </div>
                                    <input  v-model="serviceRow.quantity" type="number" class="form-control w-auto" min="0" placeholder="200">
                                </div>    
                                <div class="row">
                                    <p v-if="rowFormErrors  && rowFormErrors['rows.'+row_index+'.quantity']" class="row help-block text-error">
                                                    {{ rowFormErrors['rows.'+row_index+'.quantity'][0] }}
                                    </p>
                                 </div>
                            </div>

                            <div class="col-md-4 input-group-sm d-inline-flex align-self-start w-auto">
                               
                                    <div class="input-group-prepend">
                                        <span class="input-group-text w-auto" >Unité</span>
                                    </div>
                                    <select 
                                        v-model="serviceRow.unity"
                                        class="form-control w-auto"
                                        >
                                            <option
                                                :value="index"
                                                :key="'addRow-'+index"
                                                v-for="(value,index) in units"
                                            >{{value }}
                                            </option>
                                    </select>
                              
                                <p v-if="rowFormErrors  && rowFormErrors['rows.'+row_index+'.unity']" class="row help-block text-error">
                                    {{ rowFormErrors['rows.'+row_index+'.unity'][0] }}
                                </p>
                            </div>

                            <div class="col-md-4">
                                <div class="row input-group-sm  d-inline-flex align-self-start w-auto ml-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" >Prix unitaire</span>
                                    </div>
                                    <input  v-model="serviceRow.unit_price" type="number" min="0" class="form-control w-auto h-auto" placeholder="3,5">  
                                    <div class="input-group-append">
                                            <span class="input-group-text">€</span>
                                    </div>   
                                </div > 
                                    <p v-if="rowFormErrors  && rowFormErrors['rows.'+row_index+'.unit_price']" class="help-block text-error">
                                        {{ rowFormErrors['rows.'+row_index+'.unit_price'][0] }}
                                    </p>  
                             
                            </div>
                    
                        </div>


                        <div class="row mt-3  align-items-center w-auto">  

                            <div class="col input-group-sm d-inline-flex align-self-start w-auto">
                                <div class="input-group-prepend ">
                                    <span class="input-group-text" >Geste commerciale</span>
                                </div>
                                <input v-model="serviceRow.discount_value" type="number" min="0" class="form-control w-auto h-auto"  placeholder="150">
                            </div>
                            <div class="col input-group-sm d-inline-flex align-self-start w-auto">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" >Devise</span>
                                </div>
                                <select  v-model="serviceRow.discount_unit" class="form-control w-auto h-auto" >
                                    <option value="euros">€</option>
                                    <option value="pc">%</option>
                                </select>
                            </div>
                            <div class="col input-group-sm d-inline-flex align-self-start w-auto">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" >Valeur réelle</span>
                                </div>
                                <input  v-model="serviceRow.discount_euro" type="number" class="form-control w-auto h-auto " min="0" placeholder="200" readonly>
                                <div class="input-group-append">
                                        <span class="input-group-text">€</span>
                                </div>
                            </div>

                        </div>
                    </div> -->
                     <div class="row mt-2 mb-2" >
                        <div class="col-md-3 offset-md-9" @click="generateDesc">
                            <button type="button" class="btn">
                            ➕ <small class="text-desc-icon pt-2">Ajouter le service</small>  
                            </button>  
                            
                        </div>
                    </div>
                </div>


        </div>


        <div class="card">
            <button class="btn btn-block text-left" type="button" data-toggle="collapse" :data-target="'#description'+serviceRow.v_id" aria-expanded="true" aria-controls="description">
                <div class="card-header" :id="'description-title'+serviceRow.v_id">
                    <h2 class="mb-0">
                        <div class="row ml-2">
                            <h5 class="font-weight-bold"> Description</h5>
                        </div>
                    </h2>
                </div>
            </button>

            <div :id="'description'+serviceRow.v_id" class="collapse show" aria-labelledby="description" :data-parent="'#description'+serviceRow.v_id">
                <div class="card-body">
                    <div class="row mt-3 ml-2" >  


                        <div class="input-group">
                            <textarea  v-model="serviceRow.description" class="form-control" rows="5"></textarea>
                        </div>
                        <p v-if="rowFormErrors  && rowFormErrors['rows.'+row_index+'.description']" class="help-block text-error">
                                {{ rowFormErrors['rows.'+row_index+'.description'][0] }}
                        </p>
                        
                        <div class="col mt-3 mb-2">
                            <div class="row">

                                <div class="col-md-3 offset-md-9" @click="eraseDesc">
                                    <button type="button" class="btn btn-outline-danger">
                                        🧼      <small class=" text-desc-icon  pt-2">Effacer la description</small>  
                                    </button>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row justify-content-end mt-5">
            <div class="col-md-4 align-items-center">
                <div class="row">
                    <div class="col-auto mt-2">
                            <h6> Total :</h6>
                    </div>
                    <div class="col input-group-sm  d-inline-flex align-self-start w-auto">
                        <input  v-model="serviceRow.sell_total" type="number" class="form-control " min="0"  placeholder="Total" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text">€</span>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>
<script>

import Autocomplete from "v-autocomplete";
import ItemTemplate from "./ItemTemplate.vue";
import CurrentAddress from "./CurrentAddress";

export default ({
    name:"ServiceRow",
    props: ['row_index' , 'v_id'],
    components: {
        Autocomplete,
        CurrentAddress
    },
    data() {
        return {
            template: ItemTemplate,
            mainAddress: {},
            items: [] ,
            markings: [],
            units: [],
            vat_rates: [],
            serviceRow : {
                service : {},
                article_id : '',
                quantity: '',
                unity: 'piece',
                discount_euro: 0,
                unit_price: 0,
                vat_rate: '20.OO',
                sell_total: '',
                description: '',
                agefiph: '',
            }
        }
    },
    async beforeMount(){
        this.serviceRow = this.row;
        this.markings = await this.$getConfig('markings');
        this.units =  await this.$getConfig('units');
        this.vat_rates = await this.$getConfig('tva');
    },
    methods: {
        /**
         * Update les items de recherche lors de la recherche d'un service
         */
        updateItems(text) {
            return this.$http
                .get("services", {
                    params: {
                        q: text
                    }
                })
                .then(response => {
                    this.items = response.data;
                
                });
        }, 
        /**
         * Affiche le bon label dans la bar de recherche
         */
        getLabel(item){
            if (item){  
                return item.desc;
            }
            return "";
        },
        /**
         * Génére la desription
         */
        generateDesc(){   
            if(this.serviceRow.article && (this.serviceRow.description == '' || this.serviceRow.description ==  undefined)){
            this.serviceRow.description =  this.serviceRow.article.ref + ' '+this.serviceRow.article.desc +'\n'
                        
              
            } 

        },
        /**
         * Effasse  la desc 
         */
        eraseDesc(){
            this.row.description = '';
        },

        /**
         * Recupere de l'enfant l'address principale 
         */
        addedOrUpdated(event){
          
           this.mainAddress = event.address;
        }   

    },
    computed : {
        row(){
            return this.$store.state.quote.rows[this.row_index];          
        },
        /**
         * Récupère les erruer de formulaire du store
        */
        rowFormErrors(){
            return this.$store.state.quote.quoteFormErrors;
        }
    },
    watch : {
      serviceRow: {
            deep:true,
            handler(newVal , oldVal){
                if(newVal){
                     this.$store.state.quote.rows[this.row_index] =  newVal;
                }
            }
        }
    }

})
</script>
<style scoped>
.text-desc-icon {
    cursor:pointer;
}

.card{
    border-radius: 4px;
    background: #fff;
    box-shadow: 0 6px 10px rgba(0,0,0,.08), 0 0 6px rgba(0,0,0,.05);
    transition: .3s transform cubic-bezier(.155,1.105,.295,1.12),.3s box-shadow,.3s -webkit-transform cubic-bezier(.155,1.105,.295,1.12);
    padding: 14px 80px 18px 36px;
    cursor: pointer;
}


.card h5{
  font-weight: 600;
}


</style>

