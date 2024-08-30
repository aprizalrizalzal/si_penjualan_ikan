<script setup>
import { computed } from 'vue';

const currentYear = computed(() => {
    return new Date().getFullYear();
});
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="w-full max-w-2xl px-6 lg:max-w-7xl">
            <p class="pt-5 text-xs hover:text-sm text-gray-900">Copyright &copy; {{
                currentYear }} <a href='/info' rel="noopener noreferrer"><span class="text-gray-900 font-bold">Sistem
                        Informasi Penjualan Ikan (SIPI-Desa Soro)</span></a></p>
        </div>
    </div>


</template>