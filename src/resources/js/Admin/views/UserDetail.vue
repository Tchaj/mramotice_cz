<template>
    <div class="content--fix">
        <div class="detail-list-wrapper">
            <div class="detail-list-col">
                <h1 class="page-title" style="margin-bottom:0 !important">
                    {{ $t("user_detail") }}
                </h1>
            </div>
            <div class="detail-list-col">
                <div class="inner-row">
                    <div class="inner-col mr-3">
                        <v-btn
                            color="error"
                            @click.prevent="
                                $service('dialogRemove', true, {
                                    title:
                                        'Opravdu si přejete odstranit profil uživatele ' +
                                        user.full_name +
                                        ' ?'
                                })
                            "
                        >
                            {{ $t("delete") }}
                        </v-btn>
                    </div>
                    <div class="inner-col mr-3">
                        <v-btn color="primary" @click="update">
                            {{ $t("save_arg") }}
                        </v-btn>
                    </div>
                    <div class="inner-col">
                        <v-btn
                            color="secondary"
                            @click.prevent="$router.push({ name: 'Users' })"
                        >{{ $t("back") }}</v-btn
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- users top -->
        <div class="users-top-wrapper">
            <div class="top-col mr-3">
                <img
                    src="https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_960_720.png"
                    alt=""
                />
            </div>
            <div class="top-col">
                <div class="text--primary-color">{{ user.full_name }}</div>
                <div class="mt-3">ID {{ user.id }}</div>
            </div>
        </div>

        <div class="form-row mt-5">
            <div class="form-col" v-if="user.customer">
                <div class="f-group">
                    <label>{{ $t("first_name") }}</label>
                    <input type="text" v-model="user.customer.first_name" />
                </div>
            </div>
            <div class="form-col">
                <div class="f-group">
                    <label>{{ $t("last_name") }}</label>
                    <input type="text" v-model="user.customer.last_name" />
                </div>
            </div>
        </div>

        <div class="form-row mt-5">
            <div class="form-col">
                <div class="f-group">
                    <label>{{ $t("email") }}</label>
                    <input type="text" disabled v-model="user.email" />
                </div>
            </div>
            <div class="form-col">
                <div class="f-group">
                    <label>{{ $t("user_phone_number") }}</label>
                    <input type="text" v-model="user.customer.phone_number" />
                </div>
            </div>
        </div>

        <v-row>
            <!-- <v-col cols="12">
                <v-row>
                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="user.email"
                            hide-details
                            disabled
                        ></v-text-field>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="12">
                <v-row>
                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="user.first_name"
                            hide-details
                            :label="$t('first_name')"
                        ></v-text-field>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="12">
                <v-row>
                    <v-col cols="12" md="4">
                        <v-text-field
                            v-model="user.last_name"
                            hide-details
                            :label="$t('last_name')"
                        ></v-text-field>
                    </v-col>
                </v-row>
            </v-col> -->
            <template
                v-if="
                    user.hasOwnProperty('customer') &&
                        user.customer != null &&
                        Object.keys(user.customer).length
                "
            >
                <!-- <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="user.customer.phone_number"
                                hide-details
                                :label="$t('user_phone_number')"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="user.customer.state"
                                hide-details
                                :label="$t('user_state')"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="user.customer.city"
                                hide-details
                                :label="$t('user_city')"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="user.customer.street"
                                hide-details
                                :label="$t('user_street')"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="user.customer.no"
                                hide-details
                                :label="$t('user_no')"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="user.customer.zip"
                                hide-details
                                :label="$t('user_zip')"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-col> -->
                <!-- orders -->
                <v-col cols="12" style="margin-top:20px">
                    <!-- <h2>{{ $t("orders_title") }}</h2> -->
                </v-col>
                <v-col cols="12" style="margin-top:20px">
                    <h4 class="text-right">
                        {{ $t("total_spent") }} {{ user.customer.spent }} Kč
                    </h4>
                </v-col>
                <v-col>
                    <div class="page-header-section-wrapper align-items-center">
                        <div class="header-section-col">
                            <div class="inner-row align-items-center">
                                <h1
                                    class="page-title"
                                    style="margin-bottom:0 !important"
                                >
                                    {{ $t("orders_title") }}
                                </h1>
                            </div>
                        </div>
                        <div class="header-section-col align-items-center">
                            <div
                                class="inner-row align-items-center justify-flex-end"
                            >
                                <input
                                    v-model="search"
                                    type="text"
                                    class="base-input base-input--filled mr-3"
                                    :placeholder="$t('search')"
                                    style="width:280px"
                                />
                                <button
                                    type="button"
                                    class="base-button base-button--primary-outlined"
                                >
                                    {{ $t("search") }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <v-data-table
                        :headers="headers"
                        :items="user.customer.orders"
                        :page="1"
                        :search="search"
                        :no-data-text="$t('no_data')"
                        :no-results-text="$t('no_matching')"
                        :footer-props="{
                            'items-per-page-text': $t('per_page')
                        }"
                        @click:row="handleClick($event)"
                    >
                        <template v-slot:item.created_at="{ item }">
                            <span>{{
                                    item.created_at | moment("D. M. Y")
                                }}</span>
                        </template>
                        <template v-slot:item.total_price="{ item }">
                            <span>{{ item.total_price }} Kč</span>
                        </template>
                        <template v-slot:item.edit="{ item }">
                            <v-btn
                                color="primary"
                                @click.stop="
                                    $router
                                        .push({
                                            name: 'Orders-detail',
                                            params: { orderId: item.id }
                                        })
                                        .catch(() => {})
                                "
                            >
                                {{ $t("show") }}
                                <!-- <v-icon color="primary">mdi-pencil</v-icon> -->
                            </v-btn>
                        </template>
                    </v-data-table>
                </v-col>
                <!-- delivery addresses -->
                <v-col cols="12" style="margin-top:40px">
                    <!-- <h2>{{ $t("delivery_addresses") }}</h2> -->
                    <div class="page-header-section-wrapper align-items-center">
                        <div class="header-section-col">
                            <div class="inner-row align-items-center">
                                <h1
                                    class="page-title"
                                    style="margin-bottom:0 !important"
                                >
                                    {{ $t("delivery_addresses") }}
                                </h1>
                            </div>
                        </div>
                        <div class="header-section-col align-items-center">
                            <div
                                class="inner-row align-items-center justify-flex-end"
                            >
                                <input
                                    v-model="search_delivery"
                                    type="text"
                                    class="base-input base-input--filled mr-3"
                                    :placeholder="$t('search')"
                                    style="width:280px"
                                />
                                <button
                                    type="button"
                                    class="base-button base-button--primary-outlined"
                                >
                                    {{ $t("search") }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <v-data-table
                        :headers="addresses_headers"
                        :items="user.customer.delivery_addresses"
                        :page="1"
                        :search="search_delivery"
                        :no-data-text="$t('no_data')"
                        :no-results-text="$t('no_matching')"
                        :footer-props="{
                            'items-per-page-text': $t('per_page')
                        }"
                    ></v-data-table>
                </v-col>

                <!-- invoice addresses -->
                <v-col cols="12" style="margin-top:40px">
                    <!-- <h2>{{ $t("invoice_addresses") }}</h2> -->
                    <div class="page-header-section-wrapper align-items-center">
                        <div class="header-section-col">
                            <div class="inner-row align-items-center">
                                <h1
                                    class="page-title"
                                    style="margin-bottom:0 !important"
                                >
                                    {{ $t("invoice_addresses") }}
                                </h1>
                            </div>
                        </div>
                        <div class="header-section-col align-items-center">
                            <div
                                class="inner-row align-items-center justify-flex-end"
                            >
                                <input
                                    v-model="search_invoice"
                                    type="text"
                                    class="base-input base-input--filled mr-3"
                                    :placeholder="$t('search')"
                                    style="width:280px"
                                />
                                <button
                                    type="button"
                                    class="base-button base-button--primary-outlined"
                                >
                                    {{ $t("search") }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <v-data-table
                        :headers="addresses_headers"
                        :items="user.customer.invoice_addresses"
                        :search="search_invoice"
                        :page="1"
                        :no-data-text="$t('no_data')"
                        :no-results-text="$t('no_matching')"
                        :footer-props="{
                            'items-per-page-text': $t('per_page')
                        }"
                    ></v-data-table>
                </v-col>
            </template>

            <!-- update - delete -->
            <v-col cols="12" md="12" class="margin-top--30">
                <v-row>
                    <v-col>
                        <v-btn
                            small
                            color="error"
                            @click.prevent="
                                $service('dialogRemove', true, {
                                    /**title,text... */
                                })
                            "
                        >
                            {{ $t("delete") }}
                        </v-btn>
                    </v-col>
                    <v-spacer />
                    <v-col class="text-right">
                        <v-btn color="primary" small @click="update">
                            {{ $t("save_arg") }}
                        </v-btn>
                        <v-btn
                            color="secondary"
                            small
                            @click.prevent="$router.push({ name: 'Users' })"
                        >{{ $t("back") }}</v-btn
                        >
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import UserServices from "../services/UserServices";
import Bus from "../Config/Plugins/Bus";

