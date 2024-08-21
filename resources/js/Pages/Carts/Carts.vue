<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SearchInput from '@/Components/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PencilSquare from '@/Components/Icons/PencilSquare.vue';
import Trash3 from '@/Components/Icons/Trash3.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    carts: Array,
});

const form = useForm({
    quantity: '',
});

const searchQuery = ref('');

const filteredCartsSearch = computed(() => {
    if (!searchQuery.value) {
        return props.carts;
    }
    return props.carts.filter(cart =>
        cart.id.toString().includes(searchQuery.value.toLowerCase())
    );
});

const currentPage = ref(1);
const itemsPerPage = 5;

const paginatedCarts = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredCartsSearch.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredCartsSearch.value.length / itemsPerPage);
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

const selectedCart = ref(null);

const showingModalQuantityUpdate = ref(false);
const confirmingCartDeletion = ref(false);

const showModalQuantityUpdate = (cart) => {
    showingModalQuantityUpdate.value = true;
    selectedCart.value = cart;
};

if (selectedCart) {
    form.quantity = 1
}

const confirmCartDeletion = (cart) => {
    confirmingCartDeletion.value = true;
    selectedCart.value = cart;
};

const deleteCart = () => {
    router.delete(route('destroy.cart', { id: selectedCart.value.id }), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        },
    });
};

const closeModal = () => {
    showingModalQuantityUpdate.value = false;
    confirmingCartDeletion.value = false;
};
</script>

<template>

    <Head title="Carts" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Keranjang</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="flex items-center justify-between sm:flex-row flex-col gap-4 pt-2 pb-4 px-4 sm:px-0 bg-white">
                    <SearchInput v-model:searchQuery="searchQuery" placeholder="Cari Pesanan" />
                </div>
                <div class="overflow-x-auto sm:rounded-md pb-4">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-blue-100">
                            <tr>
                                <th scope="col" class="px-3 py-3 truncate">No.</th>
                                <th scope="col" class="px-3 py-3 truncate">Produk</th>
                                <th scope="col" class="px-3 py-3 truncate">Berat</th>
                                <th scope="col" class="px-3 py-3 truncate">Harga</th>
                                <th scope="col" class="px-3 py-3 truncate">Kuantitas</th>
                                <th scope="col" class="px-3 py-3 truncate">Total</th>
                                <th scope="col" class="px-2 py-3 text-center truncate">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(cart, index) in paginatedCarts" :key="cart.id"
                                class="bg-white border-b hover:bg-blue-100">
                                <td class="w-4 p-4 text-center">{{ (currentPage - 1) * itemsPerPage + index + 1 }}.</td>
                                <td class="px-3 py-3 truncate">{{ cart.product.name }}</td>
                                <td class="px-3 py-3 truncate">{{ cart.product.weight }} Kg</td>
                                <td class="px-3 py-3 truncate">{{ $formatCurrency(cart.product.price) }}</td>
                                <td class="px-3 py-3 truncate">
                                    <a href="#" type="button" @click="showModalQuantityUpdate(cart)"
                                        class="flex gap-2 items-center font-normal text-blue-600 hover:underline">
                                        {{ cart.quantity }}
                                        <PencilSquare width="16" height="16" />
                                    </a>
                                </td>
                                <td class="px-3 py-3 truncate">{{ $formatCurrency(cart.product.price * cart.quantity) }}
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Delete cart -->
                                    <a href="#" type="button" @click="confirmCartDeletion(cart)"
                                        class="flex gap-2 items-center justify-center font-normal px-2 text-red-600 hover:underline">
                                        <Trash3 width="16" height="16" />Hapus
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center gap-4 items-center p-6">
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

                <!-- Update order item modal -->
                <Modal :show="showingModalQuantityUpdate">
                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900">
                                Kuantitas <strong>{{ selectedCart.product.name }}</strong>
                            </h2>
                            <DangerButton @click="closeModal">X</DangerButton>
                        </div>
                        <form @submit.prevent="submitForm" class="mt-3 space-y-3">
                            <div>
                                <InputLabel for="quantity" value="Kuantitas" />
                                <TextInput id="quantity" type="text" class="mt-1 block w-full" v-model="form.quantity"
                                    placeholder="Kuantitas" required autofocus />
                                <InputError class="mt-2" :message="form.errors.quantity" />
                            </div>
                            <div class="mt-6 flex justify-start">
                                <PrimaryButton>Simpan</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </Modal>

                <!-- Delete cart modal -->
                <Modal :show="confirmingCartDeletion">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus pesanan ID <strong>{{ selectedCart.id }}</strong>?
                        </h2>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah pesanan ID <strong>{{ selectedCart.id }}</strong> dihapus, semua
                            sumber daya dan datanya akan dihapus secara permanen.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                            <DangerButton class="ms-3" @click="deleteCart">Hapus</DangerButton>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
