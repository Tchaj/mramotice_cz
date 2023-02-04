<template>
    <v-snackbar v-model="snackbar">
        {{ text }}

        <template v-slot:action="{ attrs }">
            <v-btn
                :color="color"
                text
                v-bind="attrs"
                @click="$emit('click')"
            >
                <!-- $emit('closeSnackbar') -->
                {{ $t("close_arg") }}
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script>
export default {
    props: {
        snackbar: {
            type: Boolean,
            default: false
        },
        text: String,
        color: String
    },
    methods: {
        close() {
            this.$emit("close");
        }
    }
};
</script>

<style></style>
