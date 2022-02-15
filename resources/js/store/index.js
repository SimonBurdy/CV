import Vue from "vue";
import Vuex from "vuex";
// import project from "./modules/project";
import quote from "./modules/quote";
import supply from "./modules/supply";


const store = new Vuex.Store({
    modules: {
        // project: project,
        quote : quote,
        supply : supply,
    }
})

export default store