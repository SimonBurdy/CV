import Vue from 'vue'

import store from "./store";

import _ from "lodash";
window._ = _ ;


import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Import Bootstrap an BootstrapVue CSS files (order is important)
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
// CKEditor
//--------------------------------
import CKEditor from '@ckeditor/ckeditor5-vue2';
Vue.use( CKEditor );




//--------------------------------
// Axios et http service
//--------------------------------
import http from './services/http'
Vue.prototype.$http = http;

import VModal from 'vue-js-modal'
Vue.use(VModal);


import Autocomplete from 'v-autocomplete';
//You need a specific loader for CSS files like https://github.com/webpack/css-loader
import 'v-autocomplete/dist/v-autocomplete.css'


import Agenda from "./components/Agenda";
import Contact from "./components/Contact";

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

import Quotes from "./components/billingBlock/quote/Quotes";
import CurrentQuote from "./components/billingBlock/quote/CurrentQuote";
import AddQuoteRow from "./components/billingBlock/quote/AddQuoteRow";
import ServiceRow from "./components/billingBlock/quote/ServiceRow";
import CurrentAddress from "./components/billingBlock/quote/CurrentAddress";
import AddressTemplate from "./components/billingBlock/quote/AddressTemplate";
import ItemTemplate from "./components/billingBlock/quote/ItemTemplate";
import Supplies from "./components/billingBlock/supply/Supplies";
import CurrentSupply from "./components/billingBlock/supply/CurrentSupply";
import Config from  "./plugins/config";

import Comments from  "./components/Comments";

const components = {
    'agenda': Agenda,
    'v-autocomplete': Autocomplete,
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
    'address-template':AddressTemplate,
    'item-template':ItemTemplate,
    'supplies' : Supplies,
    'current-supply' : CurrentSupply

    
    
};

Vue.use(Config)

Object.keys(components).forEach(selector => {
    Vue.component(selector, components[selector]);
    //[].forEach.call(document.querySelectorAll(selector), el => new Vue({ name: `${selector}Root`, el }));
});
new Vue({
    name: "vueappRoot",
    store
})
.$mount('#vueapp')


