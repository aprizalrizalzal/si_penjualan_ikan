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

const props = defineProps({
    orders: Array,
});

const searchQuery = ref('');

const filteredOrdersSearch = computed(() => {
    if (!searchQuery.value) {
        return props.orders;
    }
    return props.orders.filter(order =>
        order.id.toString().includes(searchQuery.value.toLowerCase()) ||
        order.status.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const currentPage = ref(1);
const itemsPerPage = 5;

const paginatedOrders = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredOrdersSearch.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredOrdersSearch.value.length / itemsPerPage);
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

const selectedOrder = ref(null);

const confirmingOrderDeletion = ref(false);

const confirmOrderDeletion = (order) => {
    confirmingOrderDeletion.value = true;
    selectedOrder.value = order;
};

const deleteOrder = () => {
    router.delete(route('destroy.order', { id: selectedOrder.value.id }), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        },
    });
};

const closeModal = () => {
    confirmingOrderDeletion.value = false;
};
</script>

<template>

    <Head title="Orders" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pesanan</h2>
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
                                <th scope="col" class="px-3 py-3 truncate">ID Pesanan</th>
                                <th scope="col" class="px-3 py-3 truncate">Status</th>
                                <th scope="col" class="px-3 py-3 truncate">Total</th>
                                <th scope="col" class="px-3 py-3 truncate">Item Pesanan</th>
                                <th scope="col" class="px-2 py-3 text-center truncate" colspan="2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(order, index) in paginatedOrders" :key="order.id"
                                class="bg-white border-b hover:bg-blue-100">
                                <td class="w-4 p-4 text-center">{{ (currentPage - 1) * itemsPerPage + index + 1 }}.</td>
                                <td class="px-3 py-3 truncate">{{ order.id }}</td>
                                <td class="px-3 py-3 truncate capitalize">{{ order.status }}</td>
                                <td class="px-3 py-3 truncate">Rp {{ order.total_amount }}</td>
                                <td class="px-3 py-3 truncate">
                                    <ul>
                                        <li v-for="orderItem in order.order_items" :key="orderItem.id">
                                            {{ orderItem.product.name }} - Kuantitas: {{ orderItem.quantity }} | Harga:
                                            Rp
                                            {{
                                                orderItem.price
                                            }}
                                        </li>
                                    </ul>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Edit Order -->
                                    <a href="#" type="button" @click="showModalAssignRole(order)"
                                        class="flex gap-2 items-center justify-center font-normal px-2 text-blue-600 hover:underline">
                                        <PencilSquare width="16" height="16" />Sunting
                                    </a>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Delete Order -->
                                    <a href="#" type="button" @click="confirmOrderDeletion(order)"
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

                <!-- Delete order modal -->
                <Modal :show="confirmingOrderDeletion">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus pesanan ID <strong>{{ selectedOrder.id }}</strong>?
                        </h2>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah pesanan ID <strong>{{ selectedOrder.id }}</strong> dihapus, semua
                            sumber daya dan datanya akan dihapus secara permanen.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                            <DangerButton class="ms-3" @click="deleteOrder">Hapus</DangerButton>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
