<template>
    <div class="loader-wrapper" v-if="show">
        <div class="loader-opacity-bg"></div>
        <v-progress-circular
            color="primary"
            :indeterminate="true"
        ></v-progress-circular>
    </div>
</template>

<script>
export default {
    props: {
        show: {
            type: Boolean,
            default: true
        }
    }
};
</script>

<style>
.loader-wrapper {
    position: fixed;
    width: 100%;
    height: 100%;
    /* top: 56px; */
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 99999999;
}
.loader-opacity-bg {
    background: #d4cfcf5c;
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: -1;
}
</style>
