<template>
    <div class="content--fix">
        <h1 class="heading--fix">{{ $t("cms_title") }}</h1>
        <v-form @submit.prevent="() => {}">
            <v-row>
                <!-- languages -->
                <!-- <v-col cols="12">
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
                </v-col> -->
                <!-- <v-col cols="12">
                    <v-checkbox
                        v-model="cms.published"
                        :label="$t('blog_published')"
                    ></v-checkbox>
                </v-col> -->
                <!-- <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <div
                                v-for="(mut, index) in cms.mutations"
                                :key="index"
                            >
                                <v-text-field
                                    v-if="mut.language_id == selectedLang"
                                    v-model="mut.page_name"
                                    :label="$t('cms_page_title')"
                                >
                                </v-text-field>
                            </div>
                        </v-col>
                    </v-row>
                </v-col> -->
                <!-- <v-col cols="12">
                    <v-row>
                        <v-col md="4">
                            <div
                                v-for="(mut, index) in cms.mutations"
                                :key="index"
                            >
                                <v-text-field
                                    v-if="mut.language_id == selectedLang"
                                    v-model="mut.title"
                                    :label="$t('cms_page_heading')"
                                >
                                </v-text-field>
                            </div>
                        </v-col>
                    </v-row>
                </v-col> -->
                <!-- content -->
                <!-- <v-col cols="12">
                    <h4>{{ $t("blog_content") }}</h4>
                    <div v-for="(mut, index) in cms.mutations" :key="index">
                        <tiptap-vuetify
                            v-if="mut.language_id == selectedLang"
                            v-model="mut.content"
                            :extensions="extensions"
                            :placeholder="$t('blog_content')"
                        />
                    </div>
                </v-col> -->
                <!-- seo -->
                <h4 class="heading--fix mt-4">Seo</h4>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <div>
                                <v-text-field
                                    v-model="cms.seo.title"
                                    label="Titulek"
                                >
                                </v-text-field>
                            </div>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <div>
                                <v-text-field
                                    v-model="cms.seo.keywords"
                                    :label="$t('blog_keyword')"
                                >
                                </v-text-field>
                            </div>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-row>
                        <v-col cols="12" md="4">
                            <div>
                                <v-textarea
                                    v-model="cms.seo.description"
                                    color="primary"
                                    height="auto"
                                >
                                    <template v-slot:label>
                                        <div>
                                            {{ $t("blog_description") }}
                                        </div>
                                    </template>
                                </v-textarea>
                            </div>
                        </v-col>
                    </v-row>
                </v-col>
                <!-- <v-col cols="12" v-if="cms.image != null">
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
                        :lazy-src="cms.image"
                        max-height="150"
                        max-width="150"
                        width="150"
                        height="150"
                        :src="cms.image"
                    ></v-img>
                </v-col> -->
                <!-- <v-col cols="12">
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
                </v-col> -->
                <!-- update -->
                <v-col cols="12" class="heading--fix">
                    <v-row>
                        <!-- <v-col>
                            <v-btn
                                color="error"
                                @click.prevent="
                                    $service('dialogRemove', true, {
                                        /**title,text... */
                                    })
                                "
                            >
                                {{ $t("delete") }}
                            </v-btn>
                        </v-col> -->
                        <v-spacer />
                        <v-col class="text-right">
                            <v-btn color="primary" @click="update">
                                {{ $t("save_arg") }}
                            </v-btn>
                            <v-btn
                                color="secondary"
                                @click.prevent="$router.push({ name: 'Cms' })"
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
import CmsService from "../services/CmsService";
import StorageService from "../services/StorageService";
import { required } from "vuelidate/lib/validators";
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
import Bus from "../Config/Plugins/Bus";
import WebService from "../services/WebService";
export default {
    components: {
        TiptapVuetify
    },
    data() {
        return {
            cms: {},
            avatar_id: null,
            foreign_lang: false,
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
                HardBreak
            ]
        };
    },
    beforeDestroy() {
        // this.$bus.$off();
        Bus.$off("remove-cb");
    },
    async created() {
        try {
            await this.detail();
            await this.getLanguages();
            await Bus.$on("remove-cb", async () => {
                try {
                    this.$service("processing", true);
                    const rem = await CmsService.remove(
                        this.$route.params.cmsId
                    );
                    this.$service("processing", false);
                    this.$service("dialogRemove", false);
                    this.$service("snackbar", this.$t("deleted"), "success");
                    this.$router.push({ name: "Cms" }).catch(() => {});
                    return;
                } catch (e) {
                    console.debug(e);
                    this.$service("processing", false);
                    this.$service("dialogRemove", false);
                    this.$service("snackbar", this.$t("error"), "warning");
                    return;
                }
            });
        } catch (e) {
            console.debug(e);
        }
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
        async detail() {
            try {
                this.$service("processing", true);
                const cms = await CmsService.detail(this.$route.params.cmsId);
                this.cms = cms;
                // this.foreign_lang =
                //     this.cms.foreign_language_title != null ||
                //     this.cms.foreign_language_content != null
                //         ? true
                //         : false;
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        /**update new page */
        async update(redirect = true) {
            try {
                this.$service("processing", true);
                if (!this.cms.hasOwnProperty("published"))
                    this.cms.published = false;
                // if (!this.foreign_lang) {
                //     this.cms.foreign_language_title = null;
                //     this.cms.foreign_language_content = null;
                // }
                await CmsService.update(this.$route.params.cmsId, this.cms);
                this.$service("processing", false);
                this.$service("snackbar", this.$t("saved"), "success");
                if (redirect) {
                    this.$router.push({ name: "Cms" }).catch(() => {});
                    return;
                } else {
                    await this.detail();
                }
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
                await this.update(false);
                this.$service("snackbar", this.$t("saved"), "success");
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        async removeMedia() {
            try {
                this.$service("processing", true);
                this.cms.image = null;
                await this.update(false);
                this.$service("processing", false);
            } catch (e) {
                this.$service("processing", false);
                this.$service("snackbar", this.$t("error"), "warning");
            }
        },
        changeLanguage() {
            console.log("change_lang");
            const isExisting = this.cms.mutations.filter(
                i => i.language_id == this.selectedLang
            );
            console.log("is", isExisting);
            if (!isExisting.length) {
                this.cms.mutations.push({
                    language_id: this.selectedLang,
                    id: null,
                    page_name: "",
                    title: "",
                    content: "",
                    keywords: "",
                    description: ""
                });
            }
        }
    }
};
</script>

<style></style>
