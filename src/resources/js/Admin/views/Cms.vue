<template>
    <div class="content--fix">
        <div class="page-header-section-wrapper align-items-center">
            <div class="header-section-col">
                <div class="inner-row align-items-center">
                    <h1 class="page-title" style="margin-bottom:0 !important">
                        {{ $t("cms_title") }}
                    </h1>
                    <button
                        class="base-add-button ml-2"
                        @click="$router.push({ name: 'Cms-new' })"
                    ></button>
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

        <!-- <h1 class="heading--fix">{{ $t("cms_title") }}</h1>
        <v-btn color="primary" @click="$router.push({ name: 'Cms-new' })">{{
            $t("cms_new_page")
        }}</v-btn>
        <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            :label="$t('search')"
            single-line
            hide-details
        ></v-text-field> -->
        <v-data-table
            :headers="headers"
            :items="pages"
            :page="1"
            :search="search"
            :no-data-text="$t('no_data')"
            :no-results-text="$t('no_matching')"
            :footer-props="{
                'items-per-page-text': $t('per_page')
            }"
            @click:row="handleClick($event)"
        >
            <template v-slot:item.published="{ item }">
                <span>{{ item.published ? $t("yes_arg") : $t("no_arg") }}</span>
            </template>
            <template v-slot:item.created_at="{ item }">
                <span>{{ item.created_at | moment("D. M. Y") }}</span>
            </template>
            <template v-slot:item.updated_at="{ item }">
                <span>{{ item.updated_at | moment("D. M. Y") }}</span>
            </template>
            <template v-slot:item.edit="{ item }">
                <v-btn
                    color="primary"
                    small
                    @click.stop="
                        $router
                            .push({
                                name: 'Cms-detail',
                                params: { cmsId: item.id }
                            })
                            .catch(() => {})
                    "
                >
                    {{ $t("edit") }}
                    <!-- <v-icon color="primary">mdi-pencil</v-icon> -->
                </v-btn>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import CmsService from "../services/CmsService";

export default {
    async created() {
        this.$service("title", this.$t("subpages"));
        await this.getSubPages();
    },
    data() {
        return {
            pages: [],
            search: "",
            headers: [
                // {
                //     text: this.$t("first_name"),
                //     align: "left",
                //     sortable: true,
                //     value: "page_name"
                // },
                {
                    text: "Slug",
                    align: "left",
                    sortable: true,
                    value: "slug"
                },
                // {
                //     text: this.$t("blog_published"),
                //     align: "left",
                //     sortable: true,
                //     value: "published"
                // },
                // {
                //     text: this.$t("created_at"),
                //     align: "left",
                //     sortable: true,
                //     value: "created_at"
                // },
                // {
                //     text: this.$t("updated_at"),
                //     align: "left",
                //     sortable: false,
                //     value: "updated_at"
                // },
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
        async getSubPages() {
            try {
                this.$service("processing", true);
                const pages = await CmsService.list();
                this.pages = pages;
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        handleClick(e) {
            this.$router
                .push({
                    name: "Cms-detail",
                    params: { cmsId: e.id }
                })
                .catch(() => {});
        }
    }
};
</script>

<style></style>
