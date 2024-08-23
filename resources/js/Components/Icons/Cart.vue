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
        default: 'bi bi-cart',
    },
});
</script>

<template>
    <svg :xmlns="xmlns" :width="width" :height="height" :fill="fill" :class=customClass :viewBox="viewBox">
        <path
            d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
    </svg>
</template>
