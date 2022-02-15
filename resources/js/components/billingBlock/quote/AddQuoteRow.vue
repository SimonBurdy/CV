<template>

    <div class="container-fluid  article-row  ml-1 mt-3 mb-3 pb-2 pt-2">

        <div class="row justify-content-start">
            <div class="col-auto">
                <div class="row">
                    <button
                        class="btn btn-sm btn-danger"
                        type="button"
                        @click="removeQuoteRow"
                    >
                    <i class="las la-trash"></i>
                    </button>

                </div>
            </div>

            <div class="handle  col-auto ml-3 mb-1">
                    <div class="row bg-white">
                        <button
                            class="btn btn-sm btn-outline-info"
                            type="button"
                        >
                       <i class="las la-arrows-alt"></i>
                        </button>
                    </div>
            </div>
        </div>
        <div>
            <service-row
            v-if="row.article_type == 'App\\Models\\Service'"
            :row_index="row_index"
            :v_id="v_id"
            >
            </service-row>
        </div>
    </div>
</template>

<script >

export default({
    name:"AddQuoteRow",
    props: ['quote_id','row_index','v_id','row'],
    data() {
        return {
            newRow:{
                currentRow : '',
                v_id:this.v_id,
                order: '',
            }
        }
    },
    mounted(){

       // this.$store.state.quote.rows.push(this.row);
        this.$store.state.quote.rows[this.row_index].v_id = this.v_id;
        this.order = this.row_index;
        this.$store.state.quote.rows[this.row_index].order = this.order;


    },
    methods : {
        /**
         * Supprime une row
         */
        removeQuoteRow(){
                if (confirm("Supprimer cette ligne ?")) {
                    if(this.newRow.id){
                        var url = ""

                        if(this.isQuote){
                            url = 'quotes/quote-row/'+this.newRow.id+'/delete'
                        }else if(this.isInvoice){
                            url = 'invoices/invoice-row/'+this.newRow.id+'/delete'
                        }

                        this.$http.delete(url).then((resp) => {
                             new Noty({
                                type: "success",
                                text: "Ligne produit supprimé",
                            }).show();
                        }).catch((err)=> {
                            new Noty({
                                type: "error",
                                text: "Erreur lors de la suppression",
                            }).show();
                        })
                    }

                    this.$emit("removed", this.row_index);
                }
        },
    },
    watch: {
       row: {
            deep: true,
            handler(newVal){

                        if(newVal.article){
                            this.$store.state.quote.rows[this.row_index].article_id = newVal.article.id;
                        }

                        if(newVal.quantity && newVal.unit_price ){
                            if( newVal.discount_unit &&  newVal.discount_value ){
                                    if(newVal.discount_unit == "euros"){
                                        this.$store.state.quote.rows[this.row_index].discount_euro =  newVal.discount_value  || 0 ;
                                    }else if(newVal.discount_unit == "pc"){
                                        this.$store.state.quote.rows[this.row_index].discount_euro =  newVal.quantity  * newVal.unit_price  * (newVal.discount_value/100);
                                    }
                            }



                            this.$store.state.quote.rows[this.row_index].sell_total = newVal.quantity  * newVal.unit_price  -   newVal.discount_euro  ||  0;


                        }

            },
     }
    },


})
</script>
<style scoped>

.article-row {
    border: 1px solid  gray;
    border-radius: 20px;
    box-shadow: 5px 2px 2px lightgray;
}
::placeholder {
  color: rgb(163, 157, 157);
}

.handle{
        cursor: grabbing;
}

.input-group-prepend{
    color: black !important;
}
</style>
