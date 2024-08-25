<template>
    <div
        class=" relative h-full flex flex-col justify-between items-stretch max-w-sm rounded text-sm/relaxed overflow-hidden hover:shadow bg-white hover:bg-blue-100 focus:bg-blue-100 active:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:ring-offset-2 hover:rounded-none hover:ring-blue-300 ring-1 ring-white/[0.05] transition ease-in-out duration-150">
        <div class="h-full flex justify-between items-stretch m-2">
            <slot name="img"></slot>
        </div>
        <div class=" text-center px-2">
            <p class="text-gray-700 overflow-hidden text-xs truncate">{{ category }}</p>
        </div>
        <hr class="my-2">
        <div class="text-start px-2">
            <p class="text-gray-900 overflow-hidden font-bold truncate">{{ name }}</p>
        </div>
        <div class="text-end">
            <span class="inline-block bg-blue-100 rounded mt-2 px-2 text-gray-700 mx-2 truncate">
                {{ price }}
            </span>
        </div>
        <p
            class="absolute top-2 left-2 inline-flex bg-black items-center p-3 hover:p-4 rounded-b-full font-semibold text-xs text-white tracking-widest shadow focus:outline-none focus:ring-1 focus:ring-blue-900 opacity-50 transition ease-in-out duration-150">
            {{ stock }}
        </p>
        <div class="flex flex-col gap-2 m-2">
            <slot name="button"></slot>
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
    stock: {
        type: String,
        required: true,
    },
    price: {
        type: String,
        required: true,
    },
});
</script>