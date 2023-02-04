<template>
    <span class="error-input" :class="{ 'error-input--active': hasError }">
      {{ !req ? $t('required') : ""}}
    </span>
</template>

<script>
export default {
    props: ["hasError", "req"]
};
</script>
