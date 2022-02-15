import Vue from "vue";
import Vuex from "vuex";
import http from "../../services/http"

Vue.use(Vuex)

const project = {
    namespaced: true,
    state: {
        currentProject: '',
        currentProjectBats: '',
        globalShippingFees: '',
        propalIsDirty: false,
        batsIsDirty: false
    },
    getters: {
        projectIsDirty: state => {
            return (state.propalIsDirty || state.batsIsDirty)
        }
    },
    mutations: {
        async loadProject(state, id) {
            state.currentProject = await http.get("project/" + id);
        }
    },
    actions: {
        async saveProjectDependencies({
            state
        }) {


            if (state.propalIsDirty) {
                await http.post("propal", {
                    project_id: state.currentProject.id,
                    propal: state.currentProject.propal
                })
                state.propalIsDirty = false
            }


            if (state.batsIsDirty) {
                await http.post("bats", {
                    bats: state.currentProjectBats
                })
                state.batsIsDirty = false
            }

        }
    }
}

export default project
