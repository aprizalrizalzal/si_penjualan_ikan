<script setup>
const props = defineProps({
    width: {
        type: String,
        default: '24',
    },
    height: {
        type: String,
        default: '24',
    },
    fill: {
        type: String,
        default: 'currentColor',
    },
    xmlns: {
        type: String,
        default: 'http://www.w3.org/2000/svg',
    },
    viewBox: {
        type: String,
        default: '0 0 16 16',
    },
    customClass: {
        type: String,
        default: 'bi bi-plus-circle',
    },
});
</script>

<template>
    <svg :xmlns="xmlns" :width="width" :height="height" :fill="fill" :class=customClass :viewBox="viewBox">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
        <path
            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
    </svg>
</template>
