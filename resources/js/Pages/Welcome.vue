<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { initFlowbite } from 'flowbite';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import Footer from './Footer.vue';
import Banners from './Welcome/Banners.vue';
import Products from './Welcome/Products.vue';
import CartPlus from '@/Components/Icons/CartPlus.vue';
import SearchInput from '@/Components/SearchInput.vue';
import { searchProducts } from '@/utils/helper';

const { auth } = usePage().props;
const roles = ref(auth.roles);
const hasRole = (role) => roles.value.includes(role);

const isAdmin = computed(() => hasRole('admin'));
const isUser = computed(() => hasRole('user'));

const props = defineProps({
    banners: Array,
    products: Array,

    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const searchQuery = ref('');

const filteredProducts = computed(() => {
    return searchProducts(props.products, searchQuery.value);
});

const showingNavigationDropdown = ref(false);
const hasScrolledToBottom = ref(false);
const hasScrolledDown = ref(false);

const handleScrollUp = () => {
    hasScrolledToBottom.value = window.scrollY > 100;
};

const handleScrollDown = () => {
    hasScrolledDown.value = window.scrollY > 0;
};

onMounted(() => {
    initFlowbite();
    window.addEventListener('scroll', handleScrollUp);
    window.addEventListener('scroll', handleScrollDown);
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScrollUp);
    window.removeEventListener('scroll', handleScrollDown);
});
</script>

<template>

    <Head title="Welcome" />
    <div class="min-h-screen bg-gradient-to-l from-blue-700 to-blue-50">
        <nav v-if="canLogin"
            :class="[{ 'shadow-sm': hasScrolledDown }, 'animate-fade-down fixed left-0 top-0 w-full bg-blue-50 transition-shadow duration-300 ease-in-out z-50']">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center h-20">
                    <div class="flex me-auto truncate">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('welcome')">
                            <ApplicationLogo class="w-16 h-auto p-2 m-2 bg-white rounded-full shadow" />
                            </Link>
                            <h1 class="px-4 text-lg text-gray-800 truncate">
                                <span class="block sm:hidden">SIPI-Desa Soro</span>
                                <span class="hidden sm:block">Sistem Informasi Penjualan Ikan
                                    (SIPI-Desa Soro)</span>
                            </h1>
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <SearchInput v-model:searchQuery="searchQuery" placeholder="Cari" class="bg-white mx-2" />
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <Link class="shadow-sm rounded-full bg-white hover:shadow-lg p-3"
                            v-if="$page.props.auth.user && isUser" :href="route('show.carts')">
                        <CartPlus class="text-blue-500" width="16" height="16" />
                        </Link>

                        <div class="ms-3 relative">
                            <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                                class="px-3 py-2 text-sm text-gray-500 ring-1 ring-transparent transition hover:text-gray-700 focus:outline-none focus-visible:ring-[#FF2D20]">
                            Dashboard
                            </Link>

                            <template v-else>
                                <Link :href="route('login')"
                                    class="px-3 py-2 text-sm text-gray-500 ring-1 ring-transparent transition hover:text-gray-700 focus:outline-none focus-visible:ring-[#FF2D20]">
                                Masuk
                                </Link>

                                <Link v-if="canRegister" :href="route('register')"
                                    class="px-3 py-2 text-sm text-gray-500 ring-1 ring-transparent transition hover:text-gray-700 focus:outline-none focus-visible:ring-[#FF2D20]">
                                Daftar
                                </Link>
                            </template>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 text-gray-400 hover:text-gray-500 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 focus:text-gray-500 transition duration-250 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="block sm:hidden max-w-7xl mx-auto">
                    <div class="flex items-center justify-end sm:flex-row flex-col gap-4 py-4 sm:px-0">
                        <SearchInput v-model:searchQuery="searchQuery" placeholder="Cari" class="bg-white mx-2" />
                    </div>
                </div>
            </div>
            <!-- Responsive Navigation Menu -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                <div v-if="canLogin" class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink v-if="$page.props.auth.user && isUser" :href="route('show.carts')">
                        Keranjang
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="$page.props.auth.user" :href="route('dashboard')"
                        :active="route().current('dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                    <template v-else>
                        <div class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink :href="route('login')">
                                Masuk
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="canRegister" :href="route('register')">
                                Daftar
                            </ResponsiveNavLink>
                        </div>
                    </template>
                </div>
            </div>
        </nav>

        <main class="animate-fade mb-2 pt-24">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="animate-fade-down">
                    <Banners :banners="props.banners" />
                </div>

                <div class="animate-fade-up mt-4">
                    <Products :products="filteredProducts" :canLogin="props.canLogin"
                        :canRegister="props.canRegister" />
                </div>
            </div>
        </main>
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
    </div>
    <footer :class="[
        'fixed w-full bottom-0 left-0 pb-2 text-center text-sm text-gray-900 bg-white z-40',
        { 'block animate-fade-up': hasScrolledToBottom, 'hidden animate-fade-down': !hasScrolledToBottom },
    ]">
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
