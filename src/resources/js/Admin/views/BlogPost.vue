<template>
    <div class="content--fix">
        <h1 class="heading--fix">{{ $t("blog_new") }}</h1>
        <v-form @submit.prevent="() => {}">
            <v-row>
                <!-- languages -->
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4" v-if="languages.length">
                            <v-select
                                :items="languages"
                                item-text="name"
                                item-value="id"
                                v-model="selectedLang"
                                @change="changeLanguage"
                                solo
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <div
                                v-for="(mutation, index) in post.mutations"
                                :key="index"
                            >
                                <v-text-field
                                    v-if="mutation.language_id == selectedLang"
                                    v-model="mutation.title"
                                    label="Název"
                                >
                                </v-text-field>
                            </div>
                        </v-col>
                    </v-row>
                </v-col>
                <!-- <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <template v-if="post.categories.length">
                                <v-chip
                                    class="ma-2 heading--fix"
                                    close
                                    color="red"
                                    text-color="white"
                                    v-for="category in post.categories"
                                    :key="category.id"
                                    @click:close="
                                        handleRemoveCategory(category.id)
                                    "
                                >
                                    {{ category.name }}
                                </v-chip>
                            </template>
                            <v-select
                                v-model="new_categories"
                                :items="blog_categories"
                                :menu-props="{ maxHeight: '400' }"
                                :label="$t('categories_title')"
                                item-text="name"
                                item-value="id"
                                multiple
                                persistent-hint
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-col> -->
                <!-- image -->
                <v-col cols="12" v-if="post.avatar != null">
                    <v-chip
                        class="ma-2"
                        close
                        color="red"
                        text-color="white"
                        @click:close="removeMedia"
                    >
                        {{ $t("delete") }}
                    </v-chip>
                    <v-img
                        :lazy-src="post.avatar"
                        max-height="150"
                        max-width="150"
                        width="150"
                        height="150"
                        :src="post.avatar"
                    ></v-img>
                </v-col>
                <v-col cols="12" v-if="post.avatar != null">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field label="Alt" v-model="post.image_alt">

                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <input
                        style="display:none"
                        ref="blogMedia"
                        type="file"
                        class="input--file"
                        @input="handleInputFile($event.target.files[0])"
                    />
                    <v-btn color="primary" @click.prevent="onInputFile"
                    >{{
                            post.avatar != null
                                ? $t("change_media")
                                : $t("add_media_to_item")
                        }}
                    </v-btn>
                </v-col>
                <v-col cols="12">
                    <h4>Text</h4>
                    <div v-for="(mut, index) in post.mutations" :key="index">
                        <tiptap-vuetify
                            v-if="mut.language_id == selectedLang"
                            v-model="mut.content"
                            :extensions="extensions"
                            :placeholder="$t('blog_content')"
                        />
                    </div>
                </v-col>
                <v-col cols="12">
                    <v-checkbox
                        v-model="post.published"
                        :label="$t('blog_published')"
                    ></v-checkbox>
                    <!-- <v-checkbox
                        v-model="post.favourite"
                        :label="$t('blog_favourite')"
                    ></v-checkbox> -->
                </v-col>
                <v-col cols="12">
                    <v-checkbox
                        v-model="post.is_link"
                        label="Zajímavé odkazy"
                    ></v-checkbox>
                </v-col>
                <v-col style="margin-top:28px;">
                    <h4>Seo</h4>
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="post.seo.title"
                                label="Titulek"
                                required
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="post.seo.keywords"
                                :label="$t('blog_keyword')"
                                required
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-textarea
                                v-model="post.seo.description"
                                color="primary"
                                height="auto"
                            >
                                <template v-slot:label>
                                    <div>
                                        {{ $t("blog_description") }}
                                    </div>
                                </template>
                            </v-textarea>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col>
                            <v-btn
                                color="error"
                                @click.prevent="
                                    $service('dialogRemove', true, {
                                        /**title,text... */
                                    })
                                "
                            >
                                {{ $t("delete") }}
                            </v-btn></v-col
                        >
                        <v-spacer />
                        <v-col class="text-right">
                            <v-btn color="primary" @click.prevent="update">
                                {{ $t("save_arg") }}
                            </v-btn>
                            <v-btn
                                color="secondary"
                                @click.prevent="$router.push({ name: 'Blog' })"
                            >{{ $t("back") }}</v-btn
                            >
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-form>
    </div>
