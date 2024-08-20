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
        ? 'block w-full ps-3 pe-4 py-6 border-l-4 border-blue-400 dark:border-blue-600 text-start text-base font-medium text-gray-700 dark:text-gray-300 bg-blue-100 dark:bg-blue-900/50 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-blue-100 dark:focus:bg-blue-900 focus:border-blue-700 dark:focus:border-blue-300 transition duration-250 ease-in-out'
        : 'block w-full ps-3 pe-4 py-3 border-l-4 border-transparent text-start text-base font-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-blue-100 dark:hover:bg-blue-700 hover:border-blue-300 dark:hover:border-blue-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-blue-100 dark:focus:bg-blue-700 focus:border-blue-300 dark:focus:border-blue-600 transition duration-250 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
    <slot />
    </Link>
</template>
