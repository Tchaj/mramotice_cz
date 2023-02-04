<template>
    <v-app>
        <v-main>
            <template v-if="notBase.includes($route.name)">
                <router-view />
            </template>
            <template v-if="!notBase.includes($route.name)">
                <AdminLayout>
                    <router-view />
                </AdminLayout>
            </template>
            <v-snackbar v-model="snackbar">
                {{ snackText }}

                <template v-slot:action="{ attrs }">
                    <v-btn
                        :color="snackColor"
                        text
                        v-bind="attrs"
                        @click="snackbar = false"
                    >
                        <!--@closeSnackbar="snackbar = false"-->
                        {{ $t("close_arg") }}
                    </v-btn>
                </template>
            </v-snackbar>
        </v-main>
    </v-app>
</template>

<script>
import AdminLayout from "./components/AdminLayout";
import Bus from "../Admin/Config/Plugins/Bus";
export default {
    name: "App",

    components: {
        AdminLayout
    },

    data: () => ({
        notBase: ["Login", "ForgottenPassword", "ForgottenPasswordCode"],
        snackColor: "success",
        snackText: "",
        snackbar: false
    }),
    created() {
        this.$vuetify.theme.dark = false;
        Bus.$on("snackbar-activate", ({ color = "success", text }) => {
            this.snackbar = true;
            this.snackColor = color;
            this.snackText = text;
        });
    },
    methods: {},
    watch: {
        $route() {
            // return this.me();
        }
    }
};
</script>

<style>
.input--helper {
    font-size: 11px;
    display: inline-block;
    cursor: pointer;
    position: relative;
    top: -5px;
    border: 0;
    appearance: none;
}
.input--file {
    position: relative;
}
.heading--fix {
    margin-bottom: 30px;
}
.col--fix {
    margin-bottom: -20px;
}
.content--fix {
    padding-bottom: 60px;
    padding-top: 30px;
    margin-bottom: 50px;
    /* overflow-x: hidden; */
}
.margin-top--fix {
    margin-top: -19px;
}
.margin-top--30 {
    margin-top: 30px;
}
@media (min-width: 960px) {
    .container {
        max-width: 800px !important;
    }
}
@media (min-width: 992px) {
    .container {
        max-width: 800px !important;
    }
}
@media (min-width: 1264px) {
    .container {
        max-width: 85% !important;
    }
}
@media (min-width: 1904px) {
    .container {
        max-width: 1785px !important;
    }
}
</style>
