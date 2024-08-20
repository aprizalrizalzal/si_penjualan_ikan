<script setup>
import { ref, watch } from 'vue';

const emit = defineEmits(['update:searchQuery']);
const props = defineProps({
    placeholder: {
        type: String,
        default: 'Search',
    },
});
const searchQuery = ref('');

watch(searchQuery, (newQuery) => {
    emit('update:searchQuery', newQuery);
});
</script>

<template>
    <div class="flex items-center sm:max-w-xs w-full border border-blue-300 rounded-md bg-transparent focus-within:ring-blue-500 focus-within:border-blue-500"
        tabindex="0">
        <span class="ml-2 text-blue-500">
            <!-- Icon Search -->
            <svg class="w-5 h-5 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd"></path>
            </svg>
        </span>
        <input id="search"
            class="block w-full pt-2 ml-2 text-sm text-gray-900 bg-transparent border-none focus:outline-none focus:ring-0"
            type="text" v-model="searchQuery" :placeholder="props.placeholder" />
    </div>
</template>