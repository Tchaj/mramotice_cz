<template>
    <v-app id="inspire">
        <v-main>
            <v-container class="fill-height custom-login-form" fluid>
                <!-- <v-row align="center" justify="center">
                    <v-col cols="12" sm="8" md="4"> -->
                <div class="centered-form">
                    <v-card class="elevation-12">
                        <!-- <v-toolbar color="primary" dark flat>
                            <v-toolbar-title>Změna hesla</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar> -->
                        <div class="form-top-logo-wrapper">
                            <div class="form-top-logo"></div>
                        </div>
                        <v-card-text>
                            <v-form
                                @submit.prevent="forgottenPasswordCode"
                                @keyup.native.enter="forgottenPasswordCode"
                            >
                                <Spinner v-if="processing" />

                                <div class="f-group">
                                    <label>
                                        {{ $t("pass") }}
                                    </label>
                                    <input
                                        class="base-input-grey"
                                        type="password"
                                        ref="password"
                                        name="login"
                                        v-model="$v.password.$model"
                                    />
                                </div>
                                <!-- <v-text-field
                                    id="password"
                                    :label="$t('pass')"
                                    name="password"
                                    prepend-icon="mdi-lock"
                                    type="password"
                                    v-model="$v.password.$model"
                                ></v-text-field> -->
                                <span
                                    class="error-input"
                                    :class="{
                                        'error-input--active':
                                            $v.password.$anyError
                                    }"
                                >
                                    {{
                                        !$v.password.required
                                            ? $t("required")
                                            : ""
                                    }}
                                </span>

                                <div class="f-group">
                                    <label>
                                        {{ $t("pass_again") }}
                                    </label>
                                    <input
                                        class="base-input-grey"
                                        type="password"
                                        ref="password"
                                        name="login"
                                        v-model="$v.passwordAgain.$model"
                                    />
                                </div>
                                <!-- <v-text-field
                                    id="password"
                                    :label="$t('pass_again')"
                                    name="password"
                                    prepend-icon="mdi-lock"
                                    type="password"
                                    v-model="$v.passwordAgain.$model"
                                ></v-text-field> -->
                                <span
                                    class="error-input"
                                    :class="{
                                        'error-input--active':
                                            $v.passwordAgain.$anyError
                                    }"
                                >
                                    {{
                                        !$v.passwordAgain.required
                                            ? $t("required")
                                            : $t("pass_not_same")
                                    }}
                                </span>
                                <div style="text-align:center">
                                    <button
                                        type="button"
                                        @keydown.enter.prevent="() => {}"
                                        @click.prevent="
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
                                type="submit"
                                class="button-main margin-auto"
                                @click="forgottenPasswordCode"
                            >
                                Změnit
                            </button>
                        </v-card-actions>
                    </v-card>
                    <!-- </v-col>
                </v-row> -->
                </div>
            </v-container>
        </v-main>
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
            passwordAgain: "",
            snackbarText: "",
            snackbar: false,
            processing: false
        };
    },
    validations: {
        password: {
            required
        },
        passwordAgain: {
            required,
            sameAs: sameAs("password")
        }
    },
    // lifecycle
    created() {
        this.$vuetify.theme.dark = false;
        this.checkForgottenPasswordCode();
    },
    methods: {
        checkForgottenPasswordCode() {
            this.processing = true;
            return User.checkForgottenPasswordCode(this.$route.params.token)
                .then(res => {
                    this.processing = false;
                })
                .catch(err => {
                    this.processing = false;
                    app.snackbar("Neplatný kód, zažádejte si o nový", "error");
                    this.$router.push({ name: "ForgottenPassword" });
                });
        },
        forgottenPasswordCode() {
            this.$v.$touch();
            this.processing = true;
            if (this.$v.$invalid) {
                this.processing = false;
                return;
            }
            return User.forgottenPasswordCode(
                this.$route.params.code,
                this.password
            )
                .then(res => {
                    this.processing = true;
                    app.snackbar("Vaše heslo bylo změněno", "success");
                    this.$router.push({ name: "Login" });
                })
                .catch(err => {
                    this.processing = false;
                    app.snackbar("Server error", "error");
                });
        },
        loginUser() {
            this.processing = true;
        }
    }
};
</script>
