<script setup>
import { computed, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Images from './Banners/Images.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PlusCircle from '@/Components/Icons/PlusCircle.vue';
import Trash3 from '@/Components/Icons/Trash3.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    banners: Array,
});

const currentPage = ref(1);
const itemsPerPage = ref(4);

const paginatedBanners = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return props.banners.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(props.banners.length / itemsPerPage.value);
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

const showingModalAddBannerImage = ref(false);
const confirmingBannerImageDeletion = ref(false);
const selectedBanner = ref(false);

const showModalAddBanner = (banner) => {
    selectedBanner.value = banner;
    showingModalAddBannerImage.value = true;
};

const confirmBannerImageDeletion = (banner) => {
    selectedBanner.value = banner;
    confirmingBannerImageDeletion.value = true;
};

const deleteBannerImage = () => {
    router.delete(route('destroy.banner.image', { id: selectedBanner.value.id }), {
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

const closeModal = () => {
    showingModalAddBannerImage.value = false;
    confirmingBannerImageDeletion.value = false;
};

</script>

<template>

    <Head title="Setting" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center ms-2">

                <h2 class="font-bold text-gray-700 text-lg leading-4 flex-none px-2 py-4">Pengaturan</h2>
            </div>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center justify-between sm:flex-row flex-col gap-4 p-2 sm:px-0 bg-white">
                    <div class="flex items-center gap-2 px-2 me-auto">
                        <PrimaryButton @click="showModalAddBanner" class="gap-2 py-2.5 capitalize">
                            <PlusCircle width="16" height="16" />Banner
                        </PrimaryButton>
                    </div>
                </div>
                <div class="overflow-x-auto pb-4 px-2 bg-white">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-blue-100">
                            <tr>
                                <th scope="col" class="px-3 py-3 truncate">
                                    No.
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Gambar
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Alt
                                </th>
                                <th scope="col" class="px-2 py-3 text-center truncate">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(banner, index) in paginatedBanners" :key="banner.id"
                                class="bg-white border-b hover:bg-blue-100">
                                <td class="w-4 p-4 text-center"> {{ (currentPage - 1) * itemsPerPage + index + 1 }}.
                                </td>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <img :src="banner.image" :alt="banner.alt" class="h-16 w-32 object-cover "
                                        style="max-width: 128px;" />
                                </td>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <div class="font-normal text-gray-500">{{ banner.alt }}</div>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Modal toggle Hapus-->
                                    <a href="#" type="button" @click="confirmBannerImageDeletion(banner)"
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
            </div>
        </div>
        <Modal :show="showingModalAddBannerImage">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900">
                        Tambah Banner
                    </h2>
                    <DangerButton @click="closeModal">X</DangerButton>
                </div>
                <Images :banner="banners" @addBannerImage="closeModal" />
            </div>
        </Modal>

        <Modal :show="confirmingBannerImageDeletion">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Apakah Anda yakin ingin menghapus banner?
                </h2>
                <p class="mt-1 text-sm text-gray-700">
                    Setelah banner dihapus,
                    semua
                    sumber daya
                    dan
                    datanya
                    akan dihapus secara permanen.
                </p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                    <DangerButton @click="deleteBannerImage" class="ms-3">
                        Hapus
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>

</template>
