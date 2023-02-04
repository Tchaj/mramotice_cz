<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" :max-width="dialogWidth" persistent>
            <v-card>
                <v-card-title class="headline">
                    {{
                        Object.keys(args).includes("title")
                            ? args.title
                            : $t("confirm_delete")
                    }}
                </v-card-title>

                <v-card-text>
                    {{ Object.keys(args).includes("text") ? args.text : "" }}
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="primary" small @click="emitDialogState(false)">
                        {{ $t("close_arg") }}
                    </v-btn>

                    <v-btn color="error" small @click="emitDialogState(true)">
                        {{ $t("delete") }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
<script>
import Bus from "../Config/Plugins/Bus";
export default {
    data() {
        return {
            dialogWidth: 360
        };
    },
    props: {
        dialog: {
            type: Boolean,
            default: () => false
        },
        args: {
            type: Object,
            default: () => {
                return {};
            }
        }
    },
    methods: {
        emitDialogState(state) {
            if (!state) {
                this.$service("dialogRemove", false);
                return;
            } else {
                Bus.$emit("remove-cb");
                Bus.$off("remove-cb");
                // Bus.$destroy();
                return;
            }
            return;
        },
        setDialogWidth(e) {
            console.log(e);
        }
    },
    mounted() {
        document.addEventListener("load", this.setDialogWidth);
    },
    destroyed() {
        document.removeEventListener("load", this.setDialogWidth);
    }
};
</script>
