import Vue from 'vue'
import store from "./store";
import App from './components/App.vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import { createApp } from 'vue' ;

import Navbar from './components/Navbar.vue';
import CustomFooter from './components/CustomFooter.vue';
import Curiculum from './components/Curiculum.vue';
import Timeline from './components/Timeline.vue';
import TimelineFormation from './components/TimelineFormation.vue';
import Projects from './components/Projects.vue';
import Contact from './components/Contact.vue';
import Agenda from './components/Agenda.vue';




const components = {
    App: App,
    Navbar : Navbar,
    BootstrapVue: BootstrapVue,
    IconsPlugin : IconsPlugin,
    CustomFooter: CustomFooter,
    Curiculum :Curiculum,
    Timeline:Timeline,
    TimelineFormation:TimelineFormation,
    Projects:Projects,
    Contact:Contact,
    Agenda:Agenda,

}

Object.keys(components).forEach(selector => {
    Vue.component(selector, components[selector]);
    //[].forEach.call(document.querySelectorAll(selector), el => new Vue({ name: `${selector}Root`, el }));
});
new Vue({
    name: "App",
    store
})
.$mount('#App')
