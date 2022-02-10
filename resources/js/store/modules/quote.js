import Vue from "vue";
import Vuex from "vuex";


Vue.use(Vuex)

const quote = {
    namespaced: true,
    state: {
        id : '',
        mode: '',
        address_id : '',
        agefiph_total: '',
        client: {},
        created_at: '',
        unity: '',
        vat_rate: '',
        discount_euro: '',
        discount_unit: '',
        notes : '',
        number : '',
        rows : [],
        sell_total: '',
        status:'',
        updated_at: '',
        creation_date: '',
        validity_date: '',
        quoteFormErrors : {},
    

        
    },
    getters: {
        //computed 

    },
    mutations:{
        //setteurs
    },
    actions: {
       // methods
    }

} 

export default quote;