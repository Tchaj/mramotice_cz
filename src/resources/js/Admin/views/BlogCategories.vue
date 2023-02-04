<template>
    <div class="content--fix">
        <div class="page-header-section-wrapper align-items-center">
            <div class="header-section-col">
                <div class="inner-row align-items-center">
                    <h1 class="page-title" style="margin-bottom:0 !important">
                        {{ $t("blog_categories") }}
                    </h1>
                    <button
                        class="base-add-button ml-2"
                        @click="dialogCreate = true"
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

        <!-- <h1 class="heading--fix">{{ $t("blog_categories") }}</h1>
        <v-btn color="primary" @click="dialogCreate = true">{{
            $t("categories_new")
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
            :items="categories"
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
            <template v-slot:item.updated_at="{ item }">
                <span>{{ item.updated_at | moment("D. M. Y") }}</span>
            </template>
            <template v-slot:item.edit="{ item }">
                <button @click.stop="onDialogUpdate(item.id)">
                    <v-icon color="primary">mdi-pencil</v-icon>
                </button>
            </template>
            <template v-slot:item.delete="{ item }">
                <button @click.stop="onDialogDelete(item.id)">
                    <v-icon color="error">mdi-close-circle</v-icon>
                </button>
            </template>
        </v-data-table>

        <!-- dialog create -->
        <v-row justify="center">
            <v-dialog v-model="dialogCreate" max-width="360" persistent>
                <v-card>
                    <v-card-title class="headline"></v-card-title>

                    <v-card-text v-if="mutations.length">
                        <v-select
                            :items="languages"
                            item-text="name"
                            item-value="id"
                            v-model="selectedLang"
                            @change="changeLanguage"
                            solo
                        ></v-select>
                        <v-text-field
                            v-model="category.slug"
                            :label="'Url slug'"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="mutations[index].name"
                            :label="$t('name_product')"
                            required
                        ></v-text-field>
                        <hr />
                        <div class="mt-4"></div>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>

                        <v-btn color="info" text @click="onCloseDialogCreate">
                            {{ $t("close_arg") }}
                        </v-btn>

                        <v-btn color="success" text @click="handleDialogCreate">
                            {{ $t("save_arg") }}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
        <!-- dialog delete -->
        <v-row justify="center">
            <v-dialog v-model="dialogDelete" max-width="360" persistent>
                <v-card>
                    <v-card-title class="headline"></v-card-title>

                    <v-card-text>
                        {{ $t("confirm_delete") }}
                        <hr />
                        <div class="mt-4"></div>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>

                        <v-btn color="info" text @click="onCloseDialogDelete">
                            {{ $t("close_arg") }}
                        </v-btn>

                        <v-btn color="success" text @click="handleDialogDelete">
                            {{ $t("delete") }}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
        <!-- dialog update -->
        <v-row justify="center">
            <v-dialog v-model="dialogUpdate" max-width="360" persistent>
                <v-card>
                    <v-card-title class="headline"></v-card-title>

                    <v-card-text>
                        <v-select
                            :items="languages"
                            item-text="name"
                            item-value="id"
                            v-model="selectedLang"
                            @change="changeLanguageDetail"
                            solo
                        ></v-select>
                        <div
                            v-for="(mutation, index) in category.mutations"
                            :key="index"
                        >
                            <v-text-field
                                v-if="mutation.language_id == selectedLang"
                                v-model="mutation.name"
                                :label="$t('name_product')"
                                required
                            ></v-text-field>
                        </div>

                        <hr />
                        <div class="mt-4"></div>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>

                        <v-btn color="info" text @click="onCloseDialogUpdate">
                            {{ $t("close_arg") }}
                        </v-btn>

                        <v-btn color="success" text @click="handleDialogUpdate">
                            {{ $t("save_arg") }}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
    </div>
</template>

<script>
import BlogCategoryService from "../services/BlogCategoryService";
import WebService from "../services/WebService";

export default {
    async created() {
        this.$service("title", this.$t("blog_categories"));
        await this.getBlogCategories();
        await this.getLanguages();
    },
    data() {
        return {
            search: "",
            categories: [],
            category: {},
            focused: null,
            dialogCreate: false,
            dialogUpdate: false,
            dialogDelete: false,
            index: 0,
            selectedLang: null,
            languages: [],
            mutations: [],
            headers: [
                {
                    text: this.$t("first_name"),
                    align: "left",
                    sortable: true,
                    value: "name"
                },
                {
                    text: this.$t("created_at"),
                    align: "left",
                    sortable: true,
                    value: "created_at"
                },
                {
                    text: this.$t("updated_at"),
                    align: "left",
                    sortable: true,
                    value: "updated_at"
                },
                {
                    text: "",
                    align: "right",
                    sortable: false,
                    value: "edit"
                },
                {
                    text: "",
                    align: "right",
                    sortable: false,
                    value: "delete"
                }
            ]
        };
    },
    methods: {
        async getBlogCategories() {
            try {
                this.$service("processing", true);
                const cat = await BlogCategoryService.list();
                this.categories = cat;
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        async getBlogCategory(id) {
            try {
                this.$service("processing", true);
                const cat = await BlogCategoryService.detail(id);
                this.category = cat;
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        async getLanguages() {
            try {
                const languages = await WebService.languages();
                this.languages = languages;
                this.selectedLang = languages[0].id;
                this.mutations.push({
                    language_id: languages[0].id,
                    name: ""
                });
            } catch (e) {
                console.debug(e);
            }
        },
        handleClick(e) {
            // this.$router
            //     .push({
            //         name: "Users-detail",
            //         params: { userId: e.id }
            //     })
            //     .catch(() => {});
        },
        async handleDialogCreate() {
            try {
                this.$service("processing", true);
                this.category.mutations = this.mutations;
                await BlogCategoryService.create(this.category);
                await this.getBlogCategories();
                this.$service("snackbar", this.$t("saved"), "success");
                this.onCloseDialogCreate();
                this.$service("processing", false);
                this.resetMutation();
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        async onDialogUpdate(id) {
            try {
                this.$service("processing", true);
                const category = await BlogCategoryService.detail(id);
                this.category = category;
                this.dialogUpdate = true;
                this.$service("processing", false);
                this.resetMutation();
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        async onDialogDelete(id) {
            try {
                this.$service("processing", true);
                const category = await BlogCategoryService.detail(id);
                this.category = category;
                this.dialogDelete = true;
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        async handleDialogUpdate() {
            try {
                this.$service("processing", true);
                await BlogCategoryService.update(
                    this.category.id,
                    this.category
                );
                await this.getBlogCategories();
                this.$service("snackbar", this.$t("saved"), "success");
                this.$service("processing", false);
                this.resetMutation();
                this.onCloseDialogUpdate();
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        async handleDialogDelete() {
            try {
                this.$service("processing", true);
                await BlogCategoryService.remove(this.category.id);
                await this.getBlogCategories();
                this.$service("snackbar", this.$t("deleted"), "success");
                this.$service("processing", false);
                this.onCloseDialogDelete();
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        onCloseDialogCreate() {
            this.category = {};
            this.dialogCreate = false;
        },
        onCloseDialogUpdate() {
            this.category = {};
            this.dialogUpdate = false;
        },
        onCloseDialogDelete() {
            this.category = {};
            this.dialogDelete = false;
        },
        resetMutation() {
            this.selectedLang = this.languages[0].id;
            this.index = 0;
            this.mutations = [{ language_id: this.selectedLang, name: "" }];
        },
        changeLanguage() {
            // set new index
            const inArray = this.mutations.filter(
                i => i.language_id == this.selectedLang
            );
            if (!inArray.length) {
                this.index++;
                this.mutations.push({
                    language_id: this.selectedLang,
                    name: ""
                });
                return;
            } else {
                const currentIndex = this.mutations.findIndex(
                    i => i.language_id == this.selectedLang
                );
                if (currentIndex != -1) this.index = currentIndex;
            }
        },
        changeLanguageDetail() {
            const isExisting = this.category.mutations.filter(
                i => i.language_id == this.selectedLang
            );
            if (!isExisting.length) {
                this.category.mutations.push({
                    language_id: this.selectedLang,
                    id: null,
                    name: "",
                    description: ""
                });
            }
        }
    }
};
</script>

<style></style>
