<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SearchInput from '@/Components/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Trash3 from '@/Components/Icons/Trash3.vue';
import CardHeading from '@/Components/Icons/CardHeading.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CreditCard from '@/Components/Icons/CreditCard.vue';
import PlusCircle from '@/Components/Icons/PlusCircle.vue';
import DropdownSelect from '@/Components/DropdownSelect.vue';
import DateTimePicker from '@/Components/DateTimePicker.vue';
import ArrowClockwise from '@/Components/Icons/ArrowClockwise.vue';

const props = defineProps({
    sellers: Array,
});

const searchQuery = ref('');

const filteredSellersSearch = computed(() => {
    if (!searchQuery.value) {
        return props.sellers;
    }
    return props.sellers.filter(seller =>
        seller.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

// Inisialisasi tanggal default
const defaultStartDate = new Date();
defaultStartDate.setDate(defaultStartDate.getDate() - 6);
const defaultEndDate = new Date();

// Refs untuk tanggal
const start_date = ref(defaultStartDate);
const end_date = ref(defaultEndDate);

const datePickerKeys = ref({
    startDate: 0,
    endDate: 0,
});

const resetDateFilters = () => {
    start_date.value = new Date(defaultStartDate);
    end_date.value = new Date(defaultEndDate);

    datePickerKeys.value.startDate += 1;
    datePickerKeys.value.endDate += 1;
};

const currentPage = ref(1);
const itemsPerPage = 10;

const filteredSellersDate = computed(() => {
    let sellers = filteredSellersSearch.value;

    if (start_date.value || end_date.value) {
        sellers = sellers.filter(seller => {
            const start = start_date.value ? new Date(start_date.value) : null;
            const end = end_date.value ? new Date(end_date.value) : null;

            // Cek jika ada order_items dalam produk untuk penjual
            const hasValidDate = seller.products.some(product =>
                product.order_items.some(orderItem => {
                    const paymentCreatedAt = new Date(orderItem.order.payment.created_at);

                    if (start && end) {
                        return paymentCreatedAt >= start && paymentCreatedAt <= end;
                    } else if (start) {
                        return paymentCreatedAt >= start;
                    } else if (end) {
                        return paymentCreatedAt <= end;
                    }

                    return false;
                })
            );

            return hasValidDate;
        });
    }

    return sellers;
});

const paginatedSellers = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredSellersDate.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredSellersDate.value.length / itemsPerPage);
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

const totalAmount = computed(() => {
    return paginatedSellers.value.reduce((total, seller) => {
        // Hitung total amount untuk setiap seller
        return total + seller.products.reduce((sum, product) => {
            return sum + product.order_items.reduce((productTotal, orderItem) => {
                // Menghitung total item untuk orderItem
                const itemTotal = parseFloat(orderItem.price) * parseFloat(orderItem.quantity);
                return productTotal + (isNaN(itemTotal) ? 0 : itemTotal);
            }, 0);
        }, 0);
    }, 0);
});
</script>

<template>

    <Head title="Report" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan</h2>
        </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="flex items-center justify-between sm:flex-row flex-col gap-4 pt-2 pb-4 px-4 sm:px-0 bg-white">
                    <div class="flex items-center sm:flex-row flex-col gap-2 w-full">
                        <div class="flex items-center gap-2 bg-white">
                            <DateTimePicker :key="datePickerKeys.startDate" id="start_date" label="Tanggal awal"
                                v-model="start_date" />
                            <DateTimePicker :key="datePickerKeys.endDate" id="end_date" label="Tanggal akhir"
                                v-model="end_date" />
                            <PrimaryButton @click="resetDateFilters" class="mt-auto gap-2 shadow-none py-3 capitalize">
                                <ArrowClockwise width="16" height="16" />
                            </PrimaryButton>
                        </div>
                        <SearchInput class="mt-auto ms-auto" v-model:searchQuery="searchQuery" placeholder="Cari" />
                    </div>
                </div>
                <div class="overflow-x-auto sm:rounded-md pb-4">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-blue-100">
                            <tr>
                                <th scope="col" class="px-3 py-3 truncate">No.</th>
                                <th scope="col" class="px-3 py-3 truncate">Penjual</th>
                                <th scope="col" class="px-3 py-3 truncate">Telepon</th>
                                <th scope="col" class="px-3 py-3 truncate">Kode Pesanan</th>
                                <th scope="col" class="px-3 py-3 truncate">Kode Pembayaran</th>
                                <th scope="col" class="px-3 py-3 truncate">Total</th>
                                <th scope="col" class="px-2 py-3 text-center truncate">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(seller, index) in paginatedSellers" :key="seller.id"
                                class="bg-white border-b hover:bg-blue-100">
                                <td class="w-4 p-4 text-center">{{ (currentPage - 1) * itemsPerPage + index + 1 }}.</td>
                                <th scope="row" class="flex items-center px-2 py-3 text-gray-900 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-base font-semibold">
                                            {{ seller.name }}
                                        </div>
                                        <div class="font-normal text-gray-500">
                                            {{ seller.email }}
                                        </div>
                                    </div>
                                </th>
                                <td class="px-3 py-3 truncate">
                                    {{ seller.phone }}
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <ul v-for="product in seller.products" :key="product.id">
                                        <li v-for="orderItem in product.order_items" :key="orderItem.id">
                                            {{ orderItem.order.order_code }}
                                        </li>
                                    </ul>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <ul v-for="product in seller.products" :key="product.id">
                                        <li v-for="orderItem in product.order_items" :key="orderItem.id">
                                            <ul v-for="order in [orderItem.order]" :key="order.id">
                                                <li v-if="order.payment" :key="order.payment.id">
                                                    {{ order.payment.payment_code }}
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <ul v-for="product in seller.products" :key="product.id">
                                        <li v-for="orderItem in product.order_items" :key="orderItem.id">
                                            {{ $formatCurrency(orderItem.quantity * orderItem.price) }}
                                        </li>
                                    </ul>
                                </td>
                                <td class="px-3 py-3 text-center truncate">
                                    <a href="#" @click.prevent="showModalSeller(seller)"
                                        class="flex gap-2 items-center justify-center font-normal px-2 text-gray-600 hover:underline">
                                        <CardHeading width="16" height="16" />Detail
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="text-xs text-gray-700 uppercase bg-blue-100">
                            <tr>
                                <td class="w-4 p-4 text-center truncate">#</td>
                                <td class="px-3 py-3 font-bold truncate" colspan="4">Total</td>
                                <td class="px-3 py-3 font-bold truncate" colspan="2">
                                    {{ $formatCurrency(totalAmount) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="flex items-center pt-4 m-1">

                        <div v-if="sellers.length < 1" class="flex items-center justify-center w-full">
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>
