<template>
    <v-app id="inspire">
        <v-main>
            <v-container class="fill-height custom-login-form" fluid>
                <div class="centered-form">
                    <!-- <v-row align="center" justify="center">
                    <v-col cols="12" sm="8" md="4"> -->
                    <v-card class="elevation-12">
                        <div class="form-top-logo-wrapper">
                            <div class="form-top-logo"></div>
                        </div>
                        <!-- <v-toolbar color="primary" dark flat>
                            <v-toolbar-title>Změna hesla</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar> -->
                        <v-card-text>
                            <v-form
                                @submit.prevent="loginUser"
                                @keyup.native.enter="forgottenPassword"
                            >
                                <Spinner v-if="processing" />
                                <div class="f-group">
                                    <label>
                                        E-mail
                                    </label>
                                    <input
                                        class="base-input-grey"
                                        type="text"
                                        ref="email"
                                        name="login"
                                        v-model="$v.email.$model"
                                    />
                                </div>
                                <!-- <v-text-field
                                    label="E-mail"
                                    name="login"
                                    prepend-icon="mdi-account"
                                    type="text"
                                    v-model="$v.email.$model"
                                ></v-text-field> -->
                                <span
                                    class="error-input"
                                    :class="{
                                        'error-input--active':
                                            $v.email.$anyError
                                    }"
                                >
                                    {{
                                        !$v.email.$email
                                            ? "Toto není platný e-mail"
                                            : "Toto pole je povinné"
                                    }}
                                </span>
                                <div style="text-align:center;z-index:999">
                                    <button
                                        type="button"
                                        @keydown.enter.prevent="() => {}"
                                        @click.stop="
                                            () =>
                                                $router.push({
                                                    name: 'Login'
                                                })
                                        "
                                        style="font-size:12px;"
                                    >
                                        Přihlásit se
                                    </button>
                                </div>
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <!-- <v-spacer></v-spacer> -->
                            <button
                                class="button-main margin-auto"
                                type="submit"
                                @click="forgottenPassword"
                            >
                                Reset
                            </button>
                        </v-card-actions>
                    </v-card>
                    <!-- </v-col>
                </v-row> -->
                </div>
            </v-container>
        </v-main>
        <!-- <SnackBar
            :text="snackbarText"
            :snackbar="snackbar"
            :color="'error'"
            @click="closeSnackBar"
        /> -->
    </v-app>
</template>

<script>
import Spinner from "../components/Spinner";
import { required, sameAs, email } from "vuelidate/lib/validators";
import User from "../services/UserServices";
import app from "../services/AppService";
export default {
    components: {
        Spinner
    },
    props: {},
    data() {
        return {
            email: "",
            password: "",
            snackbarText: "",
            snackbar: false,
            processing: false
        };
    },
    validations: {
        email: {
            required,
            email
        }
    },
    // lifecycle
    created() {
        this.$vuetify.theme.dark = false;
    },
    methods: {
        forgottenPassword() {
            this.$v.$touch();
            if (this.$v.$invalid) {
                return;
            } else {
                this.processing = true;
                return User.forgottenPassword(this.email)
                    .then(res => {
                        this.processing = false;
                        app.snackbar("Zkontrolujte si e-mail", "success");
                        this.$router.push({ name: "Login" });
                    })
                    .catch(err => {
                        this.processing = false;
                        if (err.response) {
                            if (err.response.data.hasOwnProperty("status")) {
                                switch (err.response.data.status) {
                                    case "UserNotFound":
                                        app.snackbar(
                                            "Uživatel nenalezen",
                                            "error"
                                        );
                                        break;
                                    default:
                                        app.snackbar("Server error", "error");
                                }
                            }
                        }
                    });
            }
        },
        loginUser() {
            this.processing = true;
        }
    }
};
</script>
