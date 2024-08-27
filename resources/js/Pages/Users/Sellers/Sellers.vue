<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SearchInput from '@/Components/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PencilSquare from '@/Components/Icons/PencilSquare.vue';
import Trash3 from '@/Components/Icons/Trash3.vue';
import PlusCircle from '@/Components/Icons/PlusCircle.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Images from './Images.vue';

const props = defineProps({
    sellers: Array,
});

const searchQuery = ref('');

const filteredSellersSearch = computed(() => {
    if (!searchQuery.value) {
        return props.sellers;
    }
    return props.sellers.filter(seller =>
        seller.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        seller.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const currentPage = ref(1);
const itemsPerPage = 10;

const paginatedSellers = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredSellersSearch.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredSellersSearch.value.length / itemsPerPage);
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

const form = useForm({
    image: null,
    name: '',
    email: '',
    phone: '',
    address: '',
});

const selectedSeller = ref(null);
const selectedSellerImage = ref(null);

const showingModalSeller = ref(false);
const confirmingSellerDeletion = ref(false);
const showingModalAddSellerImage = ref(false);
const confirmingSellerImageDeletion = ref(false);

const showModalAddSellerImage = (seller) => {
    selectedSeller.value = seller;
    showingModalAddSellerImage.value = true;
};

const confirmSellerImageDeletion = (seller) => {
    selectedSellerImage.value = seller.seller_image;
    confirmingSellerImageDeletion.value = true;
};

const deleteSellerImage = () => {
    router.delete(route('destroy.seller.image', { id: selectedSellerImage.value.id }), {
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

const showModalSeller = (seller) => {
    selectedSeller.value = seller;
    selectedSellerImage.value = seller.seller_image;
    showingModalSeller.value = true;

    if (selectedSeller) {
        form.image = seller.seller_image.image;
        form.name = seller.name;
        form.email = seller.email;
        form.phone = seller.phone;
        form.address = seller.address;
    }
};

const previewUrl = ref(null);

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const submitForm = () => {
    if (!selectedSeller.value.id) {
        form.post(route('store.seller', { alt: form.name }), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                previewUrl.value = null;
                closeModal();
            },
            onError: (errors) => {
                if (errors.image || errors.name || errors.email || errors.phone || errors.address) {
                    alert('addition failed!');
                } else {
                    const errorMessages = Object.values(errors).flat();
                    alert(`${errorMessages}`);
                }
            }
        });
    } else {
        form.put(route('update.seller', { id: selectedSeller.value.id }), {
            preserveScroll: true,
            onSuccess: () => {
                form.data();
                closeModal();

            },
            onError: (errors) => {
                if (errors.image || errors.name || errors.email || errors.phone || errors.address) {
                    alert('update failed!');
                } else {
                    const errorMessages = Object.values(errors).flat();
                    alert(`${errorMessages}`);
                }
            }
        });
    }
};

const confirmSellerDeletion = (seller) => {
    confirmingSellerDeletion.value = true;
    selectedSeller.value = seller;
    form.id = seller.id;
};

const deleteSeller = () => {
    router.delete(route('destroy.seller', { id: selectedSeller.value.id }), {
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
    showingModalSeller.value = false;
    showingModalAddSellerImage.value = false;
    confirmingSellerImageDeletion.value = false;
    confirmingSellerDeletion.value = false;
};
</script>

<template>

    <Head title="Sellers" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Penjual</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="flex items-center justify-between sm:flex-row flex-col gap-4 pt-2 pb-4 px-4 sm:px-0 bg-white">
                    <div class="flex items-center gap-2">
                        <PrimaryButton @click="showModalSeller" class="gap-2 py-2.5 capitalize">
                            <PlusCircle width="16" height="16" />Penjual
                        </PrimaryButton>
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
                                    Foto
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Nama
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Telepon
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Alamat
                                </th>
                                <th scope="col" class="px-2 py-3 text-center truncate" colspan="2">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(seller, index) in paginatedSellers" :key="seller.id"
                                class="bg-white border-b hover:bg-blue-100">
                                <td class="w-4 p-4 text-center"> {{ (currentPage - 1) * itemsPerPage + index + 1 }}.
                                </td>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <div class="flex items-center">
                                        <div class="relative me-2">
                                            <img v-if="seller.seller_image !== null" :src="seller.seller_image.image"
                                                :alt="seller.seller_image.alt" class="h-16 w-16 object-cover rounded "
                                                style="max-width: 128px;" />
                                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="64" height="64"
                                                fill="currentColor" class="bi bi-person-circle m-auto"
                                                viewBox="0 0 16 16">
                                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                                <path fill-rule="evenodd"
                                                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                            </svg>
                                            <botton v-if="seller.seller_image !== null"
                                                @click="confirmSellerImageDeletion(seller)"
                                                class="absolute top-0.5 right-0.5 inline-flex bg-white items-center p-0.5 rounded font-semibold text-xs text-red-900 tracking-widest shadow hover:bg-red-100 focus:outline-none focus:ring-1 focus:ring-red-900 opacity-75 transition ease-in-out duration-150">
                                                <Trash3 width="16" height="16" class="hover:w-6 hover:h-6" />
                                            </botton>
                                        </div>
                                        <botton v-if="seller.seller_image === null"
                                            @click="showModalAddSellerImage(seller)"
                                            class="bg-white items-center p-0.5 rounded font-semibold text-xs text-blue-900 tracking-widest hover:bg-blue-100 focus:outline-none focus:ring-1 focus:ring-blue-900 transition ease-in-out duration-150">
                                            <PlusCircle width="16" height="16" class="hover:w-6 hover:h-6" />
                                        </botton>
                                    </div>
                                </td>
                                <th scope="row" class="flex items-center px-2 py-3 text-gray-900 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-base font-semibold">{{ seller.name }}</div>
                                        <div class="font-normal text-gray-500">{{ seller.email }}</div>
                                    </div>
                                </th>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <div class="font-normal text-gray-500">{{ seller.phone }}</div>
                                </td>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <div class="font-normal text-gray-500">{{ seller.address }}</div>
                                </td>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <a href="#" type="button" @click="showModalSeller(seller)"
                                        class="flex gap-2 items-center font-normal text-blue-600 hover:underline">
                                        Sunting
                                        <PencilSquare width="16" height="16" />
                                    </a>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Modal toggle delete-->
                                    <a href="#" type="button" @click="confirmSellerDeletion(seller)"
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

                <Modal :show="showingModalSeller">
                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <h2 v-if="!selectedSeller.id" class="text-lg font-medium text-gray-900">
                                Tambah Seller
                            </h2>
                            <h2 v-else class="text-lg font-medium text-gray-900">
                                Sunting Seller
                            </h2>
                            <DangerButton @click="closeModal">X</DangerButton>
                        </div>
                        <div class="flex w-full flex-1 items-stretch">
                            <div class="w-full">
                                <form @submit.prevent="submitForm" class="mt-3 space-y-3">
                                    <div v-if="!selectedSeller.id">
                                        <InputLabel for="image" value="Foto" />
                                        <input type="file" id="image" @change="handleFileChange"
                                            class="mt-1 block w-full" />
                                        <InputError :message="form.errors.image" />
                                    </div>
                                    <div>
                                        <InputLabel for="name" value="Nama" />
                                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name"
                                            placeholder="Nama" required autofocus />
                                        <InputError class="mt-2" :message="form.errors.name" />
                                    </div>
                                    <div>
                                        <InputLabel for="email" value="Email" />
                                        <TextInput id="email" type="email" inputmode="numeric" class="mt-1 block w-full"
                                            v-model="form.email" placeholder="Email" required />
                                        <InputError class="mt-2" :message="form.errors.email" />
                                    </div>
                                    <div>
                                        <InputLabel for="phone" value="Telepon" />
                                        <TextInput id="phone" type="text" inputmode="numeric" class="mt-1 block w-full"
                                            v-model="form.phone" placeholder="Telepon" required />
                                        <InputError class="mt-2" :message="form.errors.phone" />
                                    </div>
                                    <div>
                                        <InputLabel for="address" value="Alamat" />
                                        <textarea id="address" type="text"
                                            class="mt-1 block w-full border-blue-300 focus:border-blue-700 focus:ring-blue-700 rounded shadow"
                                            v-model="form.address" placeholder="Alamat" required />
                                        <InputError class="mt-2" :message="form.errors.address" />
                                    </div>
                                    <div v-if="previewUrl" class="my-4">
                                        <p class="font-semibold">Pratinjau</p>
                                        <img :src="previewUrl" alt="Image Preview" class="w-full h-auto mt-2 rounded" />
                                    </div>
                                    <div>
                                        <PrimaryButton class="mt-6 mb-3" :class="{ 'opacity-25': form.processing }"
                                            :disabled="form.processing">
                                            Simpan
                                        </PrimaryButton>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </Modal>

                <!-- Delete seller modal -->
                <Modal :show="confirmingSellerDeletion">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus penjual <strong>{{ selectedSeller.name }}</strong>?
                        </h2>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah penjual <strong>{{ selectedSeller.name }}</strong> dihapus, semua sumber daya dan
                            datanya
                            akan dihapus secara permanen.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                            <DangerButton class="ms-3" @click="deleteSeller">Hapus</DangerButton>
                        </div>
                    </div>
                </Modal>

                <!-- Store seller image modal-->
                <Modal :show="showingModalAddSellerImage">
                    <div class="m-6">
                        <div class="flex justify-between items-center ps-6 ms-6 text-blue-900">
                            <span class="font-bold text-center w-full">Unggah Foto</span>
                            <DangerButton @click="closeModal">X</DangerButton>
                        </div>
                        <Images :seller="selectedSeller" @addSellerImage="closeModal" />
                    </div>
                </Modal>

                <Modal :show="confirmingSellerImageDeletion">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus foto profil?
                        </h2>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah foto profil dihapus,
                            semua
                            sumber daya
                            dan
                            datanya
                            akan dihapus secara permanen.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                            <DangerButton @click="deleteSellerImage" class="ms-3">
                                Hapus
                            </DangerButton>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>