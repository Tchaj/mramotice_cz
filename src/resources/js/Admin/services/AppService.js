import Bus from "../Config/Plugins/Bus";

const obj = {
    /** base snackbar
     *
     * allowed colors -> success, info, warning, error
     */
    snackbar: (text = "", color = "success") => {
        Bus.$emit("snackbar-activate", {
            text: text,
            color: color
        });
    },
    push: ($router, name = "index", ...args) => {
        let allowedArgs = ["query", "params"];
        return $router
            .push({
                name: name
            })
            .catch(err => {
                if (
                    err.name !== "NavigationDuplicated" &&
                    !err.message.includes(
                        "Avoided redundant navigation to current location"
                    )
                ) {
                    console.log(err);
                }
            });
    },
    /** global spinner loader */
    processing: (state = true) => {
        Bus.$emit("processing", state);
        return;
    },
    /** set view title */
    title: (title = process.env.VUE_APP_APP_NAME) => {
        if (typeof document == "undefined") return process.env.VUE_APP_NAME;
        document.title = title;
        return;
    },
    /** global remove dialog */
    dialogRemove: async (state, args = {}) => {
        console.log("args", args);
        await Bus.$emit("dialog-remove", state, { ...args });
        return;
    }
};

export default obj;
