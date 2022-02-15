<template>
    <div class="container-fluid" v-if="mode != 'list' "  ref="currentQuote">
        <h2 v-if="isQuote">Nouveau devis</h2>
         <h2 v-if="isInvoice">Nouvelle facture</h2>

        <div class="row mb-5 justify-content-between mt-3 ">
            <div class="col-auto align-self-start">
                <button @click="goBack" class="btn btn-danger"> 
                   <i class="la la-angle-left"></i>
                    Retour
                </button>
            </div>      
            <div class="col-auto justify-content-between">
                <button type="button"   @click="saveOrUpdate(currentQuote.id , currentQuote.status )" class="btn btn-success"> 
                    <i class="las la-save"></i> Enregister 
                </button>

                <button  v-if="currentQuote && currentQuote.id && isQuote"  @click="convertToInvoice(currentQuote.id)" class="btn btn-warning"> 
                    <i class="las la-exchange-alt"></i> <span class="text-white">  Transformer en facture</span>
                </button>

                <a  @click="generatePdf()"  v-if="currentQuote.id" type="button"  class="btn btn-primary"  target="_blank">   <i class="las la-file-pdf"></i> <span class="text-white">  Générer le pdf </span></a>

                <button  v-if="mode == 'edit'  && isInvoice && currentQuote.status == 'draft'" @click="finalizeInvoice(quote_id)" class="btn btn-success btn-outline-warning">
                    <span class="text-dark">
                         Finaliser la facture
                    </span>
                    
                </button>
            </div>      
        </div>
       
       
        <div class=" quote-info row justify-content-between mb-3 w-auto">

       
            <div class= "col-md-5 client-info mb-3 ">
                <h3>Détails du Client :</h3>
                <div class="row  mt-4 pl-4 pr-4">
                    <label class="col-4 text-nowrap" for="client"> Nom du client :</label>
                    <input  class="form-control col-8" v-model="currentQuote.client.name" type="text"  id="city" readonly>
                </div>
                <div class="col">
                    <current-address
                        :addresses="currentQuote.client.addresses" 
                        :quote_address_id="currentQuote.address_id"
                        statuses="billing address"
                        @addedOrUpdated="addedOrUpdated"
                        >
                    </current-address>
    
                </div>
            </div>
       


            <div class="col-md-5 quote-detail">
                    <h3 v-if="isQuote">Détails du devis :</h3>
                     <h3 v-if="isInvoice">Détails de la facture :</h3>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label for="creation_date">Date</label>
                                <input v-model="currentQuote.creation_date" type="date" class="form-control" id="creation_date" :readonly="isReadOnly">
                                    <p v-if="quoteFormErrors.creation_date" class="help-block text-error">
                                    {{ quoteFormErrors.creation_date[0] }}
                                </p>
                            </div>
                        </div>
                            <div class="col">
                            <div class="form-group">
                                <label for="validity_date">Validité</label>
                                <input v-model="currentQuote.validity_date" type="date" id="validity_date" class="form-control" :readonly="isReadOnly">
                                <p v-if="quoteFormErrors.validity_date" class="help-block text-error">
                                    {{ quoteFormErrors.validity_date[0] }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" v-if="mode == 'edit'">
                        <label for="quote_number" v-if="isQuote">Devis n°</label> 
                        <label for="quote_number" v-if="isInvoice">Facture n°</label> 
                            <input v-model="currentQuote.number"  type="text" class="form-control" id="quote_number" readonly>
                                <p v-if="quoteFormErrors.number" class="help-block text-error">
                                {{ quoteFormErrors.number[0] }}
                            </p>
                    </div >
                    <div class="form-group">
                        <label for="notes">Notes:</label>
                        <textarea  v-model="currentQuote.notes" id="notes" class="md-textarea form-control" rows="3" :readonly="isReadOnly"></textarea>
                        <p v-if="quoteFormErrors.notes" class="help-block text-error">
                            {{ quoteFormErrors.notes[0] }}
                        </p>
                    
                    </div> 
                
            </div>
        </div>
        
        <div class="row articles" >
            <div class="col w-auto">
                <div class="row">
                        <h3 class="title-section ml-3">Articles :</h3>
                </div>
                <form name="myform" novalidate>
                
                    <div class="row mr-2">
                        <draggable
                            class="draggable"
                            v-model="currentQuote.rows"
                            tag="tbody"
                            @start="drag = true"
                            @end="reorderQuoteRows"
                            v-bind="dragOptions"
                            handle=".handle"

                        >
                        
                            <add-quote-row
                                v-for="(row , row_index)  in currentQuote.rows"
                                :key="'quote_row-' + (row.v_id || row.id) "
                                :v_id="(row.v_id || row.id)"
                                :row="row"
                                :row_index="row_index"
                                @removed="removeRow"
                                @updated="updateRow( $event.row, row_index)" 
                                >
                        
                            </add-quote-row>
                        </draggable>  
                    </div>
                </form>

                <div class="row justify-content-end mt-3 mr-3 mb-3">
                    <div class="col">
                        <button type="button" @click.prevent="createEmptyServiceRow" class="btn  btn-success">
                            <span class="text-dark">
                                Ajouter un service
                            </span>
                            
                        </button>
                    </div>

                </div> 
            </div>
        </div>
        <div class="row quote-option mt-4">
            <div class="col w-auto">
                <div class="row text-left">
                    <h3 v-if="isQuote" class="title-section ml-3">Options de Devis :</h3>
                    <h3 v-if="isInvoice" class="title-section ml-3">Options de la facture :</h3>
                </div>

                <div class="row justify-content-start pl-3">
                    <h4>Réduction global :</h4>
                </div>
                <div class="row mt-3 mb-3">  
                    <div class="col input-group d-inline-flex align-items-center w-auto">
                        <input v-model="global_discount_value"  type="number" min="0" class="form-control" id="global_discount_value" placeholder="Valeur geste commerciale" :readonly="isReadOnly">
                    </div>
                    <div  class="col input-group d-inline-flex align-items-center w-auto">
                        <select class="custom-select" id="global_discount_devise" v-model="global_discount_unit"  :readonly="isReadOnly">
                            <option value="euros">€</option>
                            <option value="pc">%</option>
                    
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col total mt-3">
            <div class="col-md-2 offset-10">
                <p class="text-nowrap">Réduction:   {{ numberFormat(total_discount_euro.toFixed(2)) }} €</p>
                <h4 class="text-nowrap">Total HT :  {{ numberFormat(total_without_tax.toFixed(2)) }} €</h4>
                <h4 class="text-nowrap">Total TTC :  {{ numberFormat(total_with_tax.toFixed(2)) }} €</h4>
            </div>
            
        </div>
     
        <div class="col-auto justify-content-between text-center mb-3">
            <button type="button"   @click="saveOrUpdate( currentQuote.id , currentQuote.status )" class="btn btn-success"> 
                <i class="las la-save"></i> Enregister 
            </button>

            <button  v-if="currentQuote && currentQuote.id && isQuote"  @click="convertToInvoice(currentQuote.id)" class="btn btn-warning"> 
                <i class="las la-exchange-alt"></i> <span class="text-white">  Transformer en facture</span>
            </button>

            <a  @click="generatePdf()"  v-if="currentQuote.id" type="button"  class="btn btn-primary"  target="_blank">   <i class="las la-file-pdf"></i> <span class="text-white">  Générer le pdf </span></a>

            <button type="button" v-if="mode == 'edit'  && isInvoice && currentQuote.status == 'draft'" @click="finalizeInvoice(quote_id)" class="btn btn-success btn-outline-warning">
                <span class="text-dark">
                        Finaliser la facture
                </span>
                
            </button>
        </div> 
    </div>
</template>
<script>
import AddQuoteRow from "./AddQuoteRow";
import draggable from "vuedraggable";
import Autocomplete from "v-autocomplete";
import AddressTemplate from "./AddressTemplate.vue";


export default ({
    name:'CurrentQuote',
    components: { AddQuoteRow , draggable  , Autocomplete , AddressTemplate },
    props:['mode' , 'project_id', 'edit_quote'  , 'quote_id' , 'client' , 'role'],   
    data() {
        return {    
            items: [],
            template: AddressTemplate,
            default_dates : {},
            addressFormErrors : {},
            quoteFormErrors : {},
            global_discount_unit: "euros",
            global_discount_value: 0,
        

        }
    },
     async beforeMount() {
        this.default_dates = await this.$getConfig("default_dates"); 
        this.currentQuote.client = this.client; 
       // this.loadMainAddress();
        if(!this.currentQuote.validity_date && !this.currentQuote.creation_date){
            this.currentQuote.validity_date =  this.isQuote ? this.default_dates.quote.validity_date : this.default_dates.invoice.validity_date ;
            this.currentQuote.creation_date = this.isQuote ? this.default_dates.quote.creation_date : this.default_dates.invoice.creation_date;
        }
    },
    methods: {
                                                //CRUD D UN DEVIS
        /**
         * 
         * Chargement d'un devis déja existant 
         */
        load(){
            

            var url = ""

            if(this.isQuote){
                    
                    url = "quotes/editQuote/"+this.quote_id
                
            }else if(this.isInvoice){
      
                    url = "invoices/editInvoice/"+this.quote_id
            }

            this.$http.get(url)
            .then((resp) => {
                this.$store.state.quote = resp;

                this.convertDiscountEuroToDiscountValue(resp);
                // On récupère le client si il existe 
                if(this.client){
                    this.$store.state.quote.client = this.client;
                }
                // On store le mode 
                this.$store.state.quote.mode = this.mode;
               // this.loadMainAddress();
              
            })
        },

        /**
         * permet d'enregistrer le devis ainsi que toute ses lignes 
         */
        save(){
            var url = ""

            if(this.isQuote){
                url = "quotes"
            }else if(this.isInvoice){
                url = "invoices"
            }
            this.$http.post(url , {
                    project_id: this.project_id,
                    creation_date : this.currentQuote.creation_date,
                    validity_date: this.currentQuote.validity_date, 
                    status: "draft",
                    address_id: this.currentQuote.address_id,
                    number: null,
                    notes: this.currentQuote.notes,
                    discount_euro : this.global_discount_euro,
                    discount_unit: this.global_discount_unit,
                    sell_total:this.total_without_tax,
                    rows : this.currentQuote.rows,

            }).then((resp) => {
                          
                this.$store.state.quote.id = resp.id;
                this.$store.state.quote.status = resp.status;
                this.$store.state.quote.number = resp.number;

                if(resp.rows.length){
                    for(let index = 0 ; index < resp.rows.length ; index ++){
                        let currentRow = this.$store.state.quote.rows[index];
                        let responseRow  = resp.rows[index] ; 
                        currentRow.id = responseRow.id;

                    }
                }


                this.add(); // ajoute le devis sur la page liste 
               

                new Noty({
                    type: "success",
                    text: this.isQuote ? "Devis enregistré" : "Facture enregistré",
                }).show();
                this.quoteFormErrors = "";
                this.$set(this.$store.state.quote , 'quoteFormErrors' , this.quoteFormErrors);

            }).catch((err) => {
                new Noty({
                    type: "error",
                    text: "Erreur lors de l'enregistrement",
                }).show();
                    if (err.status == 422) {
                        this.quoteFormErrors = err.data.errors;
                        this.$store.state.quote.quoteFormErrors = this.quoteFormErrors;
                        this.$set(this.$store.state.quote , 'quoteFormErrors' , this.quoteFormErrors);
                    }
            })
        },

        /**
         * Function permettant l'update du Devis
         */
        update(quoteId , status){
                var url = ""
               
                if(this.isQuote){
                    url = "quotes/"+quoteId
                }else if(this.isInvoice){
                    url = "invoices/"+quoteId
                }
            
                return this.$http.put(url,{
                project_id: this.project_id,
                creation_date : this.currentQuote.creation_date,
                validity_date: this.currentQuote.validity_date, 
                status: status ,
                address_id: this.currentQuote.address_id,
                number: this.currentQuote.number,
                notes: this.currentQuote.notes,
                discount_euro : this.global_discount_euro,
                discount_unit: this.global_discount_unit,
                sell_total:this.total_without_tax,
                rows : this.currentQuote.rows,
            }).then((resp) => {
         

                this.currentQuote.status = resp.status;
                this.currentQuote.number = resp.number;

                if(resp.rows.length){
                    for(let index = 0 ; index < resp.rows.length ; index ++){
                        let currentRow = this.$store.state.quote.rows[index];
                        let responseRow  = resp.rows[index] ; 
                        currentRow.id = responseRow.id;

                    }
                }

                new Noty({
                    type: "success",
                    text:  this.isQuote ? "Devis edité" : "Facture editée",
                }).show();
                this.quoteFormErrors = "";
                this.$set(this.$store.state.quote , 'quoteFormErrors' , this.quoteFormErrors);

            }).catch((err ) => {
                new Noty({
                    type: "error",
                    text: "Erreur dans le formulaire",
                }).show();
                if (err.status == 422) {
                        this.quoteFormErrors = err.data.errors;
                        this.$set(this.$store.state.quote , 'quoteFormErrors' , this.quoteFormErrors);

                       // this.$store.state.quote.quoteFormErrors = this.quoteFormErrors;
                    }
            })


          
           
        },


        saveOrUpdate(quote_id , status ){
            if(quote_id){
                this.update(quote_id , status )
            }else{
                this.save();
            }
        },
        /**
         * Génération d'un devis à partir de la propal
        */  
        generatePdf(){
            
                let win = window.open('', '_blank');

                var url = ""

                if(this.isQuote){
                    url = '/admin/api/quotes/'+this.currentQuote.id+'/printPdf'
                }else if(this.isInvoice){
                    url = '/admin/api/invoices/'+this.currentQuote.id+'/printPdf'
                }

                win.location.href = url;
           
        },

        convertToInvoice(quote_id){
            let  url = "quotes/"+quote_id+"/convert-to-invoice"
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

        finalizeInvoice(){
            if (confirm("Finaliser cette facture ?")){
                this.update( this.currentQuote.id,"waiting for payment").then((resp) => {
               
                     this.$emit('isAllowed' , { quote_id :this.currentQuote.id , status: "waiting for payment", number: this.currentQuote.number}); 
                });
               

            }
        },

                                                //GESTION DES LIGNES =>QuoteRow
        /**
        * Supprime une ligne 
         */
        removeRow(row_index){
            let rowToDelete = this.currentQuote.rows[row_index] ;
            if(typeof rowToDelete.v_id === 'string' || rowToDelete.v_id instanceof String){
                return  this.currentQuote.rows.splice(row_index,1);
            }


            var url = ""

            if(this.isQuote){
                url = 'quotes/quote-row/'+rowToDelete.id+'/delete'
            }else if(this.isInvoice){
                url = 'invoices/invoice-row/'+rowToDelete.id+'/delete'
            }
                        
            return this.$http.delete(url).then((resp)=>{
                        new Noty({
                        type: "success",
                        text: "Article supprimé avec succés",
                    }).show();
                    this.currentQuote.rows.splice(row_index,1);
                }).catch((error)=> {
                        new Noty({
                        type: "error",
                        text: "Erreur lors de la suprression de l'article",
                    }).show();
                });
            

        },
        /**
        * Permet de crrer une product Row
         */
        
        createEmptyProductRow(){
            
            this.currentQuote.rows.push({
                v_id: Math.random()
                        .toString(36)
                        .substring(7),
                article : null,
                article_id: '',
                article_type: 'App\\Models\\Product',
                marking_id: '',
                marking_nb_colors: '',
                marking_nb_colors_is_max: false,
                quantity: '',
                unity: 'piece',
                unit_price: '',
                vat_rate: '20.00' ,
                discount_value: '0',
                discount_unit: 'euros',
                discount_euro: '',
                print_template: '',
                print_logo: '',
                sell_total: '',
                order: '',
                description: '',

            });
            this.$store.state.quote.rows = this.currentQuote.rows;
          
           
        },

          /**
        * PErmet de creer une product Row
         */
        createEmptyServiceRow(){
            this.currentQuote.rows.push({
                v_id: Math.random()
                        .toString(36)
                        .substring(7),
                article : null,
                article_id: '',
                article_type: 'App\\Models\\Service',
                quantity: '',
                unity: 'piece',
                unit_price: '',
                vat_rate: '20.00',
                discount_value: '0',
                discount_unit: 'euros',
                discount_euro: '',
                sell_total: '',
                order: '',
                description: '',

            });
        
        },


                /**
         * Recupere de l'enfant l'address principale 
         */
        addedOrUpdated(event){
          
            if(event.address && event.address.id){
                this.currentQuote.address_id = event.address.id;               
            }else{
                this.currentQuote.address_id = null;
            }

        },   

        /**
        * ajoute le devis sur la page liste
         */
        add(){
            
            this.$emit('added' , {quote : this.currentQuote}); 
        },

        /**
         * Réordonne les lignes d'article
         */
        reorderQuoteRows(){
            this.drag = false;
            this.$store.state.quote.rows =  this.$store.state.quote.rows.map((row, index) => {
                row.order = index;
                return row;
            });
        },

        /**
         * Converti la valeur discount_euro de la base en discount_value du front
         */
        convertDiscountEuroToDiscountValue(resp){
            this.global_discount_unit = resp.discount_unit;
            if(resp.discount_unit == "euros"){
                    this.global_discount_value =  parseInt(resp.discount_euro);
            }else if(resp.discount_unit == "%"){
                this.global_discount_value =  (parseInt(resp.sell_total)+parseInt(resp.discount_euro)) / parseInt(resp.discount_euro) ;
            }
        },
        /**
         * Formate les nomber pour séparer les milliers avec un point
         */
        numberFormat(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        goBack(){
            this.$emit('switchMode', {mode : 'list'} );
        }

    },
    computed: {
    
        // Calcul le total des ventes ( total de chaque ligne ) sans la TVA 
        total_without_tax() {
                return this.total_without_tax_without_global_discount - this.global_discount_euro;
        },
        // Calcul le total des ventes ( total de chaque ligne ) avec la TVA
        total_with_tax(){
                return this.total_without_tax * 1.2;
        },
        // Calcul le total des vente sans la TVA et sans réduction global
        total_without_tax_without_global_discount(){
            let total_without_tax = 0;
                for(let row of this.currentQuote.rows){
                    total_without_tax +=   parseFloat(row.sell_total) || 0;
                }
                return total_without_tax
        },
        // Calcul  la totalité des réductions existantes , celle des lignes et celle global 
        total_discount_euro(){
            let total_discount_euro =  0;
            for(let row of this.currentQuote.rows){
                   total_discount_euro += (parseFloat(row.discount_euro) || 0);
                }
            return total_discount_euro + this.global_discount_euro;
        },
        // Calcul la reduction  global ( cad celle rajouter en fin de formulaire)
        global_discount_euro(){
            if(this.global_discount_unit == "euros"){
                return Number(this.global_discount_value);
            }
         
            
            return this.global_discount_value/100 * this.total_without_tax_without_global_discount;
        },
        // Drag option de draggables voir doc sortable.js
        dragOptions() {
            return {
                animation: 0,
                group: "description",
                disabled: false,
                ghostClass: "ghost"
            };
        },
        /**
         * Calcul le total agefiph
         */
        total_agefiph(){
            let total_agefiph =  0;
            for(let row of this.currentQuote.rows){
                   total_agefiph += (parseFloat(row.agefiph) || 0);
                    
                }
            return total_agefiph;
        },

         /**
         * Devis en cours 
         */
        currentQuote(){
            return this.$store.state.quote;
        },

        isQuote(){
            return this.role  == "quote" ?  true :  false
        },
        isInvoice(){
             return this.role  == "invoice" ?  true :  false
        },

        isReadOnly()
        {
            return this.isInvoice && this.currentQuote.status != "draft";
        }
    },
    watch: {
        mode(){
            if(this.mode == 'edit'){
                this.load();
            }
        },
    }
})
</script>

<style scoped>

@import url('https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap');

 html,
body {
    font-family: 'Open Sans', sans-serif;
}

.quote-info , .articles , .quote-option{
    border: 1px solid  black;
    border-radius: 20px;
    box-shadow: 5px 2px 2px gray;
}


h3 {
    margin: 10px 0px 15px 0px;
}

.btn {
    border-radius : 10px;
}

::placeholder {
  color: rgb(163, 157, 157);
}


.draggable{
    width:100%;
}

.text-desc-icon {
    cursor:pointer;
}

</style>