<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SearchInput from '@/Components/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import AssigningRole from './AssigningRole.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PencilSquare from '@/Components/Icons/PencilSquare.vue';
import Trash3 from '@/Components/Icons/Trash3.vue';

const props = defineProps({
    users: Array,
});

const getUserRoles = (user) => {
    return user.roles.length > 0 ? user.roles : [];
};

const { auth } = usePage().props;
const userId = ref(auth.user.id);

const filteredUsersId = computed(() => {
    return props.users.filter(user => user.id !== userId.value);
});

const searchQuery = ref('');

const filteredUsersSearch = computed(() => {
    if (!searchQuery.value) {
        return filteredUsersId.value;
    }
    return filteredUsersId.value.filter(user =>
        user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        user.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const currentPage = ref(1);
const itemsPerPage = 4;

const paginatedUsers = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredUsersSearch.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredUsersSearch.value.length / itemsPerPage);
});

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const selectedUser = ref(null);

const confirmingUserDeletion = ref(false);

const form = useForm({
});

const confirmUserDeletion = (user) => {
    confirmingUserDeletion.value = true;
    selectedUser.value = user;
    form.id = user.id;
};

const deleteUser = () => {
    router.delete(route('destroy.user', { id: selectedUser.value.id }), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        },
        onError: (errors) => {
            if (errors) {
                closeModal();
            } else {
                const errorMessages = Object.values(errors).flat();
                alert(`${errorMessages}`);
            }
        }
    });
};

const showingModalassignRole = ref(false);

const showModalAssignRole = (user) => {
    selectedUser.value = user;
    showingModalassignRole.value = true;
};

const showingModalAssignSuccessfully = ref(false);

const showModalAssignSuccessfully = () => {
    showingModalassignRole.value = false;
    showingModalAssignSuccessfully.value = true;
};

const closeModalAssignSuccessfully = () => {
    showingModalAssignSuccessfully.value = false;
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    showingModalassignRole.value = false;
};
</script>

<template>

    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengguna</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="flex items-center justify-between sm:flex-row flex-col gap-4 pt-2 pb-4 px-4 sm:px-0 bg-white">
                    <div class="flex items-center gap-2">
                    </div>
                    <SearchInput v-model:searchQuery="searchQuery" placeholder="Cari" />
                </div>
                <div class="overflow-x-auto sm:rounded-md pb-4">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-blue-100">
                            <tr>
                                <th scope="col" class="px-3 py-3 truncate">
                                    No.
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Nama
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Peran
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Status E-mail
                                </th>
                                <th scope="col" class="px-2 py-3 text-center truncate">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in paginatedUsers" :key="user.id"
                                class="bg-white border-b hover:bg-blue-100">
                                <td class="w-4 p-4 text-center"> {{ (currentPage - 1) * itemsPerPage + index + 1 }}.
                                </td>
                                <th scope="row" class="flex items-center px-2 py-3 text-gray-900 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-base font-semibold">{{ user.name }}</div>
                                        <div class="font-normal text-gray-500">{{ user.email }}</div>
                                    </div>
                                </th>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <a href="#" type="button" @click="showModalAssignRole(user)"
                                        class="flex gap-2 items-center font-normal text-blue-600 hover:underline">
                                        <span v-for="role in getUserRoles(user)" :key="role.id">#{{ role.name }}</span>
                                        <PencilSquare width="16" height="16" />
                                    </a>
                                </td>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <div v-if="user.email_verified_at !== null"
                                        class="flex items-center gap-1 text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                                            <path
                                                d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                                        </svg>
                                        <span>Terverifikasi</span>
                                    </div>
                                    <div v-else class="flex items-center gap-1 text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                            <path
                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                            <path
                                                d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                        </svg>
                                        <span>Belum Diverifikasi</span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Modal toggle delete-->
                                    <a href="#" type="button" @click="confirmUserDeletion(user)"
                                        class="flex gap-2 items-center justify-center font-normal px-2 text-red-600 hover:underline">
                                        <Trash3 width="16" height="16" />Hapus
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center gap-4 items-center p-2">
                    <SecondaryButton @click="previousPage" :disabled="currentPage === 1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-chevron-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" />
                        </svg>
                    </SecondaryButton>
                    <span>{{ currentPage }} / {{ totalPages }}</span>
                    <SecondaryButton @click="nextPage" :disabled="currentPage === totalPages">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-chevron-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                        </svg>
                    </SecondaryButton>
                </div>
                <!-- Assigning roles modal -->
                <Modal :show="showingModalassignRole">
                    <div class="m-6">
                        <div class="flex justify-between items-center text-gray-900">
                            <h2 class="text-lg font-medium text-gray-900">
                                Peran
                            </h2>
                            <DangerButton @click="closeModal">X</DangerButton>
                        </div>
                        <hr class="mt-4 mb-2">
                        <AssigningRole :user="selectedUser" @assignRole="showModalAssignSuccessfully" />
                    </div>
                </Modal>

                <Modal maxWidth="xl" :show="showingModalAssignSuccessfully">
                    <div class="p-6">
                        <div class="flex justify-between items-center text-gray-900">
                            <h2 class="text-lg font-medium text-gray-900">
                                Peran
                            </h2>
                            <DangerButton @click="closeModalAssignSuccessfully">X</DangerButton>
                        </div>
                        <hr class="mt-4 mb-2">
                        <p class="mt-1 text-sm text-gray-700">
                            Penetapan Peran Pengguna Berhasil!
                        </p>
                        <div class="mt-6 flex justify-start">
                            <PrimaryButton @click="closeModalAssignSuccessfully">Ok</PrimaryButton>
                        </div>
                    </div>
                </Modal>
                <!-- Delete user modal -->
                <Modal :show="confirmingUserDeletion">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus pengguna <strong>{{ selectedUser.name }}</strong>?
                        </h2>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah pengguna <strong>{{ selectedUser.name }}</strong> dihapus, semua sumber daya dan
                            datanya
                            akan dihapus secara permanen.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                            <DangerButton class="ms-3" @click="deleteUser">Hapus</DangerButton>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>