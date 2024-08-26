<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { initFlowbite } from 'flowbite'
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import Footer from '@/Pages/Footer.vue';
import Inbox from '@/Components/Icons/Inbox.vue';
import CreditCard from '@/Components/Icons/CreditCard.vue';
import Cart from '@/Components/Icons/Cart.vue';
import BoxSeam from '@/Components/Icons/BoxSeam.vue';
import People from '@/Components/Icons/People.vue';
import Person from '@/Components/Icons/Person.vue';

const { auth } = usePage().props;
const roles = ref(auth.roles);
const hasRole = (role) => roles.value.includes(role);

const isAdmin = computed(() => hasRole('admin'));
const isUser = computed(() => hasRole('user'));

const showingNavigationDropdown = ref(false);
const hasScrolled = ref(false);

const handleScroll = () => {
    hasScrolled.value = window.scrollY > 0;
};

onMounted(() => {
    initFlowbite();
    window.addEventListener('scroll', handleScroll);
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>


<template>
    <div>
        <div class="min-h-screen bg-white">
            <nav :class="[
                'animate-fade-down fixed left-0 top-0 w-full bg-white transition-shadow duration-300 z-0',
                { 'shadow-sm': hasScrolled },
            ]">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center h-20">
                        <!-- drawer admin init and show -->
                        <button v-if="isAdmin"
                            class="animate-fade-right px-2 my-5 px-2 py-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 focus:text-gray-700 transition duration-150 ease-in-out"
                            type="button" data-drawer-target="drawer-navigation-admin"
                            data-drawer-show="drawer-navigation-admin" aria-controls="drawer-navigation-admin">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-chevron-compact-right animate-pulse" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" stroke="currentColor" stroke-width="0.5"
                                    d="M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671" />
                            </svg>
                        </button>
                        <!-- drawer user init and show -->
                        <button v-if="isUser"
                            class="animate-fade-right px-2 my-5 px-2 py-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 focus:text-gray-700 transition duration-150 ease-in-out"
                            type="button" data-drawer-target="drawer-navigation-user"
                            data-drawer-show="drawer-navigation-user" aria-controls="drawer-navigation-user">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-chevron-compact-right animate-pulse" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" stroke="currentColor" stroke-width="0.5"
                                    d="M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671" />
                            </svg>
                        </button>
                        <div class="flex me-auto truncate">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('welcome')">
                                <ApplicationLogo class="w-16 h-auto" />
                                </Link>
                                <Link :href="route('welcome')">
                                <h1 class="px-4 text-lg text-gray-800 truncate">
                                    <span class="block sm:hidden">SIPI Desa Soro</span>
                                    <span class="hidden sm:block">Pusat Ikan Desa Soro</span>
                                </h1>
                                </Link>
                            </div>
                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <!-- Add your navigation links here -->
                            </div>
                        </div>
                        <div class="hidden sm:flex ms-auto sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-250">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profil </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Keluar
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                        <!-- Hamburger -->
                        <div class="animate-fade-left -me-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 focus:text-gray-700 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="#256125" fill="none" viewBox="0 0 24 24">
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
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>
                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-blue-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profil </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Keluar
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Page Heading -->
            <header class="animate-fade-left shadow-sm mt-20 bg-white transition-shadow duration-300"
                v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
            <!-- drawer admin component -->
            <div v-if="isAdmin" id="drawer-navigation-admin"
                class="fixed top-0 left-0 z-50 h-screen py-7 overflow-y-auto transition-transform -translate-x-full bg-white w-64"
                tabindex="-1" aria-labelledby="drawer-navigation-label">
                <h5 id="drawer-navigation-label" class="text-base font-semibold text-gray-500 px-4 pb-7">
                    Menu - Admin
                </h5>
                <hr>
                <button type="button" data-drawer-hide="drawer-navigation-admin" aria-controls="drawer-navigation-admin"
                    class="text-gray-400 bg-transparent hover:bg-blue-100 hover:text-gray-900 rounded-md text-sm w-8 h-8 absolute top-6 end-2.5 inline-flex items-center justify-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close menu</span>
                </button>
                <div class="pt-2 pb-4 overflow-y-auto">
                    <div class="space-y-1">
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('dashboard')"
                            :active="route().current('dashboard')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="flex-shrink-0 w-5 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                viewBox="0 0 16 16">
                                <path
                                    d="M6 1v3H1V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm14 12v3h-5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1zM6 8v7H1V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1zm14-6v7h-5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1z" />
                            </svg>
                            <span class="text-sm">Dashboard</span>
                        </ResponsiveNavLink>
                        <hr>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.products')"
                            :active="route().current('show.products') || route().current('show.categories')">
                            <Person widht="16" height="16" />
                            <span class="text-sm">Penjual</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.products')"
                            :active="route().current('show.products') || route().current('show.categories')">
                            <BoxSeam widht="16" height="16" />
                            <span class="text-sm">Produk</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.users')"
                            :active="route().current('show.users')">
                            <People widht="16" height="16" />
                            <span class="text-sm">Pengguna</span>
                        </ResponsiveNavLink>
                        <hr>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.orders')"
                            :active="route().current('show.orders')">
                            <Inbox width="16" height="16" />
                            <span class="text-sm">Pesanan</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.payments')"
                            :active="route().current('show.payments')">
                            <CreditCard width="16" height="16" />
                            <span class="text-sm">Pembayaran</span>
                        </ResponsiveNavLink>
                        <hr>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.settings')"
                            :active="route().current('show.settings')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-gear" viewBox="0 0 16 16">
                                <path
                                    d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0" />
                                <path
                                    d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z" />
                            </svg>
                            <span class="text-sm">Pengaturan</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('dashboard')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-info-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path
                                    d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                            </svg>
                            <span class="text-sm">Info</span>
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>

            <!-- drawer user component -->
            <div v-if="isUser" id="drawer-navigation-user"
                class="fixed top-0 left-0 z-50 h-screen py-7 overflow-y-auto transition-transform -translate-x-full bg-white w-64"
                tabindex="-1" aria-labelledby="drawer-navigation-label">
                <h5 id="drawer-navigation-label" class="text-base font-semibold text-gray-500 px-4 pb-7">
                    Menu - Pembeli
                </h5>
                <hr>
                <button type="button" data-drawer-hide="drawer-navigation-user" aria-controls="drawer-navigation-user"
                    class="text-gray-400 bg-transparent hover:bg-blue-100 hover:text-gray-900 rounded-md text-sm w-8 h-8 absolute top-6 end-2.5 inline-flex items-center justify-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close menu</span>
                </button>
                <div class="pt-2 pb-4 overflow-y-auto">
                    <div class="space-y-1">
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('dashboard')"
                            :active="route().current('dashboard')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="flex-shrink-0 w-5 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                viewBox="0 0 16 16">
                                <path
                                    d="M6 1v3H1V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm14 12v3h-5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1zM6 8v7H1V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1zm14-6v7h-5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1z" />
                            </svg>
                            <span class="text-sm">Dashboard</span>
                        </ResponsiveNavLink>
                        <hr>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.carts')"
                            :active="route().current('show.carts')">
                            <Cart width="16" height="16" />
                            <span class="text-sm">Keranjang</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.orders')"
                            :active="route().current('show.orders')">
                            <Inbox width="16" height="16" />
                            <span class="text-sm">Pesanan</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.payments')"
                            :active="route().current('show.payments')">
                            <CreditCard width="16" height="16" />
                            <span class="text-sm">Pembayaran</span>
                        </ResponsiveNavLink>
                        <hr>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('dashboard')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-info-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path
                                    d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                            </svg>
                            <span class="text-sm">Info</span>
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
            <!-- Page Content -->
            <main class="animate-fade shadow-sm mb-2">
                <slot />
            </main>
            <!-- Page Footer -->
            <footer class="animate-fade-up pb-4 text-center text-sm text-gray-900 bg-white">
                <Footer />
            </footer>
        </div>
    </div>
</template>
