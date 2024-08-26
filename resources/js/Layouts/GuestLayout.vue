<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite';

onMounted(() => {
    initFlowbite();
})
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-white">
        <div class="animate-fade-down flex flex-col justify-center items-center">
            <Link href="/">
            <ApplicationLogo class="w-40 h-auto" />
            </Link>
            <h1 class="text-gray-800 font-bold">Pusat Ikan Desa Soro</h1>
        </div>

        <div
            class="animate-fade-up w-full sm:max-w-md mt-6 px-6 py-4 bg-white hover:shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
