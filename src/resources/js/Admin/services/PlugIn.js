import AppService from "./AppService";
let AppServices;

AppServices.install = (Vue, opts) => {
    Vue.prototype.$snack = (text = "", color = "success") => {
        return AppService.snackbar(text, color);
    };
};

export default AppServices;
