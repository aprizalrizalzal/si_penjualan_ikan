<script setup>
import CreditCard from '@/Components/Icons/CreditCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const { auth } = usePage().props;
const roles = ref(auth.roles);
const hasRole = (role) => roles.value.includes(role);

const isAdmin = computed(() => hasRole('admin'));
const isUser = computed(() => hasRole('user'));
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="isUser" class="bg-white overflow-hidden hover:shadow-sm sm:rounded-lg py-6">
                    <ol class="flex items-center w-full mb-4 sm:mb-5 py-8">
                        <li
                            class="flex w-full items-center text-blue-500 after:content-['Check'] after:w-full after:h-1 after:border-b after:border-blue-50 after:border-4 after:inline-block ">
                            <a href="#"
                                class="flex items-center justify-center hover:w-16 hover:h-16 hover:bg-blue-200 w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-check-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                    <path
                                        d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                                </svg>
                            </a>
                        </li>
                        <li
                            class="flex w-full items-center text-blue-500 after:content-['Pending'] after:w-full after:h-1 after:border-b after:border-blue-100 after:border-4 after:inline-block">
                            <a href="#"
                                class="flex items-center justify-center hover:w-16 hover:h-16 hover:bg-blue-200 w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-hourglass" viewBox="0 0 16 16">
                                    <path
                                        d="M2 1.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1-.5-.5m2.5.5v1a3.5 3.5 0 0 0 1.989 3.158c.533.256 1.011.791 1.011 1.491v.702c0 .7-.478 1.235-1.011 1.491A3.5 3.5 0 0 0 4.5 13v1h7v-1a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351v-.702c0-.7.478-1.235 1.011-1.491A3.5 3.5 0 0 0 11.5 3V2z" />
                                </svg>
                            </a>
                        </li>
                        <li
                            class="flex w-full items-center text-blue-500 after:content-['Paid'] after:w-full after:h-1 after:border-b after:border-blue-200 after:border-4 after:inline-block">
                            <a href="#"
                                class="flex items-center justify-center hover:w-16 hover:h-16 hover:bg-blue-200 w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                                <CreditCard />
                            </a>
                        </li>
                        <li
                            class="flex w-full items-center text-blue-500 after:content-['Shipped'] after:w-full after:h-1 after:border-b after:border-blue-300 after:border-4 after:inline-block">
                            <a href="#"
                                class="flex items-center justify-center hover:w-16 hover:h-16 hover:bg-blue-200 w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-truck" viewBox="0 0 16 16">
                                    <path
                                        d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                                </svg>
                            </a>
                        </li>
                        <li
                            class="flex w-full items-center text-blue-500 after:content-['Completed'] after:w-full after:h-1 after:border-b after:border-blue-400 after:border-4 after:inline-block">
                            <a href="#"
                                class="flex items-center justify-center hover:w-16 hover:h-16 hover:bg-blue-200 w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                            </a>
                        </li>
                        <li
                            class="flex w-full items-center text-red-500 after:content-['Cancelled'] after:w-full after:h-1 after:border-b after:border-red-400 after:border-4 after:inline-block">
                            <a href="#"
                                class="flex items-center justify-center hover:w-16 hover:h-16 hover:bg-red-200 w-10 h-10 bg-red-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-x-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                    <path
                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                </svg>
                            </a>
                        </li>
                    </ol>
                    <div class="py-4">
                        <h3 class="mb-4 text-lg font-medium leading-none text-gray-900">Details
                        </h3>
                        <p>
                            Pesanan sedang dalam pengecekan awal sebelum diproses lebih lanjut.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
