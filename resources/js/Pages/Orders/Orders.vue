<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SearchInput from '@/Components/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Trash3 from '@/Components/Icons/Trash3.vue';
import CardHeading from '@/Components/Icons/CardHeading.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CreditCard from '@/Components/Icons/CreditCard.vue';
import PlusCircle from '@/Components/Icons/PlusCircle.vue';
import DropdownSelect from '@/Components/DropdownSelect.vue';

const props = defineProps({
    orders: Array,
});

const form = useForm({
    order_id: '',
    amount: '',
    payment_method: '',
    status: '',
});

const goToPayments = () => {
    router.visit(route('show.payments'))
}

const searchQuery = ref('');

const filteredOrdersSearch = computed(() => {
    if (!searchQuery.value) {
        return props.orders;
    }
    return props.orders.filter(order =>
        order.order_code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        order.status.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const currentPage = ref(1);
const itemsPerPage = 10;

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
const selectedOrderItems = ref(null);

const showingModalOrderItems = ref(false);
const confirmingOrderDeletion = ref(false);
const confirmingOrderPayment = ref(false);

const showModalOrderItems = (order) => {
    showingModalOrderItems.value = true;
    selectedOrder.value = order;
    selectedOrderItems.value = order.order_items;
};

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

const totalAmount = computed(() => {
    return filteredOrdersSearch.value.reduce((total, order) => {
        const amount = parseFloat(order.total_amount);
        return total + (isNaN(amount) ? 0 : amount);
    }, 0);
});

const detailTotalAmount = computed(() => {
    return selectedOrderItems.value.reduce((total, orderItems) => {
        const totalAmount = parseFloat(orderItems.product.price * orderItems.quantity);
        return total + (isNaN(totalAmount) ? 0 : totalAmount);
    }, 0);
});

const paymentMethods = [
    { id: 1, name: 'Bank Transfer' },
    { id: 2, name: 'Cash on Delivery' },
    { id: 3, name: 'E-Wallet' }
];

const confirmOrderPayment = (order) => {
    confirmingOrderPayment.value = true;
    selectedOrder.value = order;
    if (order) {
        form.order_id = order.id;
        form.amount = order.total_amount;
        form.status = 'pending';
    }
};

const storePayment = () => {
    form.post(route('store.order.payment'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        },
        onError: (errors) => {
            if (errors) {
                alert('Cart failed!');
            } else {
                const errorMessages = Object.values(errors).flat();
                alert(`${errorMessages}`);
            }
        }
    });
}

const goToCart = () => {
    router.visit(route('show.carts'))
}

const closeModal = () => {
    showingModalOrderItems.value = false;
    confirmingOrderDeletion.value = false;
    confirmingOrderPayment.value = false;
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
                    <div class="flex items-center gap-2">
                        <SecondaryButton @click="goToPayments" class="gap-2 shadow-none py-2.5 capitalize">
                            <CreditCard width="16" height="16" /> Pembayaran
                        </SecondaryButton>
                    </div>
                    <SearchInput v-model:searchQuery="searchQuery" placeholder="Cari" />
                </div>
                <div class="overflow-x-auto sm:rounded-md pb-4">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-blue-100">
                            <tr>
                                <th scope="col" class="px-3 py-3 truncate">No.</th>
                                <th scope="col" class="px-3 py-3 truncate">Kode Pesanan</th>
                                <th scope="col" class="px-3 py-3 truncate">Status</th>
                                <th scope="col" class="px-3 py-3 truncate">Total</th>
                                <th scope="col" class="px-2 py-3 text-center truncate" colspan="2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(order, index) in paginatedOrders" :key="order.id"
                                class="bg-white border-b hover:bg-blue-100">
                                <td class="w-4 p-4 text-center">{{ (currentPage - 1) * itemsPerPage + index + 1 }}.</td>
                                <td class="px-3 py-3 truncate">{{ order.order_code }}</td>
                                <td class="px-3 py-3 text-blue-600 truncate capitalize">{{ order.status }}</td>
                                <td class="px-3 py-3 truncate">{{ $formatCurrency(order.total_amount) }}</td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Modal toggle Detail-->
                                    <a href="#" type="button" @click="showModalOrderItems(order)"
                                        class="flex gap-2 items-center justify-center font-normal px-2 text-gray-600 hover:underline">
                                        <CardHeading width="16" height="16" />Detail
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
                        <tfoot class="text-xs text-gray-700 uppercase bg-blue-100">
                            <tr>
                                <td class="w-4 p-4 text-center truncate">
                                    #
                                </td>
                                <td class="px-3 py-3 font-bold truncate" colspan="2">
                                    Total
                                </td>
                                <td class="px-3 py-3 font-bold truncate" colspan="3">
                                    {{ $formatCurrency(totalAmount) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="flex items-center pt-4 m-1">

                        <div v-if="orders.length < 1" class="flex items-center justify-center w-full">
                            <a href="#" type="button" @click="goToCart"
                                class="flex gap-2 items-center font-normal text-blue-600 hover:underline">
                                Pesanan masih kosong
                                <PlusCircle width="16" height="16" />
                            </a>
                        </div>
                    </div>
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
                <div class="p-2 bg-white">
                    <h3 class="text-sm font-semibold mb-2 text-gray-800">Cara Bayar</h3>
                    <ul class="space-y-1">
                        <li class="flex items-start">
                            <strong class="w-20 text-gray-800 text-sm">Langkah 1:</strong>
                            <span class="text-gray-800 text-sm">Pilih salah satu metode pembayaran: <strong>Bank
                                    Transfer, Cash
                                    on Delivery, atau E-Wallet</strong>.</span>
                        </li>
                        <li class="flex items-start">
                            <strong class="w-20 text-gray-800 text-sm">Langkah 2:</strong>
                            <span class="text-gray-800 text-sm">Tekan tombol <strong>"Detail"</strong> untuk melihat
                                daftar
                                pesanan Anda.</span>
                        </li>
                        <li class="flex items-start">
                            <strong class="w-20 text-gray-800 text-sm">Langkah 3:</strong>
                            <span class="text-gray-800 text-sm">Setelah memeriksa daftar pesanan, tekan tombol
                                <strong>"Bayar"</strong> untuk melihat informasi pembayaran seperti nomor rekening atau
                                instruksi lainnya.</span>
                        </li>
                    </ul>
                </div>

                <!-- Detail order item modal -->
                <Modal :show="showingModalOrderItems">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Detail kode pesanan <strong>{{
                                selectedOrder.order_code }}</strong>
                        </h2>
                        <table class="mt-1 w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-blue-100">
                                <tr>
                                    <th scope="col" class="ps-3 pe-6 py-1.5 truncate">No.</th>
                                    <th scope="col" class="pe-6 py-1.5 truncate">Produk</th>
                                    <th scope="col" class="pe-6 py-1.5 truncate">Berat</th>
                                    <th scope="col" class="pe-6 py-1.5 truncate">Harga</th>
                                    <th scope="col" class="pe-6 py-1.5 truncate">Kuantitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(orderItem, index) in selectedOrderItems" :key="orderItem.id"
                                    class="bg-white border-b hover:bg-blue-100">
                                    <td class="w-2 p-2 text-center">{{ index + 1 }}.</td>
                                    <td class="pe-6 py-1.5 truncate">{{ orderItem.product.name }}</td>
                                    <td class="pe-6 py-1.5 truncate capitalize">{{ orderItem.product.weight }}
                                        Kg</td>
                                    <td class="pe-6 py-1.5 truncate">{{ $formatCurrency(orderItem.price) }} </td>
                                    <td class="pe-3 py-1.5 truncate">{{ orderItem.quantity }} </td>
                                </tr>
                            </tbody>
                            <tfoot class="text-xs text-gray-700 uppercase bg-blue-100">
                                <tr>
                                    <td class="w-2 p-2 text-center truncate">
                                        #
                                    </td>
                                    <td class="pe-3 py-1.5 font-bold truncate" colspan="2">
                                        Total
                                    </td>
                                    <td class="pe-3 py-1.5 font-bold truncate" colspan="2">
                                        {{ $formatCurrency(detailTotalAmount) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="mt-6 flex gap-4 justify-end">
                            <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                            <PrimaryButton v-if="selectedOrder.status === 'check'"
                                @click="confirmOrderPayment(selectedOrder)">
                                Bayar</PrimaryButton>
                            <PrimaryButton v-else @click="goToPayments">
                                <CreditCard widht="16" height="16" />Pembayaran
                            </PrimaryButton>
                        </div>
                    </div>
                </Modal>

                <Modal :show="confirmingOrderDeletion">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus pesanan Anda, code <strong>{{
                                selectedOrder.order_code
                                }}</strong>?
                        </h2>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah pesanan Anda, code <strong>{{ selectedOrder.order_code }}</strong> dihapus,
                            semua
                            sumber daya dan datanya akan dihapus secara permanen.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                            <DangerButton class="ms-3" @click="deleteOrder">Hapus</DangerButton>
                        </div>
                    </div>
                </Modal>

                <!-- Confirm order modal -->
                <Modal :show="confirmingOrderPayment">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Konfirmasi Pesanan
                        </h2>
                        <p class="my-1 text-sm text-gray-700">
                            Setelah pesanan dibayar, status pesanan akan terupdate. Silahkan pilih metode pembayarannya.
                        </p>
                        <div>
                            <DropdownSelect id="payment_method" label="Metode Pembayaran" optionProperty="name"
                                valueProperty="name" :options="paymentMethods" v-model="form.payment_method"
                                placeholder="Pilih Metode Pembayaran" class="w-full" />
                            <InputError class="mt-2" :message="form.errors.payment_method" />
                        </div>
                        <ul class="pt-2 space-y-1">
                            <li class="flex items-start">
                                <strong class="w-32 text-gray-800 text-sm">Bank Transfer:</strong>
                                <span class="text-gray-800 text-sm">Nomor Rekening: 1234567890 (Bank XYZ) a.n. PT. Toko
                                    Online</span>
                            </li>
                            <li class="flex items-start">
                                <strong class="w-32 text-gray-800 text-sm">Cash on Delivery:</strong>
                                <span class="text-gray-800 text-sm">Silahkan siapkan jumlah uang yang tepat saat pesanan
                                    Anda
                                    tiba.</span>
                            </li>
                            <li class="flex items-start">
                                <strong class="w-32 text-gray-800 text-sm">E-Wallet:</strong>
                                <span class="text-gray-800 text-sm">Nomor E-Wallet: 081234567890 (GoPay, OVO,
                                    Dana)</span>
                            </li>
                        </ul>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                            <PrimaryButton class="ms-3" @click="storePayment">Pesan</PrimaryButton>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
