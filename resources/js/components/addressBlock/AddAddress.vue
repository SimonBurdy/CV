<template>
  <div v-if="showModal">
        <transition name="modal">
            <div class="modal-mask">
                <div
                    class="modal-wrapper"
                    @click="$emit('close')"
                >

                    <div
                        class="modal-dialog"
                        @click.stop
                    >
                        <div class="modal-content">
                                <div class="modal-header">
                                    <legend>Ajouter une Adresse : </legend>
                                    <button
                                        type="button"
                                        class="close"
                                        @click="$emit('close')"
                                    >

                                    <span aria-hidden="true" @click="emptyAddress">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                  


                                    <label for="name_input">Entreprise</label>
                                    <input v-model="newAddress.name" type="text"  placeholder="Description" class="form-control" id="name_input">
                                    <p v-if="formErrors.name" class="help-block text-error">
                                                {{ formErrors.name[0] }}
                                    </p>                               

                                
                                    <label for="address_input">Adresse</label>
                                    <textarea v-model="newAddress.address"  placeholder="Adresse" class="form-control" id="address_input"></textarea>
                                    <p v-if="formErrors.address" class="help-block text-error">
                                                {{ formErrors.address[0] }}
                                    </p> 
                                


                                    <label for="phone_number_input">Numéro de téléphone :</label>
                                    <input 
                                        v-model="newAddress.phone"  type="tel" placeholder="06 12 XX XX XX" 
                                        class="form-control" id="phone_number_input">

                                    <p v-if="formErrors.country" class="help-block text-error">
                                                {{ formErrors.country[0] }}
                                    </p> 

                                   


                                    <div class="form-check" >
                                        <input  v-model="newAddress.is_main" type="radio"  class="form-check-input" name="is_main" value="1" id="is_main"  >
                                        <label for="is_main" class="form-check-label">Adresse Principale</label>
                                        <p v-if="formErrors.is_main" class="help-block text-error">
                                            {{ formErrors.is_main[0] }}
                                        </p>
                                
                                    </div>

                                  
                                    <div class="row">
                                      
                                            <button 
                                                @click="add"
                                                :disabled="loading"
                                                class="col-md-6 offset-md-3 btn btn-success"
                                                style="margin-top:15px"
                                                type="button"
                                            >
                                                Ajouter
                                            </button>
                                    
                                    </div>
                                    
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>

export default ({
    
    name:'AddAddress',
    props:['showModal' , 'client' ],
    data() {
        return {
            cityOptions: [],
            newAddress: {
                name : this.client.name,
              
               
            },
            formErrors: {},
            loading: false,
        }
    },
    methods: {
        add() {
            let formData = new FormData();

            formData.append("addressable_type" , "App\\Models\\Client");
            formData.append("addressable_id" ,this.client.id );
           
            for (let field in this.newAddress) {
                formData.append(field, this.newAddress[field] != null ? this.newAddress[field]  : "");
            }
            
            this.$http
                .post("addresses", formData, {})
                .then((resp) => {
                    this.newAddress = {
                        name : this.client.name,
                    };
                    this.$emit('add' , {
                        added: resp
                    });
                   
                  
                })
                .catch((err) => {
                    if (err.status == 422) {
                        this.formErrors = err.data.errors;
                    }
                })
                .finally(() => {
                    this.loading = false;
                });

        },
        emptyAddress(){
            this.newAddress = {
                name : this.client.name,
               
            }
        }
    }
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