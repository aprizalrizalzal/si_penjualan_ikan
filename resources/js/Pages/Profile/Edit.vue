<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import Customers from '../Users/Customers/Customers.vue';

defineProps({
    customer: Object,
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>

    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="animate-fade-up p-4 sm:p-8 bg-white hover:shadow ">
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">
                        <Customers :customer="customer" />
                        <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status"
                            :customer="customer" />
                    </div>
                </div>

                <div class="animate-fade-right p-4 sm:p-8 bg-white hover:shadow ">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="animate-fade-left p-4 sm:p-8 bg-white hover:shadow ">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
