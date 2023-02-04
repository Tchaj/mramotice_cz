<template>
    <div class="content--fix">
        <h1 class="heading--fix">{{ $t("cms_title") }}</h1>
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
                    <v-checkbox
                        v-model="cms.published"
                        :label="$t('blog_published')"
                    ></v-checkbox>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="cms.slug"
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
                                v-model="mutations[index].page_name"
                                :label="$t('cms_page_title')"
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col md="4">
                            <v-text-field
                                v-if="mutations.length"
                                v-model="mutations[index].title"
                                :label="$t('cms_page_heading')"
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <!-- content -->
                <v-col cols="12">
                    <h4>{{ $t("blog_content") }}</h4>
                    <tiptap-vuetify
                        v-if="mutations.length"
                        v-model="mutations[index].content"
                        :extensions="extensions"
                        :placeholder="$t('blog_content')"
                    />
                </v-col>

                <!-- seo -->
                <h4 class="heading--fix mt-4">Seo</h4>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field v-model="seo.title" label="Titulek">
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="seo.keywords"
                                :label="$t('blog_keyword')"
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
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
                <v-col cols="12">
                    <input
                        style="display:none"
                        ref="cmsImage"
                        type="file"
                        class="input--file"
                        @input="handleInputFile($event.target.files[0])"
                    />
                    <v-btn color="primary" @click.prevent="onInputFile"
                    >{{
                            cms.image != null ? $t("change_media") : $t("image")
                        }}
                    </v-btn>
                </v-col>
                <!-- Create -->
                <v-col class="text-right" cols="12" md="12">
                    <v-btn color="primary" @click="create">
                        {{ $t("save_arg") }}
                    </v-btn>
                    <v-btn
                        color="secondary"
                        @click.prevent="$router.push({ name: 'Cms' })"
                    >{{ $t("back") }}</v-btn
                    >
                </v-col>
            </v-row>
        </v-form>
    </div>
</template>

<script>
import CmsService from "../services/CmsService";
import StorageService from "../services/StorageService";
import { required } from "vuelidate/lib/validators";
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
            cms: {},
            seo: {
                title: null,
                description: null,
                keywords: null
            },
            avatar_id: null,
            foreign_lang: false,
            languages: [],
            mutations: [],
            index: 0,
            selectedLang: null,
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
        await this.getLanguages();
    },
    methods: {
        async getLanguages() {
            try {
                const languages = await WebService.languages();
                this.languages = languages;
                this.selectedLang = languages[0].id;
                this.mutations.push({
                    language_id: languages[0].id,
                    page_name: "",
                    title: "",
                    content: "",
                    keywords: "",
                    description: ""
                });
            } catch (e) {
                console.debug(e);
            }
        },
        /**create new page */
        async create() {
            try {
                this.$service("processing", true);
                if (!this.cms.hasOwnProperty("published"))
                    this.cms.published = false;
                this.cms.mutations = this.mutations;
                this.cms.seo = this.seo;
                await CmsService.create(this.cms);
                this.$service("processing", false);
                this.$service("snackbar", this.$t("saved"), "success");
                this.$router.push({ name: "Cms" }).catch(() => {});
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        /**open window file */
        onInputFile() {
            this.$refs.cmsImage.click();
        },
        /**upload file  */
        async handleInputFile(f) {
            try {
                this.$service("processing", true);
                const { avatar, thumbnail } = await StorageService.storeAvatar(
                    f
                );
                this.cms.image = avatar;
                this.$service("snackbar", this.$t("saved"), "success");
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
                    page_name: "",
                    title: "",
                    content: "",
                    keywords: "",
                    description: ""
                });
                return;
            } else {
                const currentIndex = this.mutations.findIndex(
                    i => i.language_id == this.selectedLang
                );
                if (currentIndex != -1) this.index = currentIndex;
            }
        }
    }
};
</script>

<style></style>
