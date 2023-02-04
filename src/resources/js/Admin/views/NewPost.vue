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
                            <v-text-field
                                v-model="post.slug"
                                :label="'Url slug*'"
                                required
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-if="mutations.length"
                                v-model="mutations[index].title"
                                label="Název"
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <!-- <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-select
                                v-model="post.categories"
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
                <v-col cols="12" v-if="avatar">
                    <v-img
                        :lazy-src="avatar"
                        max-height="150"
                        max-width="150"
                        width="150"
                        height="150"
                        :src="avatar"
                    ></v-img>
                </v-col>
                <v-col cols="12" v-if="avatar">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="img_alt"
                                label="Alt"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <input
                        style="display:none"
                        ref="blogThumbnailInput"
                        type="file"
                        class="input--file"
                        @input="handleInputFile($event.target.files[0])"
                    />
                    <v-btn
                        color="primary"
                        @click.prevent="$refs.blogThumbnailInput.click()"
                    >Náhledový obrázek
                    </v-btn>
                </v-col>
                <!-- <v-col cols="12">
                    <input
                        style="display:none"
                        ref="blogMedia"
                        type="file"
                        class="input--file"
                        @input="handleInputFile($event.target.files[0])"
                    />
                    <v-btn color="primary" @click.prevent="onInputFile"
                        >{{ $t("add_media_to_item") }}
                    </v-btn>
                </v-col> -->
                <v-col cols="12">
                    <h4>Text</h4>
                    <tiptap-vuetify
                        v-if="mutations.length"
                        v-model="mutations[index].content"
                        :extensions="extensions"
                        :placeholder="$t('blog_content')"
                    />
                </v-col>
                <v-col cols="12">
                    <v-checkbox
                        v-model="post.is_link"
                        label="Zajímavé odkazy"
                    ></v-checkbox>
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
                <v-col style="margin-top:28px;">
                    <h4>Seo</h4>
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="seo.title"
                                label="Titulek"
                                required
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="seo.keywords"
                                :label="$t('blog_keyword')"
                                required
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-textarea
                                v-model="seo.description"
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
                <v-col cols="12" class="text-right">
                    <v-btn color="primary" @click="create">
                        {{ $t("save_arg") }}
                    </v-btn>
                    <v-btn
                        color="secondary"
                        @click.prevent="$router.push({ name: 'Blog' })"
                    >{{ $t("back") }}</v-btn
                    >
                </v-col>
            </v-row>
        </v-form>
    </div>
</template>

<script>
import StorageService from "../services/StorageService";
import BlogService from "../services/BlogService";
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
            avatar: null,
            post: {},
            title: "",
            foreign_lang: false,
            blog_categories: [],
            languages: [],
            selectedLang: null,
            index: 0,
            img_alt: null,
            mutations: [],
            seo: {
                title: null,
                description: null,
                keywords: null
            },
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
    validations: {
        // title: { required }
    },
    async created() {
        await this.$store.dispatch("setShop");
        if (
            Object.prototype.hasOwnProperty.call(this.shop, "blog") &&
            this.shop.blog != 1
        ) {
            this.$router.push({ name: "Dashboard" }).catch(() => {});
            return;
        }
        await this.getLanguages();
        await this.getBlogPostCategories();
    },
    methods: {
        async getLanguages() {
            try {
                const languages = await WebService.languages();
                this.languages = languages;
                this.selectedLang = languages[0].id;
                this.mutations.push({
                    language_id: languages[0].id,
                    title: "",
                    content: "",
                    description: "",
                    keywords: ""
                });
            } catch (e) {
                console.debug(e);
            }
        },
        /**create new blog post */
        async create() {
            this.$v.$touch();
            if (this.$v.$invalid)
                return this.$service(
                    "snackbar",
                    this.$t("form_invalid"),
                    "warning"
                );
            try {
                this.$service("processing", true);
                this.post.mutations = this.mutations;
                this.post.seo = this.seo;
                this.post.image_alt = this.img_alt;
                await BlogService.create(this.post);
                this.$service("processing", false);
                this.$router.push({ name: "Blog" }).catch(() => {});
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
                this.avatar = URL.createObjectURL(f);
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
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
                    title: "",
                    content: "",
                    description: "",
                    keywords: ""
                });
                return;
            } else {
                const currentIndex = this.mutations.findIndex(
                    i => i.language_id == this.selectedLang
                );
                if (currentIndex != -1) this.index = currentIndex;
            }
        }
    },
    computed: {
        shop() {
            return this.$store.state.shop;
        }
    },
    watch: {
        title() {
            this.post.title = this.title;
        }
    }
};
</script>

<style></style>
