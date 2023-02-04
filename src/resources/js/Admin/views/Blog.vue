<template>
    <div class="content--fix">
        <div class="page-header-section-wrapper align-items-center">
            <div class="header-section-col">
                <div class="inner-row align-items-center">
                    <h1 class="page-title" style="margin-bottom:0 !important">
                        Blog
                    </h1>
                    <button
                        class="base-add-button ml-2"
                        @click="$router.push({ name: 'Blog-new' })"
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

        <v-data-table
            :headers="headers"
            :items="posts"
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
            <template v-slot:item.is_link="{ item }">
                <span>{{ item.is_link ? $t("yes_arg") : $t("no_arg") }}</span>
            </template>
            <template v-slot:item.favourite="{ item }">
                <span>{{ item.favourite ? $t("yes_arg") : $t("no_arg") }}</span>
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
                                name: 'Blog-detail',
                                params: { postId: item.id }
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
import UserServices from "../services/UserServices";
import BlogService from "../services/BlogService";
export default {
    async created() {
        this.$service("title", "Blog");
        await this.$store.dispatch("setWeb");
        if (
            Object.prototype.hasOwnProperty.call(this.web, "blog") &&
            this.web.blog != 1
        ) {
            this.$router.push({ name: "Dashboard" }).catch(() => {});
            return;
        }
        await this.getBlogPosts();
    },
    data() {
        return {
            posts: [],
            search: "",
            headers: [
                {
                    text: this.$t("name_product"),
                    align: "left",
                    sortable: true,
                    value: "title"
                },
                {
                    text: this.$t("blog_published"),
                    align: "left",
                    sortable: true,
                    value: "published"
                },
                {
                    text: "Zajímavé odkazy",
                    align: "left",
                    sortable: true,
                    value: "is_link"
                },
                {
                    text: this.$t("blog_favourite"),
                    align: "left",
                    sortable: true,
                    value: "favourite"
                },
                {
                    text: this.$t("updated_at"),
                    align: "left",
                    sortable: true,
                    value: "updated_at"
                },
                {
                    text: this.$t("created_at"),
                    align: "left",
                    sortable: true,
                    value: "created_at"
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
        /**get all posts */
        async getBlogPosts() {
            try {
                this.$service("processing", true);
                const list = await BlogService.list();
                this.posts = list;
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        handleClick(e) {
            this.$router
                .push({
                    name: "Blog-detail",
                    params: { postId: e.id }
                })
                .catch(() => {});
        }
    },
    computed: {
        web() {
            return this.$store.state.web;
        }
    }
};
</script>

<style></style>
