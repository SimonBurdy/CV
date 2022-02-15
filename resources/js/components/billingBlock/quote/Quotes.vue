<template>
    <div class="container-fluid" >
        <div>
            <current-quote
                :project_id="project_id"
                :mode="mode"
                :quote_id="quoteId"
                :client="client"
                @added="onAdded"
                @isAllowed="isMemberAllowed"
                :role="role"
                @switchMode="switchMode"
            >
            </current-quote> 
        </div>
        <div v-if="mode == 'list'">

            <div class="py-2 d-flex justify-content-end">
              
                <button
                    type="button"
                    @click="changeMode('creation')"
                    class="btn btn-secondary btn-sm"
                ><i class="la la-plus"></i> Ajouter
                </button>
            </div>

            <table
                v-if="quotes"
                class=" bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs"
                cellspacing="0"
            >
                <thead>
                <tr style="font-size: 16px">
                    <th>
                        N°
                    </th>
                    <th>
                        Statut
                    </th>
                    <th>
                        Date de création
                    </th>
                    <th>
                        Date d'échéance
                    </th>
                    <th>
                        Montant
                    </th>
                    <th class="text-center">
                        Actions
                    </th>
                </tr>
                </thead>
               <tbody>
                    
                <tr
                    :key="quote.id"
                    v-for="(quote,index) in quotes"
                >

                    <td>
                        <div class="row">
                            <div class="col-6">
                                <strong class="text-danger " v-if="quote.comments && quote.comments.length"
                                    >({{ quote.comments.length }})</strong
                                >
                                <button
                                    v-if="quote.id"
                                    class="variant-btn mr-1"
                                    @click="showCommentsModal(quote.id)"
                                    type="button"
                                >
                                    💬
                                </button>
                                <modal
                                    width="70%"
                                    :height="'auto'"
                                    :scrollable="true"
                                    :name="'comments_modal_' + quote.id"
                                >
                                    <div class="comments-modal-container">
                                        <comments
                                            @updated="quote.comments = $event.comments"
                                            :model-class=" isQuote ? 'Quote' : 'Invoice'"
                                            :model-id="quote.id"
                                        ></comments>
                                    </div>
                                </modal>
                            </div>
                            <div class="col-6">
                                <span  v-if="quote.number">
                                
                                    {{ quote.number }}
                                </span>
                                <span  v-else>
                                
                                    Aucun numéro
                                </span>
                            </div>
                        </div>
                    </td>               
                    <td class="align-middle">
                        <select 
                        @change="update(quote)"
                        v-model="quote.status"
                        class="form-control form-control-sm"
                        :disabled="isInvoice"
                        >
                            <option
                                :value="index"
                                :key="'editquote-'+index"
                                v-for="(value,index) in isQuote ? options_status.quote : options_status.invoice"
                            >{{ value  }}
                            </option>
                        </select>
                    
                        <p v-if="formErrors.status" class="help-block text-error">
                                                {{ formErrors.status[0] }}</p>
                    </td>
                    <td>
                        <p> {{ formatDate(quote.creation_date)}}</p>
                    </td>
                    <td>
                        <p> {{ formatDate(quote.validity_date)}}</p>
                    </td>
                        
                    <td>
                        <p> {{numberFormat(quote.sell_total)}} € </p> 
                    </td>
                    <td class="row">
                            <div class="mx-auto form-inline">
                                <button
                                    @click="updateMode(quote)"
                                    type="button"
                                    class="btn btn-sm btn-info"
                                ><i class="la la-edit"></i>
                                    <span v-if="isInvoice && quote.status == 'draft' || isQuote">Editer</span>
                                    <span v-else>Voir</span>
                                </button>


                                <button
                                    v-if="isQuote"
                                    @click="convertToInvoice(index)"

                                   
                                    class="btn btn-sm btn-warning"
                                >
                                    <i class="las la-exchange-alt"></i>
                                    <span>Convertir</span>
                                </button> 

                                <button
                                    @click="generatePdf(index)"

                                    type="button"
                                    class="btn btn-sm btn-primary"
                                >
                                    <i class="las la-file-pdf"></i>
                                    <span>PDF</span>
                                </button>  
        
    
        
                                <button
                                    @click="del(index)"
                                    v-if="isInvoice && quote.status == 'draft' || isQuote"
                                    type="button"
                                    class="btn btn-sm btn-danger mt"
                                >
                                    <i class="la la-trash"></i>
                                </button> 
                        </div>
                    </td>

                </tr>


                </tbody>
                <tfoot>
                <tr>
                    <th>
                        N°
                    </th>
                    <th>
                        Statut
                    </th>

                    <th>
                        Date de création
                    </th>
                    <th>
                        Date d'échéance 
                    </th>
                    <th>
                        Montant
                    </th>
                    <th class="text-center">
                        Actions
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script>

import  CurrentQuote from "./CurrentQuote"
import Comments from "../../Comments";

