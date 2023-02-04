<template>
    <v-app id="inspire">
        <v-main>
            <v-container class="fill-height custom-login-form" fluid>
                <div class="centered-form">
                    <!-- <v-row align="center" justify="center"> -->
                    <!-- <v-col cols="12" sm="12" md="12"> -->
                    <v-card class="elevation-12">
                        <div class="form-top-logo-wrapper">
                            <div class="form-top-logo"></div>
                        </div>
                        <!-- <v-toolbar color="primary" dark flat>
                                <v-toolbar-title>{{
                                    $t("login_title")
                                }}</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar> -->
                        <v-card-text>
                            <v-form
                                @submit.prevent="loginUser"
                                @keyup.native.enter="loginUser"
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
                                        ref="email"
                                        :label="$t('email')"
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
                                        !$v.email.required
                                            ? $t("required")
                                            : $t("not_valid_mail")
                                    }}
                                </span>
                                <div class="f-group">
                                    <label>
                                        {{ $t("password") }}
                                    </label>
                                    <input
                                        class="base-input-grey"
                                        type="password"
                                        ref="password"
                                        name="password"
                                        v-model="$v.password.$model"
                                    />
                                </div>

                                <!-- <v-text-field
                                        id="password"
                                        :label="$t('password')"
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
                                <div style="text-align:center">
                                    <button
                                        type="button"
                                        @keydown.enter.prevent="() => {}"
                                        @click.stop="
                                            () =>
                                                $router
                                                    .push({
                                                        name:
                                                            'ForgottenPassword'
                                                    })
                                                    .catch(() => {})
                                        "
                                        style="font-size:12px;z-index:999"
                                    >
                                        {{ $t("forgotten_password") }}
                                    </button>
                                </div>
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <!-- <v-spacer></v-spacer> -->
                            <button
                                type="submit"
                                class="button-main margin-auto"
                                @click="loginUser"
                            >
                                {{ $t("login") }}
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
import axios from "axios";
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
        },
        password: {
            required
        }
    },
    // lifecycle
    created() {
        document.title = this.$t("login_title");
        this.$vuetify.theme.dark = false;
    },
    methods: {
        loginUser() {
            this.$v.$touch();
            if (this.$v.$invalid) {
                return;
            } else {
                this.processing = true;
                return User.login(this.email, this.password)
                    .then(res => {
                        this.processing = false;
                        this.$router
                            .push({ name: "Dashboard" })
                            .catch(() => {});
                        // this.$snack("aha", "success");
                    })
                    .catch(err => {
                        this.processing = false;
                        this.email = "";
                        this.password = "";
                        this.$v.$reset();
                        app.snackbar(this.$t("invalid_login"));
                        this.$refs.email.focus();
                    });
            }
        },
        closeSnackBar() {
            this.snackbar = false;
        }
    }
};
</script>
