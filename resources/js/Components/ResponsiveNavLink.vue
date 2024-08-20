<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-6 border-l-4 border-green-400 dark:border-green-600 text-start text-base font-medium text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/50 focus:outline-none focus:text-green-800 dark:focus:text-green-200 focus:bg-green-100 dark:focus:bg-green-900 focus:border-green-700 dark:focus:border-green-300 transition duration-250 ease-in-out'
        : 'block w-full ps-3 pe-4 py-3 border-l-4 border-transparent text-start text-base font-sm text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200 hover:bg-green-100 dark:hover:bg-green-700 hover:border-green-300 dark:hover:border-green-600 focus:outline-none focus:text-green-800 dark:focus:text-green-200 focus:bg-green-100 dark:focus:bg-green-700 focus:border-green-300 dark:focus:border-green-600 transition duration-250 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
    <slot />
    </Link>
</template>