export default {
    components: { CurrentQuote , Comments},
    name: "Quotes",
    async beforeMount() {
        this.options_status = await this.$getConfig("options_status");  
        this.quotes = await this.load(); 
    },
    data() {
        return {
            quotes : [],
            mode : 'list',
            formErrors: {},
            loading: false,
            quoteId: '',
            options_status: {
                'quote' :'',
                'invoice' :''
            },
            nbComments: "",
         
           
       
        };
    },
    props: [ "project_id" , "client"  , "role" , 'title'],
    mounted(){
        console.log( "this.$store" , this.$store);
        this.$store.state.quote.client = this.client;
        this.nbComments = 0;
    },
    methods: {

        /**
         * Permet de changer de mode vers Parent => l'enfant  
         */
        changeMode(mode){
                return this.mode = mode;
        },

           /**
         * Permet de changer de mode vers L''enfant   => Parent
         */
        switchMode(event){
            this.changeMode(event.mode);
        },

        /**
         * Permet de charger touts les devis du projet
         */
        load(){
            if(this.isQuote){
                return this.$http.get("quotes/"+this.project_id);
            }else{
                return this.$http.get("invoices/"+this.project_id);
            }
            
            
        },

        pluck(array, key) {
            return array.map(o => o[key]);
        },

        /**
         * Permet d'ajouter le nouveau devis de l'enfant vers le parent 
         */
        onAdded(event) {  
            let quote = event.quote;

            quote.status = "draft";
 

            if(this.isInvoice){
                 quote.number = null;
            }

            if(quote.rows.length){
                quote.sell_total = this.pluck(quote.rows , "sell_total").reduce(
                (accumulateur, valeurCourante) => accumulateur + valeurCourante);
            }

            this.quotes.push({...quote}); 

        },

        /**
         * Passe en mode update et transmet  l'id 
         */
        updateMode(quote){
            this.quoteId =  quote.id;
            this.changeMode('edit');

        },

        /**
         * Permet d 'updater un devis
         */
        update(quote){
            var url = ""

            if(this.isQuote){
                url = "quotes/"+quote.id
            }else if(this.isInvoice){
                url = "invoices/"+quote.id
            }
            
            this.$http.put( url, {
                project_id : this.project_id ,
                creation_date: quote.creation_date ,
                validity_date: quote.validity_date,
                address_id: quote.address_id,
                number: quote.number,
                sell_total: quote.sell_total,
                status: quote.status
         
            }).then((resp) => {
                if(this.isInvoice){
                      quote.number = resp.number;
                }
              
                new Noty({
                    type: "success",
                    text: "Devis edité",
                }).show();
            }).catch((err) => {
                new Noty({
                    type: "error",
                    text: "Erreur",
                }).show();
            })
        },

        /**
         * Permet de supprimmer un devis
         */
        del(index) {
            if (confirm("Supprimer ce devis ?")){

                var url = ""

                if(this.isQuote){
                    url = "quotes/"+this.quotes[index].id
                }else if(this.isInvoice){
                    url = "invoices/"+this.quotes[index].id
                }

                this.$http.delete(url).then(() => {
                    this.quotes.splice(index, 1);
                    new Noty({
                        type: "success",
                        text: "Devis supprimé",
                    }).show();
                }).catch((err) => {
                    new Noty({
                        type: "error",
                        text: "Erreur lors de la suppression du devis",
                    }).show();
                });
            }
        },  

         /**
         * Génération d'un devis à partir de la propal
        */  
        generatePdf(index){
            
                let win = window.open('', '_blank');

                var url = ""

                if(this.isQuote){
                    url = '/admin/api/quotes/'+this.quotes[index].id+'/printPdf'
                }else if(this.isInvoice){
                    url = '/admin/api/invoices/'+this.quotes[index].id+'/printPdf'
                }

                win.location.href = url;
            
        },



        /**
        * 
         */

        convertToInvoice(index){
            let  url = "quotes/"+this.quotes[index].id+"/convert-to-invoice"
              this.$http.get(url).then(() => {
                    new Noty({
                        type: "success",
                        text: "Devis convertie en facture avec succée",
                    }).show();
                }).catch((err) => {
                    new Noty({
                        type: "error",
                        text: "Erreur lors de la convertion du devis",
                    }).show();
                });
        },



        /**
         * Format les date au format européen
         */
        formatDate(oldDate){
            let newDate = oldDate.split('-');
            return newDate[2] +'-' + newDate[1]+'-' + newDate[0];
        },

        /**
         * Formate les nomber pour séparer les milliers avec un point
         */
        numberFormat(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },


        /**
         * 
         */

        isMemberAllowed(event){
            let quote =  this.quotes.find(quote => quote.id === event.quote_id);
            quote.status = event.status ;

            quote.number = event.number;
           
        },

        showCommentsModal(quoteId) {
            this.$modal.show("comments_modal_" + quoteId);
        },
        hideModalComments() {
            this.$modal.hide("comments_modal");
        },

  


       
    },
    computed : {
        isQuote(){
            return this.role  == "quote" ?  true :  false
        },
        isInvoice(){
             return this.role  == "invoice" ?  true :  false
        },


    },
    watch: {
        mode(newVal , oldVal){
            if(newVal == "creation"){
                this.$store.state.quote.id = "";
                this.$store.state.quote.address_id = "" ;
                this.$store.state.quote.created_at="" ;
                this.$store.state.quote.discount_euro="" ;
                this.$store.state.quote.discount_unit="";
                this.$store.state.quote.notes="" ;
                this.$store.state.quote.number="" ;
                this.$store.state.quote.project_id="" ;
                this.$store.state.quote.sell_total="" ;
                this.$store.state.quote.rows=[] ;
                this.$store.state.quote.status="draft" ;
 
            }
        }
    }
    
};
</script>

<style>



.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}




.img-thumbnail {
    max-height: 250px;
    max-width: 250px;
    border:none !important;
    background-color:lightgray;
   
    
}

.form-inline > * {
    margin-right: 10px;
}



</style>

<style lang="scss">
.quote-product-row {
    .v-autocomplete-input {
        width: 100% !important;
        border: 0;
    }
}
</style>
