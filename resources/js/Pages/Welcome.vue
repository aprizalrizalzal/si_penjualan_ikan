<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { initFlowbite } from 'flowbite'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import Footer from './Footer.vue';
import Banners from './Welcome/Banners.vue';
import Products from './Welcome/Products.vue';
import CartPlus from '@/Components/Icons/CartPlus.vue';

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

const showingNavigationDropdown = ref(false);
const hasScrolled = ref(false);

const handleScroll = () => {
    hasScrolled.value = window.scrollY > 0;
};

onMounted(() => {
    initFlowbite();
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>

    <Head title="Welcome" />
    <div class="min-h-screen bg-white">
        <div class="relative min-h-screen flex flex-col items-center justify-center">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <nav v-if="canLogin"
                    :class="[{ 'shadow-sm': hasScrolled }, 'animate-fade-down fixed left-0 top-0 w-full bg-white transition-shadow duration-300 ease-in-out z-50']">
                    <!-- Primary Navigation Menu -->
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center h-20">
                            <div class="flex me-auto truncate">
                                <!-- Logo -->
                                <div class="shrink-0 flex items-center">
                                    <Link :href="route('welcome')">
                                    <ApplicationLogo class="w-16 h-auto" />
                                    </Link>
                                    <h1 class="px-4 text-lg text-gray-800 truncate">
                                        <span class="block sm:hidden">SIPI Desa Soro</span>
                                        <span class="hidden sm:block">Sistem Informasi Penjualan Ikan (Desa Soro)</span>
                                    </h1>
                                </div>
                            </div>

                            <div class="hidden sm:flex sm:items-center sm:ms-6">
                                <Link class="shadow-sm rounded-full hover:bg-blue-100 p-2"
                                    v-if="$page.props.auth.user && isUser" :href="route('show.carts')">
                                <CartPlus class="text-blue-500" width="16" height="16" />
                                </Link>
                                <div class="ms-3 relative">
                                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                                        class="rounded-md px-3 py-2 text-sm text-gray-500 ring-1 ring-transparent transition hover:text-gray-700 focus:outline-none focus-visible:ring-[#FF2D20]">
                                    Dashboard
                                    </Link>

                                    <template v-else>
                                        <Link :href="route('login')"
                                            class="rounded-md px-3 py-2 text-sm text-gray-500 ring-1 ring-transparent transition hover:text-gray-700 focus:outline-none focus-visible:ring-[#FF2D20]">
                                        Masuk
                                        </Link>

                                        <Link v-if="canRegister" :href="route('register')"
                                            class="rounded-md px-3 py-2 text-sm text-gray-500 ring-1 ring-transparent transition hover:text-gray-700 focus:outline-none focus-visible:ring-[#FF2D20]">
                                        Daftar
                                        </Link>
                                    </template>
                                </div>
                            </div>

                            <!-- Hamburger -->
                            <div class="-me-2 flex items-center sm:hidden">
                                <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 focus:text-gray-500 transition duration-250 ease-in-out">
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
                    </div>

                    <!-- Responsive Navigation Menu -->
                    <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                        class="sm:hidden">
                        <div v-if="canLogin" class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink v-if="$page.props.auth.user && isUser" :href="route('show.carts')"
                                :active="route().current('show.carts')">
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

                <main class="animate-fade mb-2">
                    <div class="mt-20 w-full max-w-2xl px-6 lg:max-w-7xl pt-4">

                        <div class="animate-fade-down">
                            <Banners :banners="props.banners" />
                        </div>

                        <div class="animate-fade-up">
                            <Products :products="props.products" :canLogin="props.canLogin"
                                :canRegister="props.canRegister" />
                        </div>
                    </div>
                </main>

                <footer class="animate-fade-up py-16 text-center text-sm text-black">
                    <Footer />
                </footer>
            </div>
        </div>
    </div>
</template>
