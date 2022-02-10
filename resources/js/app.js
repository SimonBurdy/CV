import Vue from 'vue'

import store from "./store";

import _ from "lodash";
window._ = _ ;

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)


Vue.mixin({
    methods: {
        /**
         * Arrondi propre
         * @param value
         * @param decimals
         * @returns {number}
         */
        round(value, decimals) {
            if(!value) {
                value = 0;
            }

            if(!decimals) {
                decimals = 2;
            }

            value = Math.round(value * Math.pow(10, decimals)) / Math.pow(10, decimals);
            return value;
        }
    }
})



//--------------------------------
// Axios et http service
//--------------------------------
import http from './services/http'
Vue.prototype.$http = http





import Agenda from "./components/Agenda";
import Contact from "./components/contact";

import Clients from "./components/clientBlock/Clients";
import Client from "./components/clientBlock/Client";

import Curiculum from "./components/Curiculum";
import CustomFooter from "./components/CustomFooter";
import Navbar from "./components/Navbar";

import Projects from "./components/projectBlock/Projects";
import Project from "./components/projectBlock/Project";

import Experiences from "./components/experienceBlock/Experiences";
import Experience from "./components/experienceBlock/Experience";

import Formation from "./components/formationBlock/Formation";
import Formations from "./components/formationBlock/Formations";

import AddAddress from "./components/addressBlock/AddAddress";
import Addresses from "./components/addressBlock/Addresses";

import Quotes from "./components/billingBlock/Quotes";
import CurrentQuote from "./components/billingBlock/CurrentQuote";
import AddQuoteRow from "./components/billingBlock/AddQuoteRow";
import ServiceRow from "./components/billingBlock/ServiceRow";
import CurrentAddress from "./components/billingBlock/CurrentAddress";
import AddressTemplate from "./components/billingBlock/AddressTemplate";

import Comments from  "./components/Comments";

const components = {
    'agenda': Agenda,
    'contact': Contact,
    'curiculum': Curiculum,
    'customFooter': CustomFooter,
    'navbar': Navbar,
    'projects': Projects,
    'project': Project,
    'experiences': Experiences,
    'experience': Experience,
    'formation': Formation,
    'formations': Formations,
    'clients' : Clients,
    'client' : Client,
    'add-address' : AddAddress,
    'addresses' : Addresses,
    'quotes' : Quotes,
    'current-quote' : CurrentQuote,
    'add-quote-row' :AddQuoteRow,
    'service-row' : ServiceRow,
    'current-address' : CurrentAddress,
    'comments' : Comments,
    'address-template':AddressTemplate

    
    
};

Object.keys(components).forEach(selector => {
    Vue.component(selector, components[selector]);
    //[].forEach.call(document.querySelectorAll(selector), el => new Vue({ name: `${selector}Root`, el }));
});
new Vue({
    name: "vueappRoot",
    store
})
.$mount('#vueapp')


