<template>
    <div class="container-fluid">

         <div class=" d-flex justify-content-end">
            <button
            
                type="button"
                @click="showModal = true "
                class="btn btn-primary btn-sm "
                style="margin-bottom:15px"
            ><i class="la la-plus"></i> Ajouter
            </button>
        </div>

        <current-supply
        :showModal="showModal"
        :project_id="project_id"
        :supplies_statuses="supplies_statuses"
        :vat_rates="vat_rates"
        :index="index"
        @close="showModal = false ; index = null"
        @add="onAdded"
        >
        </current-supply>


        <table
                v-if="supplies"
                class=" bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs"
                cellspacing="0"
            >
                <thead>
                <tr style="font-size: 16px">
                    <th class="text-center">
                        Fournisseur
                    </th>
                    <th class="text-center">
                       N° Facture
                    </th>
                    <th class="text-center">
                        Statut
                    </th>
                    <th class="text-center">
                        Montant HT
                    </th>
                    <th class="text-center">
                        Montant TTC
                    </th>
                    <th class="text-center">
                        Actions
                    </th>
                </tr>
                </thead>
               <tbody>
                    
                <tr
                    :key="supply.id"
                    v-for="(supply , index) in supplies"
                >   


                    <td class="text-center">
                        <div class="row">
                            <div class="col-6">
                                <strong class="text-danger " v-if="supply.comments && supply.comments.length"
                                    >({{ supply.comments.length }})</strong
                                >
                                <button
                                    v-if="supply.id"
                                    class="variant-btn mr-1"
                                    @click="showCommentsModal(supply.id)"
                                    type="button"
                                >
                                    💬
                                </button>
                                <modal
                                    width="70%"
                                    :height="'auto'"
                                    :scrollable="true"
                                    :name="'comments_modal_' + supply.id"
                                >
                                    <div class="comments-modal-container">
                                        <comments
                                            @updated="supply.comments = $event.comments"
                                            model-class="Supply"
                                            :model-id="supply.id"
                                        ></comments>
                                    </div>
                                </modal>
                            </div>
                            <div class="col-6">
                                <span  v-if="supply.supplier">
                                
                                    {{ supply.supplier }}
                                </span>

                            </div>
                        </div>
                    </td>  
                     <td class="text-center">
             
                               <p v-if="supply.number"> {{ supply.number}}</p>
                                <p v-else> Aucun numéro</p>
                      
                    </td>             
                    <td class="text-center">

                        <select 
                        @change="update(supply)"
                        v-model="supply.status"
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
                                                {{ formErrors.status[0] }}</p>
                    </td>

                    <td class="text-center">
                           <p> {{ supply.total_supply}} €</p>
                    </td>
                    <td class="text-center"> 
                            <p> {{ supply.total_supply_net}} €</p>
                    </td>
                    <td class="row text-center">
                            <div class="mx-auto form-inline">
                                <button
                                    @click="changeIndex(index)"
                                    type="button"
                                    class="btn btn-sm btn-info"
                                ><i class="la la-edit"></i>
                                    <span>Editer</span>
                                </button>


                                <button
                                    @click="del(index)"
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
                    <th class="text-center">
                        Fournisseur
                    </th>
                    <th class="text-center">
                       N° Facture
                    </th>
                    <th class="text-center">
                        Statut
                    </th>
                    <th class="text-center">
                        Montant HT
                    </th>
                    <th class="text-center">
                        Montant TTC
                    </th>
                    <th class="text-center">
                        Actions
                    </th>
                </tr>
                </tfoot>
        </table>
        
    </div>
</template>
<script>

import CurrentSupply from "./CurrentSupply";
import Comments from "../../Comments";

export default({
    components: { CurrentSupply , Comments},
    name:"Supplies",
    data(){
        return {
            showModal: false,
            formErrors: {},
            loading: false,
            index: "",
           // supplies: []
        }
    },
    props: ["project_id" ,"supplies_statuses" , "vat_rates"],
    methods: {
        debug(item) {
            console.log(item);
        },
        update(supply) {

            this.loading = true;

            let formData = new FormData();
           
            for (let field in supply) {
                formData.append(field, supply[field] != null  ? supply[field] : "");
            }

            this.$http.post("supplies/"+supply.id+"?_method=PUT", formData, {} )
            .then((resp) => {
                new Noty({
                    type: "success",
                    text: "Achat mise à jour",
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
            if (confirm("Supprimer cette Achat ?")){
                this.$http.delete("supplies/" + this.supplies[index].id).then(() => {
                    this.supplies.splice(index, 1);

                    new Noty({
                        type: "success",
                        text: "Achat supprimé",
                    }).show();
                }).catch((err) => {
                    new Noty({
                        type: "error",
                        text: "Erreur lors de la suppression de l'achat",
                    }).show();
                });
            }
        },
        onAdded(event) {
                
            this.showModal = false;
            this.supplies.push({...event.added});
        
            new Noty({
                type: "success",
                text: "Achat ajouté",
            }).show();
               


        },
        showCommentsModal(supplyId) {
            this.$modal.show("comments_modal_" + supplyId);
        },
        hideModalComments() {
            this.$modal.hide("comments_modal");
        },
        changeIndex(newIndex){
            this.showModal = true;
            this.index = newIndex;
        }
    },
    computed : {
        supplies(){
            return this.$store.state.supply.supplies;
        },
    }
})
</script>
