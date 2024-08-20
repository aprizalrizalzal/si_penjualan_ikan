<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { initFlowbite } from 'flowbite'
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import Footer from '@/Pages/Footer.vue';

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
                'animate-fade-down fixed left-0 top-0 w-full bg-white transition-shadow duration-300',
                { 'shadow-sm': hasScrolled },
            ]">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center h-20">
                        <!-- drawer init and show -->
                        <button
                            class="animate-fade-right px-2 my-5 px-2 py-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 focus:text-gray-700 transition duration-150 ease-in-out"
                            type="button" data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation"
                            aria-controls="drawer-navigation">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-chevron-compact-right animate-pulse" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671" />
                            </svg>
                        </button>
                        <div class="flex me-auto truncate">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('welcome')">
                                <ApplicationLogo class="w-16 h-auto" />
                                </Link>
                                <h1 class="px-4 text-lg text-gray-800">SI Penjual Ikan</h1>
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
            <!-- drawer component -->
            <div id="drawer-navigation"
                class="fixed top-0 left-0 z-40 h-screen py-7 overflow-y-auto transition-transform -translate-x-full bg-white w-64"
                tabindex="-1" aria-labelledby="drawer-navigation-label">
                <h5 id="drawer-navigation-label" class="text-base font-semibold text-gray-500 px-4 pb-7">
                    MENU - SIService
                </h5>
                <hr>
                <button type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation"
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
                        <!-- <ResponsiveNavLink class="flex items-center gap-2" :href="route('dashboard')"
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
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.users')"
                            :active="route().current('show.users')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="flex-shrink-0 w-5 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                            </svg>
                            <span class="text-sm">Pengguna</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.spare.parts')"
                            :active="route().current('show.spare.parts') || route().current('show.spare.parts.categories')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="flex-shrink-0 w-5 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z" />
                            </svg>
                            <span class="text-sm">Suku Cadang</span>
                        </ResponsiveNavLink>
                        <hr>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.customers')"
                            :active="route().current('show.customers')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="flex-shrink-0 w-5 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                viewBox="0 0 16 16">
                                <path
                                    d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                            </svg>
                            <span class="text-sm">Pelanggan</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.devices')"
                            :active="route().current('show.devices') || route().current('show.device.types')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="flex-shrink-0 w-5 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                viewBox="0 0 16 16">
                                <path
                                    d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5" />
                            </svg>
                            <span class="text-sm">Perangkat</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.services')"
                            :active="route().current('show.services') || route().current('show.service.statuses')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="flex-shrink-0 w-5 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2" />
                            </svg>
                            <span class="text-sm">Layanan</span>
                        </ResponsiveNavLink>
                        <hr>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.service.details')"
                            :active="route().current('show.service.details')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="flex-shrink-0 w-5 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                viewBox="0 0 16 16">
                                <path
                                    d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z" />
                                <path
                                    d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z" />
                                <path
                                    d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                            </svg>
                            <span class="text-sm">Rincian Layanan</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('show.part.usages')"
                            :active="route().current('show.part.usages')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="flex-shrink-0 w-5 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                viewBox="0 0 16 16">
                                <path
                                    d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434zM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567zM7.5 9.933l-2.75 1.571v3.134l2.75-1.571zm1 3.134 2.75 1.571v-3.134L8.5 9.933zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567zm2.242-2.433V3.504L8.5 5.076V8.21zM7.5 8.21V5.076L4.75 3.504v3.134zM5.258 2.643 8 4.21l2.742-1.567L8 1.076zM15 9.933l-2.75 1.571v3.134L15 13.067zM3.75 14.638v-3.134L1 9.933v3.134z" />
                            </svg>
                            <span class="text-sm">Penggunaan Suku Cadang</span>
                        </ResponsiveNavLink>
                        <hr>
                        <ResponsiveNavLink class="flex items-center gap-2" :href="route('dashboard')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="flex-shrink-0 w-5 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0" />
                                <path
                                    d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z" />
                            </svg>
                            <span class="text-sm">Pengaturan</span>
                        </ResponsiveNavLink> -->
                        <!-- <div>
                            <button type="button"
                                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-md group hover:bg-blue-100"
                                aria-controls="dropdown-device-types" data-collapse-toggle="dropdown-device-types">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-display flex-shrink-0 w-5 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M0 4s0-2 2-2h12s2 0 2 2v6s0 2-2 2h-4q0 1 .25 1.5H11a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1h.75Q6 13 6 12H2s-2 0-2-2zm1.398-.855a.76.76 0 0 0-.254.302A1.5 1.5 0 0 0 1 4.01V10c0 .325.078.502.145.602q.105.156.302.254a1.5 1.5 0 0 0 .538.143L2.01 11H14c.325 0 .502-.078.602-.145a.76.76 0 0 0 .254-.302 1.5 1.5 0 0 0 .143-.538L15 9.99V4c0-.325-.078-.502-.145-.602a.76.76 0 0 0-.302-.254A1.5 1.5 0 0 0 13.99 3H2c-.325 0-.502.078-.602.145" />
                                </svg>

                                <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Device Types</span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <div id="dropdown-device-types" class="hidden py-2 space-y-2">
                                <div>
                                    <a href="#"
                                        class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-md pl-11 group hover:bg-blue-100">Products</a>
                                </div>
                                <div>
                                    <a href="#"
                                        class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-md pl-11 group hover:bg-blue-100">Billing</a>
                                </div>
                                <div>
                                    <a href="#"
                                        class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-md pl-11 group hover:bg-blue-100">Invoice</a>
                                </div>
                            </div>
                        </div> -->
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
