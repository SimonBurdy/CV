<template>  
    <div class="col w-auto">
         <template>
                <div class="form-row  mt-4" v-if="addresses && addresses.length"> 
                    <label class="col-4 text-nowrap" for="select_address">Choisir:</label>
                    <select @change="changeSelectedAddress" class="col-8" v-model="selectedAddress" id="select_address">
                        <option :value="null">Aucune</option>
                        <option
                            class="w-auto h-auto form-control"
                            v-bind:value="address.id"
                            :key="'address-' + address.id"
                            v-for="address in addresses"
                        >{{ address.name }}
                        </option>
                    </select>
                </div>
           
                <div class="form-group mt-4">
                        <label for="name">Entreprise:</label>
                        <textarea  v-model="mainAddress.name" id="name" class="form-control" ></textarea>
                         <p v-if="addressFormErrors.name" class="help-block text-error">
                            {{ addressFormErrors.name[0] }}
                        </p> 
                </div>
                <div class="form-group mt-4">
                    <label for="address">Adresse :</label>
                    <textarea  v-model="mainAddress.address" id="address" class="form-control" ></textarea>
                    <p v-if="addressFormErrors.address" class="help-block text-error">
                        {{ addressFormErrors.address[0] }}
                    </p>
                </div>

                
                <div class="form-row mb-3 mt-4">
                    <label class="col-4 " >Téléphone :</label>
                    <input  class="form-control col-8"  v-model="mainAddress.phone" type="text"  placeholder="06 46 55 XX XX">    
                </div>

                <div class="row d-flex justify-content-between">
                    <div class="col text-left" >
                         <button type="button" class="btn btn-outline-danger" @click="eraseMainAddress">
                            🧼     <small class=" text-desc-icon  mt-3 ml-2">Effacer l'adresse</small>  
                        </button>
 
                    </div>
                    <div class="col text-right">
                        <div class="dropdown">
                            <button class="btn btn btn-success dropdown-toggle" type="button" id="addressActionButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Enregistrer l'adresse
                            </button>
                            <div class="dropdown-menu" aria-labelledby="addressActionButton">
                                <a v-if="addresses && addresses.length" class="btn dropdown-item"  @click.prevent="updateAddress()"  href="#" >Enregistrer et écraser</a>
                                <a class="dropdown-item" @click.prevent="saveAddress()"  href="#">Créer une nouvelle adresse</a>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
    </div>
</template>
<script>

export default ({
    name:"CurrentAddress",
    props:["addresses" , "statuses" , "quote_address_id" ],
    data() {
        return {
            mainAddress: {
                name: "",
                address: "",
                phone: "",
            },
            addressFormErrors: {},
            selectedAddress : "",
        }
    },
    async mounted(){
         
            if(this.addresses.length ){
                if(this.quote_address_id){
                    this.mainAddress = this.addresses.find((address) => address.id == this.quote_address_id);
                    this.selectedAddress =  this.mainAddress.id ;

                }else{
                        this.mainAddress = this.addresses.find((address) => address.is_main == 1) || this.addresses[0];
                        this.selectedAddress =  this.mainAddress ? this.mainAddress.id : this.addresses[0].id;
                        this.$emit('addedOrUpdated' , {address : this.mainAddress });
                }
                
            }
    },
    methods : {
         saveAddress(){
            this.$http.post("addresses" , {
                addressable_type: "App\\Models\\Client",
                addressable_id: this.currentQuote.client.id ,
                name : this.mainAddress.name,
                address : this.mainAddress.address,
                country :this.mainAddress.country,
                statuses: this.statuses,
                is_main: 0,
                phone : this.mainAddress.phone ,
            }).then((resp) => {
                this.mainAddress = resp;
                this.addresses.push(resp);
                this.$store.state.quote.address_id =  this.mainAddress.id;
                new Noty({
                    type: "success",
                    text: "Adresse enregistré avec succée",
                }).show();

                 new Noty({
                    type: "warning",
                    text: "N'oublie pas d'enregistrer le devis pour associer l'adresse à ce devis",
                }).show();
            }).catch((err) => {
                new Noty({
                    type:"error",
                    text: "Erreur lors de l'enregrstrement de l'adresse",
                }).show();
                if (err.status == 422) {
                    this.addressFormErrors = err.data.errors;
                }
            })
        },
 
        /**
        * Update l'addresse de facturation 
        */
        updateAddress(){
            this.$http.put("addresses/"+this.mainAddress.id ,{ 
                addressable_type: "App\\Models\\Client",
                addressable_id: this.currentQuote.client.id ,
                name : this.mainAddress.name,
                address : this.mainAddress.address,
                statuses: this.statuses,
                phone : this.mainAddress.phone ,
            }).then((resp) => {
                new Noty({
                    type: "success",
                    text: "Adresse editée avec succée",
                }).show();

                 new Noty({
                    type: "warning",
                    text: "N'oublie pas d'enregistrer le devis pour associer l'adresse à ce devis",
                }).show();
            }).catch((err) => {
                new Noty({
                    type: "error",
                    text: "Erreur lors de la modification de l'adresse",
                }).show();
                if (err.status == 422) {
                        this.addressFormErrors = err.data.errors;
                    }
            })
        }, 

        /**
         * Supprimer le contenue d'une main Address
         */
        eraseMainAddress(){
             this.mainAddress =  {};
        },
        changeSelectedAddress(event){ 
            
            if(event.target.value){
                    this.mainAddress = this.addresses.find((address) => address.id == event.target.value);
            }else{
                this.eraseMainAddress();
                 
            }

            this.$emit('addedOrUpdated' , {address : this.mainAddress }); 

        }

            
        
    },
    computed: {
        currentQuote(){
            return this.$store.state.quote;
        }
    },
     watch: {
        "$store.state.quote.address_id"(newVal){


            if(newVal && newVal != ""){
                
                if(this.addresses.length){
                    this.selectedAddress =  this.addresses.find((address) => address.id == newVal).id;
                  
                }

            }else{
            
                this.selectedAddress = null;
            }

        },


    }

})
</script>