export default {
    data() {
        return {
            user: {},
            search: "",
            search_delivery: "",
            search_invoice: "",
            headers: [
                {
                    text: this.$t("created_at"),
                    align: "left",
                    sortable: true,
                    value: "created_at"
                },
                {
                    text: this.$t("orders_id"),
                    align: "left",
                    sortable: true,
                    value: "internal_id"
                },
                {
                    text: this.$t("order_status"),
                    align: "left",
                    sortable: true,
                    value: "status"
                },
                {
                    text: this.$t("order_payment_method"),
                    align: "left",
                    sortable: true,
                    value: "payment_type_name"
                },
                {
                    text: this.$t("orders_delivery"),
                    align: "left",
                    sortable: true,
                    value: "delivery_type_name"
                },
                {
                    text: this.$t("orders_total_price"),
                    align: "left",
                    sortable: true,
                    value: "total_price"
                },
                {
                    text: "",
                    align: "left",
                    sortable: false,
                    value: "edit"
                }
            ],
            addresses_headers: [
                {
                    text: this.$t("full_name"),
                    align: "left",
                    sortable: true,
                    value: "full_name"
                },
                {
                    text: this.$t("user_phone_number"),
                    align: "left",
                    sortable: true,
                    value: "phone_number"
                },
                {
                    text: "E-mail",
                    align: "left",
                    sortable: true,
                    value: "email"
                },
                {
                    text: this.$t("user_state"),
                    align: "left",
                    sortable: true,
                    value: "state"
                },
                {
                    text: this.$t("user_city"),
                    align: "left",
                    sortable: true,
                    value: "city"
                },
                {
                    text: this.$t("user_full_address"),
                    align: "left",
                    sortable: true,
                    value: "full_address"
                }
            ]
        };
    },
    beforeDestroy() {
        // this.$bus.$off();
        Bus.$off("remove-cb");
    },
    async created() {
        await this.getDetail();

        await Bus.$on("remove-cb", async () => {
            try {
                this.$service("processing", true);
                const response = await UserServices.deleteUser(
                    this.$route.params.userId
                );
                this.$service("processing", false);
                this.$service("dialogRemove", false);
                this.$service("snackbar", this.$t("deleted"), "success");
                this.$router.push({ name: "Users" }).catch(() => {});
                return;
            } catch (e) {
                console.debug(e);
                this.$service("processing", false);
                this.$service("dialogRemove", false);
                this.$service("snackbar", this.$t("error"), "warning");
                return;
            }
        });
    },
    methods: {
        /**get current user detail */
        async getDetail() {
            try {
                this.$service("processing", true);
                const user = await UserServices.getUser(
                    this.$route.params.userId
                );
                this.user = user;
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        async update() {
            try {
                this.$service("processing", true);
                const r = await UserServices.patchUser(
                    this.$route.params.userId,
                    { ...this.user, ...this.user.customer }
                );
                this.$service("processing", false);
                this.$service("snackbar", this.$t("saved"), "success");
                this.$router.push({ name: "Users" }).catch(() => {});
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warninng");
            }
        }
    }
};
</script>

<style></style>
