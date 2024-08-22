<template>
    <div
        class="h-full flex flex-col justify-between items-stretch max-w-sm rounded text-sm/relaxed overflow-hidden shadow bg-white hover:bg-green-100 focus:bg-green-100 active:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-100 focus:ring-offset-2 hover:rounded-none hover:ring-green-300 ring-1 ring-white/[0.05] transition ease-in-out duration-150">
        <div class="h-full flex justify-between items-stretch m-2">
            <slot name="img"></slot>
        </div>
        <div class=" text-center px-2">
            <p class="text-green-700 overflow-hidden text-xs truncate">{{ category }}</p>
        </div>
        <hr class="my-2">
        <div class="text-start px-2">
            <p class="text-gray-900 overflow-hidden font-bold truncate">{{ name }}</p>
        </div>
        <div class="text-end">
            <span class="inline-block bg-green-100 rounded mt-2 px-2 text-green-700 mx-2 truncate">
                {{ price }}
            </span>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    category: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    price: {
        type: String,
        required: true,
    },
});
</script>