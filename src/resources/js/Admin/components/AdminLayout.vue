<template>
    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            :color="'secondary'"
            :expand-on-hover="isPernament"
            :mini-variant.sync="drawer"
            :right="false"
            :permanent="isPernament"
            :temporary="!isPernament"
            fixed
            light
            :mini-variant-width="drawerWidth"
            @update:mini-variant="drawerObserver"
        >
            <v-list dense nav class="py-0">
                <!-- <v-list-item two-line :class="drawer && 'px-0'"> -->
                <v-list-item two-line class="">
                    <v-list-item-content>
                        <v-list-item-title
                        ><div class="navigation_logo"></div
                        ></v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item link :to="{ name: 'Dashboard' }">
                    <v-list-item-action>
                        <v-icon class="dashboard-icon"></v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ $t("home") }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item
                    link
                    :to="{ name: 'Blog' }"
                    v-if="web.hasOwnProperty('blog') && web.blog == 1"
                >
                    <v-list-item-action>
                        <v-icon class="blog-icon"></v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Zajímavé sekce + Blog</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link :to="{ name: 'Cms' }">
                    <v-list-item-action>
                        <v-icon class="cms-icon"></v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Statické stránky</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link :to="'/users'">
                    <v-list-item-action>
                        <v-icon class="users-icon"></v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{
                                $t("nav_users")
                            }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-action :class="{ hidden_copy: drawer }">
                        <a href="https://martinhoracek.cz">Martin Horáček</a>
                    </v-list-item-action>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <div
            class="top-bar-header"
            :class="{ 'top-bar-header--scrolled': navScrolled }"
        >
            <div class="top-bar-header-inner-wrapper">
                <v-app-bar-nav-icon
                    class="sm-navigation-drawer"
                    @click.stop="drawer = !drawer"
                ></v-app-bar-nav-icon>
                <span style="position:relative;right:8px;color:grey">{{
                        user.email
                    }}</span>
                <button class="button-main button-main--small" @click="logout">
                    Odhlásit
                </button>
            </div>
        </div>

        <v-main style="padding-top:0;padding-left:0">
            <v-container class="container-content-slot">
                <Spinner :show="loading" />
                <slot></slot>
                <keep-alive>
                    <DeleteDialog :dialog="dialog" />
                </keep-alive>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import Spinner from "./Spinner";
import UserServices from "../services/UserServices";
import Bus from "../Config/Plugins/Bus";
import DeleteDialog from "../components/DeleteDialog";
export default {
    props: {
        source: String
    },
    components: {
        // SnackBar,
        Spinner,
        DeleteDialog
    },
    data: () => ({
        drawer: null,
        snackbar: false,
        color: "",
        snackbarText: "",
        loading: false,
        dialog: false,
        user: {},
        mini: true,
        navScrolled: false,
        isPernament: false,
        drawerWidth: 70
    }),

    async created() {
        // this.$vuetify.theme.dark = true;
        this.$vuetify.theme.dark = false;
        this.profile();

        Bus.$on("processing", state => {
            this.loading = state;
        });

        Bus.$on("dialog-remove", (state = false, data = {}) => {
            this.dialog = state;
        });
        await this.$store.dispatch("setWeb");
    },
    mounted() {
        window.addEventListener("scroll", this.handleScroll);
        window.addEventListener("resize", this.setNavigationPernament);
        this.setNavigationPernament();
    },
    destroyed() {
        window.removeEventListener("scroll", this.handleScroll);
        window.removeEventListener("resize", this.setNavigationPernament);
        window.removeEventListener(
            "resize",
            this.setNavigationPernamentOnResize
        );
    },
    methods: {
        setNavigationPernament() {
            let width = window.innerWidth;
            if (width > 768) {
                this.isPernament = true;
                this.drawerWidth = 70;
                return;
            }
            this.isPernament = false;
            this.drawerWidth = 220;
            return;
        },
        profile() {
            UserServices.currentUser()
                .then(res => {
                    this.user = res.data;
                })
                .catch(e => {
                    console.error("Not authorized");
                    UserServices.logout().then(r => {
                        // this.$router
                        //     .push({ name: "Login" })
                        //     .then()
                        //     .catch(e => {
                        //     });
                    });
                });
        },
        logout() {
            UserServices.logout()
                .then(res => {
                    this.$router.push({ name: "Login" }).catch(() => {});
                })
                .catch(e => {
                    console.error("e", e);
                });
        },
        closeSnackBar() {
            this.snackbar = false;
        },
        handleScroll(e) {
            let scrolled = window.scrollY;
            if (scrolled > 90) {
                this.navScrolled = true;
                return;
            }
            this.navScrolled = false;
            return;
        },
        drawerObserver(e) {
            // console.log("e", e);
        }
    },
    computed: {
        /**web info */
        web() {
            return this.$store.state.web;
        }
    }
};
</script>
<style scoped>
.logo {
    /* background: url("../assets/logo.svg"); */
    background-repeat: no-repeat;
    background-size: contain;
    width: 170px;
    height: 30px;
}
.v-toolbar__content {
    background: white !important;
}
.v-app-bar__nav-icon.v-btn.v-btn--flat.v-btn--icon.v-btn--round.theme--dark.v-size--default {
    color: black !important;
}

@media (min-width: 992px) {
    .container {
        max-width: 800px !important;
    }
}

@media (min-width: 1264px) {
    .container {
        /* max-width: 95% !important; */
        max-width: 85% !important;
    }
}
@media (min-width: 1904px) {
    .container {
        max-width: 1785px !important;
    }
}
</style>
