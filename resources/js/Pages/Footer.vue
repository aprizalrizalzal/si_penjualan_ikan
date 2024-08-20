<script setup>
import { computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const currentYear = computed(() => {
    return new Date().getFullYear();
});
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="w-full max-w-2xl px-6 lg:max-w-7xl">
            <hr>
            <p class="pt-5 text-xs text-gray-900">Copyright &copy; {{
                currentYear }} <a href='#' target="_blank" rel="noopener noreferrer"><span
                        class="text-gray-900 font-bold">Sistem Informasi Penjualan Ikan (SIPI)</span></a></p>
        </div>
    </div>
</template>
