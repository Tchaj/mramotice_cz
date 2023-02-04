<template>
    <div class="content--fix">
        <div class="page-header-section-wrapper align-items-center">
            <div class="header-section-col">
                <div class="inner-row align-items-center">
                    <h1 class="page-title" style="margin-bottom:0 !important">
                        {{ $t("users") }}
                    </h1>
                    <!-- <button
                        class="base-add-button ml-2"
                        @click="
                            $router.push({ name: 'Items-new' }).catch(() => {})
                        "
                    ></button> -->
                </div>
            </div>
            <div class="header-section-col align-items-center">
                <div class="inner-row align-items-center justify-flex-end">
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
            :items="users"
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
                <span>{{ item.created_at | moment("D. M. Y") }}</span>
            </template>
            <template v-slot:item.edit="{ item }">
                <v-btn
                    color="primary"
                    small
                    @click.stop="
                        $router
                            .push({
                                name: 'Users-detail',
                                params: { userId: item.id }
                            })
                            .catch(() => {})
                    "
                >
                    detail
                </v-btn>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import UserServices from "../services/UserServices";

export default {
    created() {
        this.$service("title", this.$t("users"));

        this.getUsers();
    },
    data() {
        return {
            users: [],
            search: "",
            headers: [
                {
                    text: "ID",
                    align: "left",
                    sortable: true,
                    value: "id"
                },
                {
                    text: this.$t("first_name"),
                    align: "left",
                    sortable: true,
                    value: "first_name"
                },
                {
                    text: this.$t("last_name"),
                    align: "left",
                    sortable: true,
                    value: "last_name"
                },
                {
                    text: "E-mail",
                    align: "left",
                    sortable: true,
                    value: "email"
                },
                {
                    text: this.$t("created_at"),
                    align: "left",
                    sortable: true,
                    value: "created_at"
                },
                {
                    text: this.$t("user_orders"),
                    align: "left",
                    sortable: true,
                    value: "orders"
                },
                {
                    text: "",
                    align: "right",
                    sortable: false,
                    value: "edit"
                }
            ]
        };
    },
    methods: {
        getUsers() {
            this.$service("processing", true);
            UserServices.getUsers()
                .then(res => {
                    this.$service("processing", false);
                    this.users = res.list;
                })
                .catch(err => {
                    console.log(err);
                    this.$service("processing", false);
                    this.$snack(this.$t("error"), "warning");
                });
        },
        handleClick(e) {
            this.$router
                .push({
                    name: "Users-detail",
                    params: { userId: e.id }
                })
                .catch(() => {});
        }
    }
};
</script>

<style></style>
