import Vue from "vue";
import Vuex from "vuex";
import ShopService from "./services/WebService";
Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        web: {}
    },
    /** */
    mutations: {
        setWeb(state, payload) {
            return (state.web = payload);
        }
    },
    /** */
    actions: {
        async setShop(context) {
            try {
                const payload = await WebService.detail();
                context.commit("setWeb", payload);
            } catch (e) {
                console.debug(e);
            }
        }
    },
    /** */
    getters: {
        getWeb(state) {
            return state.web;
        }
    }
});
