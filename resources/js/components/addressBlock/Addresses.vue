<template>
    <div class="container-fluid">
            <button
                type="button"
                @click="showModal = true"
                class="btn btn-primary btn-sm"
                style="margin-bottom:15px"
            ><i class="la la-plus"></i> Ajouter
            </button>

            <add-address
                :showModal="showModal"
                :client="currentClient"
                :client_name="currentClient.name"
                @close="showModal = false"
                @add="onAdded"
            >
            </add-address>
        <div class="row">
            <div
                :key="address.id"
                v-for="(address,index) in addresses"
            >
                <div class="col-4">
                    <div class="card" style="width: 30rem;">
                        <div class="card-body">
                            <h5 class="card-title">Details de l'Adresse :</h5>

                        
                            <label :for="'status_input'+address.id">Type d'adresse</label>



                     
                             
                           
                             <label :for="'name_input'+address.id">Entreprise</label>
                            <input v-model="address.name" type="textarea" placeholder="Entreprise" class="form-control" :id="'name_input'+address.id" >
                            <p v-if="formErrors.name" class="help-block text-error">
                                    {{ formErrors.name[0] }}
                            </p> 
                           
                            <label :for="'address_input'+address.id">Adresse</label>
                            <textarea v-model="address.address" type="textarea" placeholder="Address" class="form-control" :id="'address_input'+address.id" ></textarea>
                            <p v-if="formErrors.address" class="help-block text-error">
                                    {{ formErrors.address[0] }}
                            </p> 
                          

                            <label for="phone_number_input">Numéro de téléphone :</label>
                            <input 
                                v-model="address.phone"  type="tel" placeholder="06 12 XX XX XX" 
                                class="form-control" id="phone_number_input">

                            <p v-if="formErrors.country" class="help-block text-error">
                                        {{ formErrors.country[0] }}
                            </p> 
                               

                             <div class="form-check" >
                                <input  v-model="address.is_main" type="radio" value="1" class="form-check-input" :name="'is_main'+address.addressable_id" id="is_main"  >
                                <label for="is_main" class="form-check-label">Adresse Principale</label>
                                <p v-if="formErrors.is_main" class="help-block text-error">
                                    {{ formErrors.is_main[0] }}
                                </p>
                                
                             </div>

                     

                            <div class="form-group" style="margin-top:15px">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <button
                                            @click="del(index)"
                                            type="button"
                                            class="btn btn-danger"
                                        >
                                            <i class="la la-trash"></i>
                                        </button>  
                                    </div>
                                    <div class="col-sm-2 offset-sm-8">
                                        <button  
                                            @click="update(address)"
                                            type="button"
                                            class="btn btn-success"
                                        >
                                            <i class="la la-save"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import AddAddress from "./AddAddress";





export default ({
    components: { AddAddress },
    name: "Addresses", 
    data() {
        return {
            showModal: false,
            formErrors: {},
            addresses: [],
            loading: false,
            currentClient: {},
           
         
        };
    },
    props: [ "client" , "title" ],
    async beforeMount(){
        
        this.currentClient = JSON.parse(this.client);
        this.addresses = this.currentClient.addresses;
    }, 
    methods: {
        debug(item) {
            console.log(item);
        },
        load(){
            return this.$http.get("addresses", {
                params: {
                    client_id: this.currentClient.id,
                }
            });
            
        },
        update (address) {

            this.loading = true;

            let formData = new FormData();

            formData.append("addressable_type" , "App\\Models\\Client");
            formData.append("addressable_id" ,this.currentClient.id );
           
            for (let field in address) {
                formData.append(field, address[field] != null  ? address[field] : "");
            }

            this.$http.post("addresses/"+address.id+"?_method=PUT", formData, {} )
            .then((resp) => {
                new Noty({
                    type: "success",
                    text: "Addresse mise à jour",
                }).show();
            }).catch((err) => {
                new Noty({
                    type: "error",
                    text: "Erreur",
                }).show();

                    if (err.status == 422) {
                        this.formErrors = err.data.errors;
                        
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        del(index) {
            if (confirm("Supprimer cette Adresse ?")){
                this.$http.delete("addresses/" + this.addresses[index].id).then(() => {
                    this.addresses.splice(index, 1);

                    new Noty({
                        type: "success",
                        text: "Adresse supprimé",
                    }).show();
                }).catch((err) => {
                    new Noty({
                        type: "error",
                        text: "Erreur lors de la suppression de l'adresse",
                    }).show();
                });
            }
        },
        onAdded(event) {
                
            this.showModal = false;
            this.addresses.push(event.added);
          

            

            new Noty({
                type: "success",
                text: "Adresse ajouté",
            }).show();
               


        },
    },
})
</script>
<style scoped>
    label{
        margin-top:10px;
    }

    #is_main{
        margin-top: 15px;
    }

    .form-check{
        margin:15px 0 20px 0;
    }
</style>