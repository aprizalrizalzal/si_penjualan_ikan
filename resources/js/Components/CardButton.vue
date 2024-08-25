<template>
    <button
        class="flex flex-col p-2 max-w-sm rounded text-sm overflow-hidden bg-white hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:ring-offset-2 hover:rounded-none hover:ring-blue-300 ring-1 ring-white/[0.05] transition ease-in-out duration-150">
        <div class="flex items-center text-blue-600 px-2 gap-2">
            <div class="h-auto flex">
                <slot name="svg"></slot>
            </div>
            <div class="flex flex-col justify-start items-center">
                <p class="font-bold text-md">{{ title }}</p>
                <p class="text-gray-500 text-left text-xs">{{ description }}</p>
            </div>
            <div class="h-auto flex">
                <slot name="end">{{ count }}</slot>
            </div>
        </div>
    </button>
</template>

<script setup>
const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    count: {
        type: Number,
        required: false,
    },
});
</script>