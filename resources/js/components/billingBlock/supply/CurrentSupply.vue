<template>
  <div v-if="showModal">
        <transition name="modal">
            <div class="modal-mask">
                <div
                    class="modal-wrapper"
                    @click="closeModal"
                >

                    <div
                        class="modal-dialog"
                        @click.stop
                    >
                        <div class="modal-content">
                                <div class="modal-header">
                                    <legend>Ajouter un Achat : </legend>
                                    <button
                                        type="button"
                                        class="close"
                                        @click="closeModal"
                                    >

                                 
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                      <div class="col-md-12">
                                           <label for="name_input">Fournisseur</label>
                                            <input v-model="currentSupply.supplier" type="text" placeholder="A saisir" class="form-control" id="name_input">
                                                <p v-if="formErrors.supplier" class="help-block text-error">
                                                        {{ formErrors.supplier[0] }}
                                            </p> 
                                        </div>
                                    </div>
                                  
                                    
                                
                                  

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="total_supply_input">Montant total HT</label>
                                            <input v-model="currentSupply.total_supply" type="number" placeholder="A saisir" class="form-control" id="total_supply_input">
                                                <p v-if="formErrors.total_supply" class="help-block text-error">
                                                        {{ formErrors.total_supply[0] }}
                                            </p> 
                                        </div>


                                        <div class="col-md-4">
                                             <label for="vat_rate_select">TVA</label>
                                            <select 
                                                id="vat_rate_select"
                                                v-model="currentSupply.vat_rate"
                                                class="form-control w-auto "
                                                >
                                                    <option
                                                        :value="index"
                                                        :key="'vat_rate-'+index"
                                                        v-for="(value,index) in vat_rates"
                                                    >
                                                    {{value }}
                                                    </option>
                                            </select>
                                        </div>



                                        <div class="col-md-4">
                                            <label for="total_supply_input">Montant total TTC</label>
                                            <input v-model="currentSupply.total_supply_net" type="number"  class="form-control" id="total_supply_net_input" readonly>
                                                <p v-if="formErrors.total_supply_net" class="help-block text-error">
                                                        {{ formErrors.total_supply_net[0] }}
                                                </p>
                                        </div>
                                    </div>

 
                                      
                                   <label for="status_input">Status</label>

                                    <select 
                                        v-model="currentSupply.status"
                                        class="form-control form-control-sm"
                                        >
                                            <option
                                                :value="index"
                                                :key="'editquote-'+index"
                                                v-for="(value,index) in supplies_statuses"
                                            >{{ value  }}
                                            </option>
                                    </select>
                                     <p v-if="formErrors.status" class="help-block text-error">
                                            {{ formErrors.status[0] }}
                                    </p>

                                     <label>Proforma *</label>
                                    <div
                                        class="form-group col-auto"
                                        :class="{
                                            'text-error': formErrors.proforma_path
                                        }"
                                    >   
                                   
                                      
                                        <div class="form-inline">
                                            <button
                                                @click="showUpdateFile('Proforma')"
                                                v-if="currentSupply.id && proformaFile"
                                                type="button"
                                                class="btn btn-sm btn-warning"
                                            >   
                                                <i class="la la-edit" v-if="showProformaFile">
                                                    <span >Remplacer</span>
                                                </i>
                                                <i class="las la-eye" v-else>
                                                    <span>Voir fichier actuel</span>
                                                </i>
                                            
                                            </button>

                                            <div v-if="!showProformaFile || !proformaFile" >
                                                <div class="backstrap-file" >
                                                    <input
                                                        type="file"
                                                        name="proforma_path"
                                                        ref="proforma_file"
                                                        value=""
                                                        class="file_input backstrap-file-input"
                                                    />
                                                    <label
                                                        class="backstrap-file-label"
                                                        for="customFile"
                                                    ></label>
                                                </div>

                                                <p
                                                    v-if="formErrors.proforma_path"
                                                    class="help-block text-error"
                                                >
                                                    {{ formErrors.proforma_path[0] }}
                                                </p>
                                            </div>

                                            <div v-if="showProformaFile && proformaFile">
                                                <strong>Fichier actuel :</strong>
                                                <span> {{proformaFile.name}}</span>
                                            </div>
                                        </div>
                                        <div class="mx-auto form-inline row justify-content-center mt-2" v-if="currentSupply.id && proformaFile">
                                            
                                            <button
                                                   
                                                    @click="downloadFile(proformaFile)"
                                                    type="button"
                                                    class="btn btn-sm btn-outline-primary"
                                                >
                                                <i class="la la-download"></i> Télécharger
                                            </button>

                                            <button
                                              
                                                    @click="deleteFile(proformaFile)"
                                                    type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                <i class="la la-download"></i> Supprimer
                                            </button>
                                         
                                        </div>

                                    </div>


                                    <label for="number_input">Numéro de facture</label>
                                    <input v-model="currentSupply.number" type="text"  placeholder="A saisir" class="form-control" id="number_input">
                                    <p v-if="formErrors.number" class="help-block text-error">
                                                {{ formErrors.number[0] }}
                                    </p>  
                           
                                    
                                    <label>Facture fournisseur</label>
                                    <div
                                        class="form-group col-auto"
                                        :class="{
                                            'text-error': formErrors.invoice_path
                                        }"
                                    >
                                       
                                        
                                        <div class="mx-auto form-inline">
                                            <button
                                                v-if="currentSupply.id && invoiceFile"
                                                @click="showUpdateFile('Invoice')"
                                                type="button"
                                                class="btn btn-sm btn-warning"
                                            >
                                                <i class="la la-edit" v-if="showInvoiceFile">
                                                    <span >Remplacer</span>
                                                </i>
                                                <i class="las la-eye" v-else>
                                                    <span>Voir fichier actuel</span>
                                                </i>
                                            </button>

                                            <div v-if="!showInvoiceFile || !invoiceFile" >
                                                <div class="backstrap-file ">
                                                    <input
                                                        type="file"
                                                        name="invoice_path"
                                                        ref="invoice_file"
                                                        value=""
                                                        class="file_input backstrap-file-input"
                                                    />
                                                    <label
                                                        class="backstrap-file-label"
                                                        for="customFile"
                                                    ></label>
                                                </div>
                                                <p
                                                    v-if="formErrors.invoice_path"
                                                    class="help-block text-error"
                                                >
                                                    {{ formErrors.invoice_path[0] }}

                                                </p>
                                            </div>

                                            <div v-if="showInvoiceFile && invoiceFile">
                                                <strong>Fichier actuel :</strong>
                                                <span>  {{invoiceFile.name}}</span>
                                            </div>
                                        </div>
                                        <div class="mx-auto form-inline row justify-content-center mt-2"   v-if="currentSupply.id && invoiceFile">
                                          
                                            <button
                                                  
                                                    @click="downloadFile(invoiceFile)"
                                                    type="button"
                                                    class="btn btn-sm btn-outline-primary"
                                                >
                                                <i class="la la-download"></i> Télécharger
                                            </button>

                                            <button
                                              
                                                    @click="deleteFile(invoiceFile)"
                                                    type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                >
                                                <i class="la la-download"></i> Supprimer
                                            </button>
                                         
                                        </div>

                                        
                                    </div>

                                  
                                    <div class="row">
                                      
                                            <button 
                                                @click="saveOrUpdate"
                                                :disabled="loading"
                                                class="col-md-6 offset-md-3 btn btn-success"
                                                style="margin-top:15px"
                                                type="button"
                                            >
                                                Enregistrer
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
    name:'CurrentSupply',
    props:['showModal','supplies_statuses' , 'project_id' ,"index" , "vat_rates"],
    data() {
        return {
            items: [], // auto-complete 
            currentSupply: {
                supplier : null,
                number:"",
                total_supply: "",
                total_supply_net: "",
                status: "",
                proforma_path : "",
                invoice_path: "",
                vat_rate: "20.00"

               
            },
            showInvoiceFile: false,
            showProformaFile:false,
            formErrors: {},
            loading: false,
        }
    },
    methods: {

        /**
         * Permet d'uploader le fichier en fonction de sa category
         */
        uploadFile(category ,refFile){
            

            let formData = new FormData();

            let file = refFile;
         
            formData.append("path", file.files[0]);
            formData.append("category", category);
            formData.append("supply_id", this.currentSupply.id);

            this.$http
                .post("supplies/files", formData, {})
                .then((resp) => {
                    this.$store.state.supply.supplies.find(supply => supply.id == resp.fileable_id).files.push(resp);
  
                })
                .catch((err) => {
                    if (err.status == 422) {
                        this.formErrors = err.data.errors;
                    }
                })
               

        },  
        /**
         * Permet d'updater le fichier en fonction de sa category
         */
        updateFile(category ,refFile){
             let formData = new FormData();

            let file = refFile;
         
            formData.append("path", file.files[0]);
            formData.append("category", category);
      
            this.$http
                .post("supplies/files/"+this.currentSupply.id+"?_method=PUT", formData, {})
                .then((resp) => {
                    
                    this.$store.state.supply.supplies.find(supply => supply.id == resp.fileable_id).files.forEach(file => {
                            if(file.category == resp.category){
                                file.name = resp.name;
                                file.category = resp.category;
                                file.extension = resp.extension;
                                file.fileable_id = resp.fileable_id;
                                file.path = resp.path;

                                if(resp.category ==  "Proforma"){
                                    this.showProformaFile = true;
                                }else if(resp.category ==  "Facture"){
                                    this.showFactureFile = true;
                                }

                            }
                        });
                })
                .catch((err) => {
                    if (err.status == 422) {
                        this.formErrors = err.data.errors;
                    }
                })
        },
        /**
         * 
         */
        showUpdateFile(category){
            if(category == "Proforma"){
                this.showProformaFile = !this.showProformaFile;
            }

            if(category == "Invoice"){
                this.showInvoiceFile = !this.showInvoiceFile;
            }
        },
         /**
         * Permet de ddl le fichier
         */
        downloadFile(file){

            this.$http
                .get("supplies/files/"+file.id+"/download", {
                    responseType: "blob"
                })
                .then(response => {
                    const url = window.URL.createObjectURL(
                        new Blob([response])
                    );
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute(
                        "download",
                        file.name + "." + file.extension
                    );
                    document.body.appendChild(link);
                    link.click();
                })
                .catch(err => {
                    new Noty({
                        type: "error",
                        text: "Erreur lors du téléchargement"
                    }).show();
                });
        },
        deleteFile(file){
            if (confirm("Supprimer ce fichier ?")) {
                this.$http
                    .delete("supplies/files/"+file.id+"/delete")
                    .then(resp => {
                        
                        // Permet de supprimer du l'array 
                        let  supplyFiles = this.supplies.find(supply => supply.id == file.fileable_id).files;

                        let idx =   supplyFiles.findIndex(e => e.id == file.id);

                        if (idx != -1) supplyFiles.splice(idx, 1);

                        new Noty({
                            type: "succes",
                            text: "Fichier supprimé avec succée"
                        }).show();
                    })
                    .catch(err => {
                        new Noty({
                            type: "error",
                            text: "Erreur lors de la suppression"
                        }).show();
                    });
            }
        },
        /**
         * Permet d'enregistrer l'ensemble du Supply
         */
        save() {
            let formData = new FormData();
            formData.append("project_id" ,this.project_id );
            
            for (let field in this.currentSupply) {
                formData.append(field, this.currentSupply[field] != null ? this.currentSupply[field]  : "");
            }
            this.$http
                .post("supplies", formData, {})
                .then((resp) => {
                    this.currentSupply.id = resp.id;


                    let proformaFile = this.$refs.proforma_file;

                    let invoiceFile =  this.$refs.invoice_file;


                    if(proformaFile && proformaFile.files && proformaFile.files[0]){
                        this.uploadFile("Proforma" , this.$refs.proforma_file);
                    }


                    if(invoiceFile && invoiceFile.files && invoiceFile.files[0]){
                        this.uploadFile("Facture" , this.$refs.invoice_file  );
                    }

                    
                    


                    this.$emit('add' , {
                        added: resp
                    });

                     this.closeModal();

                     new Noty({
                            type: "success",
                            text:  "Achat ajouté avec succée",
                    }).show();
                        
                })
                .catch((err) => {
                    if (err.status == 422) {
                        this.formErrors = err.data.errors;
                    }
                    new Noty({
                            type: "error",
                            text:  "Error lors de l'enregistrement",
                    }).show();
                })
                .finally(() => {
                    this.loading = false;
                });

        },
        /**
         * Pertmet d'update l'ensemble du currentSupply
         */
        update(){
            let formData = new FormData();
            formData.append("project_id" ,this.project_id );
       
            
            for (let field in this.currentSupply) {
                formData.append(field, this.currentSupply[field] != null ? this.currentSupply[field]  : "");
            }
            this.$http
                .post("supplies/"+this.currentSupply.id+"?_method=PUT", formData, {})
                .then((resp) => {
                    this.currentSupply.id = resp.id;

                   
                    let proformaFile = this.$refs.proforma_file;

                    let invoiceFile =  this.$refs.invoice_file;
                    
                    if(proformaFile && proformaFile.files && proformaFile.files[0]){
                   
                        if(this.proformaFile){
                            this.updateFile("Proforma" , this.$refs.proforma_file);
                        }else{  
                            this.uploadFile("Proforma" , this.$refs.proforma_file);
                        }
                    }

           
                    if(invoiceFile && invoiceFile.files && invoiceFile.files[0]){
                        if(this.invoiceFile){
                            this.updateFile("Facture" , this.$refs.invoice_file);
                        }else{  
                            this.uploadFile("Facture" , this.$refs.invoice_file);
                        }
                    } 


              
                      new Noty({
                            type: "success",
                            text:  "Achat édité avec succée",
                    }).show();
                    
                    this.closeModal();
                  
                })
                .catch((err) => {
                    if (err.status == 422) {
                        this.formErrors = err.data.errors;
                    }

                     new Noty({
                            type: "error",
                            text:  "Error lors de l'enregistrement",
                    }).show();
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        /**
         * Fonction intermédiaire
         */
        saveOrUpdate()
        {
            if(!this.currentSupply.supplier){
                this.sendErrorNoty('Fournisseur');
                return;
            }

            if(this.currentSupply.id){
                this.update();
            }else{
                this.save();
            }
        },
        /**
         * Vide le currentSupply
         */
        emptySupply(){
            this.currentSupply = {
                supplier : null,
                number:"",
                total_supply: "",
                total_supply_net: "",
                status: "",
                proforma_path : "",
                invoice_path: "",
                vat_rate: "20.00",
                files: [],
            };
    
        },
        /**
         * Envoie un noty error
         */
        sendErrorNoty(field){
            new Noty({
                    type: "error",
                    text: field+" à saisir",
            }).show();
        },
       
        closeModal(){
            this.$emit("close");
            this.emptySupply();
        }

    },
    computed : {
        supplies(){
            return this.$store.state.supply.supplies;
        },
        invoiceFile(){
            if(this.currentSupply.files){
                return this.currentSupply.files.find(file=>file.category == "Facture") || null;
              
            }
        },
        proformaFile(){
            if(this.currentSupply.files){
                return this.currentSupply.files.find(file =>file.category == "Proforma") || null;
         
            }

        },
    },
    watch: {
        index(newVal, oldVal){
            if(newVal != null){
                this.currentSupply = this.supplies[newVal];
            }
        
            
        },
        "currentSupply.total_supply"(newVal , oldVal){
        
            if(newVal){
                this.currentSupply.total_supply_net = Number(newVal)  + (Number(newVal)* Number(this.currentSupply.vat_rate))/100;
            }else{
                this.currentSupply.total_supply_net = 0;
            }
        },
        "currentSupply.vat_rate"(newVal , oldVal){
  
            if(newVal){
                this.currentSupply.total_supply_net = Number(this.currentSupply.total_supply) + (Number(this.currentSupply.total_supply)* Number(newVal))/100;
            }
        },
        invoiceFile(newVal){
            if(newVal){
                this.showInvoiceFile = true;
            }
        },
        proformaFile(newVal){
            if(newVal){
                this.showProformaFile = true;
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