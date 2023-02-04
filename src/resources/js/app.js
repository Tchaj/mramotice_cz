require("./bootstrap");

window.Vue = require("vue").default;
import vuetify from "./Admin/Config/Plugins/Vuetify";
import router from "./Admin/Routes";
import store from "./Admin/Store";
Vue.component("Admin", require("./Admin/App.vue").default);
import Vuelidate from "vuelidate";
import AppService from "./Admin/services/AppService";
import VueI18n from "vue-i18n";
import { TiptapVuetifyPlugin } from "tiptap-vuetify";
import "tiptap-vuetify/dist/main.css";
import "vuetify/dist/vuetify.min.css";

import Vuetify from "vuetify";

import strings from "./Strings";
import ErrorInput from "./Admin/components/ErrorInput.vue";

Vue.prototype.$snack = (text = "", color = "success") => {
    return AppService.snackbar(text, color);
};
/** register base app services */
Vue.prototype.$service = (fn, ...args) => {
    if (
        typeof AppService[fn] !== "undefined" &&
        typeof AppService[fn] == "function"
    ) {
        return AppService[fn](...args);
    }
};

Vue.component("Err", ErrorInput);

Vue.use(Vuelidate);
Vue.use(require("vue-moment"));
Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: "cs",
    fallbackLocale: "cs",
    messages: { ...strings }
});

Vue.use(TiptapVuetifyPlugin, {
    vuetify: new Vuetify(),
    iconsGroup: "mdi"
});

const app = new Vue({
    router,
    store,
    vuetify,
    i18n,
    el: "#app"
}).$mount("#app");
