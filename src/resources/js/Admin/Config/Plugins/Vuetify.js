import Vue from "vue";
import Vuetify from "vuetify/lib";

Vue.use(Vuetify);

export default new Vuetify({
    theme: {
        themes: {
            dark: {
                primary: "#f69c25",
                secondary: "#2c2d2d",
                error: "#a51e22"
            },
            light: {
                primary: "#f69c25",
                secondary: "#2c2d2d",
                error: "#a51e22"
            }
        }
    }
});