</template>
<script>
import Bus from "../Config/Plugins/Bus";
import BlogService from "../services/BlogService";
import StorageService from "../services/StorageService";
import { required } from "vuelidate/lib/validators";
import BlogCategoryService from "../services/BlogCategoryService";
import WebService from "../services/WebService";
import {
    TiptapVuetify,
    Heading,
    Bold,
    Italic,
    Strike,
    Underline,
    Code,
    Paragraph,
    BulletList,
    OrderedList,
    ListItem,
    Link,
    Blockquote,
    HardBreak,
    HorizontalRule,
    History,
    Image
} from "tiptap-vuetify";
export default {
    components: {
        TiptapVuetify
    },
    data() {
        return {
            post: {},
            foreign_lang: false,
            blog_categories: [],
            new_categories: [],
            mutations: [],
            selectedLang: null,
            index: 0,
            languages: [],
            extensions: [
                History,
                Blockquote,
                Link,
                Underline,
                Strike,
                Italic,
                ListItem,
                BulletList,
                OrderedList,
                [
                    Heading,
                    {
                        options: {
                            levels: [1, 2, 3, 4]
                        }
                    }
                ],
                Bold,
                Code,
                HorizontalRule,
                Paragraph,
                HardBreak,
                Image
            ]
        };
    },
    async created() {
        this.$service("title", "Blog");
        await this.$store.dispatch("setShop");
        if (
            Object.prototype.hasOwnProperty.call(this.shop, "blog") &&
            this.shop.blog != 1
        ) {
            this.$router.push({ name: "Dashboard" }).catch(() => {});
            return;
        }
        await this.detail();
        await this.getLanguages();
        /** remove current blog post */
        await Bus.$on("remove-cb", async () => {
            try {
                this.$service("processing", true);
                await BlogService.remove(this.$route.params.postId);
                this.$service("processing", false);
                this.$service("dialogRemove", false);
                this.$service("snackbar", this.$t("deleted"), "success");
                this.$router.push({ name: "Blog" }).catch(() => {});
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
    beforeDestroy() {
        // this.$bus.$off();
        Bus.$off("remove-cb");
    },
    methods: {
        async getLanguages() {
            try {
                const languages = await WebService.languages();
                this.languages = languages;
                this.selectedLang = languages[0].id;
                // this.mutations.push({
                //     language_id: languages[0].id,
                //     name: ""
                // });
            } catch (e) {
                console.debug(e);
            }
        },
        /** current blog post detail */
        async detail() {
            try {
                this.$service("processing", true);
                const post = await BlogService.detail(
                    this.$route.params.postId
                );
                if (
                    post.foreign_language_title != null ||
                    post.foreign_language_content != null
                ) {
                    this.foreign_lang = true;
                }
                await this.getBlogPostCategories();
                this.post = post;
                this.fixCategories();
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        async getBlogPostCategories() {
            try {
                this.$service("processing", true);
                const categories = await BlogCategoryService.list();
                this.blog_categories = categories;
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        /** update current blog post */
        async update() {
            try {
                this.$service("processing", true);
                if (!this.foreign_lang) {
                    this.post.foreign_language_title = null;
                    this.post.foreign_language_content = null;
                }
                let data = this.post;
                if (this.new_categories.length) {
                    for (let i = 0; i < this.new_categories.length; i++) {
                        data.categories.push({
                            id: null,
                            blog_category_id: this.new_categories[i]
                        });
                    }
                }
                await BlogService.update(this.$route.params.postId, data);
                this.$service("processing", false);
                this.$service("snackbar", this.$t("saved"), "success");
                this.$router.push({ name: "Blog" }).catch(() => {});
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        /** activate input file */
        onInputFile() {
            this.$refs.blogMedia.click();
        },
        /** upload file */
        async handleInputFile(f) {
            try {
                this.$service("processing", true);
                const { avatar, thumbnail } = await StorageService.storeAvatar(
                    f
                );
                this.post.avatar_id = avatar;
                this.post.thumbnail_id = thumbnail;
                await BlogService.update(this.$route.params.postId, this.post);
                await this.detail();
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        /**remove current avatar & thumbnail */
        async removeMedia() {
            try {
                this.$service("processing", true);
                await BlogService.update(this.$route.params.postId, {
                    avatar_id: null,
                    thumbnail_id: null
                });
                await this.detail();
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        async handleRemoveCategory(id) {
            try {
                this.$service("processing", true);
                await BlogCategoryService.removePostCategory(id);
                await this.detail();
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        fixCategories() {
            let filterOut = [];
            if (!this.post.categories.length) return;

            const mapCategories = this.blog_categories.map(i => {
                const filterCurr = this.post.categories.filter(
                    v => v.category_id == i.id
                );
                if (!filterCurr.length) filterOut = [...filterOut, i];
            });
            this.blog_categories = filterOut;
        },
        changeLanguage() {
            console.log("change_lang");
            const isExisting = this.post.mutations.filter(
                i => i.language_id == this.selectedLang
            );
            console.log("is", isExisting);
            if (!isExisting.length) {
                this.post.mutations.push({
                    language_id: this.selectedLang,
                    id: null,
                    title: "",
                    content: "",
                    description: "",
                    keywords: ""
                });
            }
        }
    },
    computed: {
        shop() {
            return this.$store.state.shop;
        }
    }
};
</script>

<style></style>
