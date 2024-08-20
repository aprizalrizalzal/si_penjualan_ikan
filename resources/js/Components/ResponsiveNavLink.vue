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
        ? 'block w-full ps-3 pe-4 py-6 border-l-4 border-blue-400 text-start text-base font-medium text-gray-700 focus:outline-none focus:text-gray-800 focus:border-blue-700 transition duration-250 ease-in-out'
        : 'block w-full ps-3 pe-4 py-3 border-l-4 border-transparent text-start text-base font-sm text-gray-600 hover:text-gray-800 hover:bg-blue-100 hover:border-blue-300 focus:outline-none focus:text-gray-800 focus:bg-blue-100 focus:border-blue-300 transition duration-250 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
    <slot />
    </Link>
</template>
