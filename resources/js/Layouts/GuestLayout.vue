<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite';
import Footer from '@/Pages/Footer.vue';

onMounted(() => {
    initFlowbite();
})
</script>

<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-l from-blue-700 to-blue-50">
        <div class="animate-fade-down flex flex-col justify-center items-center">
            <Link href="/">
            <ApplicationLogo class="w-40 h-auto p-2 m-2 bg-white rounded-full shadow" />
            </Link>
            <h1 class="text-white font-bold">Sistem Informasi Penjualan Ikan (SIPI-Desa Soro)</h1>
        </div>

        <div class="animate-fade-up w-full sm:max-w-md mt-6 px-6 py-4 bg-white hover:shadow-md overflow-hidden ">
            <slot />
        </div>
    </div>
    <!-- Page Footer -->
    <div class="relative">
        <div class="h-32 bg-gradient-to-l from-blue-700 to-blue-50 text-white flex items-center justify-center">
            <svg class="w-24 h-24" version="1.1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 500 500" xml:space="preserve">
                <path fill="#FFFFFF" stroke="#000000" stroke-width="10" stroke-miterlimit="10" d="M57,283" />
            </svg>
        </div>

        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
            <svg class="w-full h-48 transform translate-y-10" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none"
                shape-rendering="auto">
                <defs>
                    <path id="gentle-wave"
                        d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
                </defs>
                <g class="parallax">
                    <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7)" />
                    <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
                    <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.2)" />
                    <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
                </g>
            </svg>
        </div>
    </div>
    <footer class="animate-fade-up py-4 text-center text-sm text-black">
        <Footer />
    </footer>
</template>

<style>
.parallax>use {
    animation: move-forever 25s cubic-bezier(.55, .5, .45, .5) infinite;
}

.parallax>use:nth-child(1) {
    animation-delay: -2s;
    animation-duration: 7s;
}

.parallax>use:nth-child(2) {
    animation-delay: -3s;
    animation-duration: 10s;
}

.parallax>use:nth-child(3) {
    animation-delay: -4s;
    animation-duration: 13s;
}

.parallax>use:nth-child(4) {
    animation-delay: -5s;
    animation-duration: 20s;
}

@keyframes move-forever {
    0% {
        transform: translate3d(-90px, 0, 0);
    }

    100% {
        transform: translate3d(85px, 0, 0);
    }
}
</style>
