import Vue from "vue";
import Vuex from "vuex";
import http from "../../services/http"

Vue.use(Vuex)

const supply = {
    namespaced: true,
    state: {
        formErrors: {},
        supplies: [],
    

        
    },
    getters: {
        //computed 

    },
    mutations: {
        async loadSupplies(state, id) {
            state.supplies = await http.get("supplies", {
                params: {
                    project_id: id,
                }
            });
        }
    },
    actions: {
       // methods
    }

} 

export default supply;