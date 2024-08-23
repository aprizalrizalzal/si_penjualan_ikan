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
import CardHeading from '@/Components/Icons/CardHeading.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    payments: Array,
});

const goToWelcome = () => {
    router.visit(route('welcome'))
}

const searchQuery = ref('');

const filteredPaymentsSearch = computed(() => {
    if (!searchQuery.value) {
        return props.payments;
    }
    return props.payments.filter(payment =>
        payment.payment_code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        payment.status.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const currentPage = ref(1);
const itemsPerPage = 10;

const paginatedPayments = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredPaymentsSearch.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredPaymentsSearch.value.length / itemsPerPage);
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

const selectedPayment = ref(null);

const showingModalPayment = ref(false);
const confirmingPaymentDeletion = ref(false);

const showModalPayment = (payment) => {
    showingModalPayment.value = true;
    selectedPayment.value = payment;
};

const confirmPaymentDeletion = (payment) => {
    confirmingPaymentDeletion.value = true;
    selectedPayment.value = payment;
};

const deletePayment = () => {
    router.delete(route('destroy.payment', { id: selectedPayment.value.id }), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        },
    });
};

const totalAmount = computed(() => {
    return filteredPaymentsSearch.value.reduce((total, payment) => {
        const amount = parseFloat(payment.amount);
        return total + (isNaN(amount) ? 0 : amount);
    }, 0);
});

const closeModal = () => {
    showingModalPayment.value = false;
    confirmingPaymentDeletion.value = false;
};
</script>

<template>

    <Head title="Payments" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pembayaran</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="flex items-center justify-between sm:flex-row flex-col gap-4 pt-2 pb-4 px-4 sm:px-0 bg-white">
                    <div class="flex items-center gap-2">
                        <SecondaryButton @click="goToWelcome" class="gap-2 shadow-none py-2.5 capitalize">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-back" viewBox="0 0 16 16">
                                <path
                                    d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z" />
                            </svg>
                            SIPI Desa Soro
                        </SecondaryButton>
                    </div>
                    <SearchInput v-model:searchQuery="searchQuery" placeholder="Cari" />
                </div>
                <div class="overflow-x-auto sm:rounded-md pb-4">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-blue-100">
                            <tr>
                                <th scope="col" class="px-3 py-3 truncate">No.</th>
                                <th scope="col" class="px-3 py-3 truncate">Kode Pembayaran</th>
                                <th scope="col" class="px-3 py-3 truncate">Status</th>
                                <th scope="col" class="px-3 py-3 truncate">Metode Pembayaran</th>
                                <th scope="col" class="px-3 py-3 truncate">Jumlah</th>
                                <th scope="col" class="px-2 py-3 text-center truncate" colspan="2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(payment, index) in paginatedPayments" :key="payment.id"
                                class="bg-white border-b hover:bg-blue-100">
                                <td class="w-4 p-4 text-center">{{ (currentPage - 1) * itemsPerPage + index + 1 }}.</td>
                                <td class="px-3 py-3 truncate">{{ payment.payment_code }}</td>
                                <td class="px-3 py-3 truncate capitalize">
                                    <a href="#" type="button" @click="showModalUpdatePayment(payment)"
                                        class="flex gap-2 items-center font-normal text-blue-600 hover:underline">
                                        {{ payment.status }}
                                        <PencilSquare width="16" height="16" />
                                    </a>
                                </td>
                                <td class="px-3 py-3 truncate">{{ payment.payment_method }}</td>
                                <td class="px-3 py-3 truncate">{{ $formatCurrency(payment.amount) }}</td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Modal toggle Detail-->
                                    <a href="#" type="button" @click="showModalPayment(payment)"
                                        class="flex gap-2 items-center justify-center font-normal px-2 text-gray-600 hover:underline">
                                        <CardHeading width="16" height="16" />Detail
                                    </a>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Delete payment -->
                                    <a href="#" type="button" @click="confirmPaymentDeletion(payment)"
                                        class="flex gap-2 items-center justify-center font-normal px-2 text-red-600 hover:underline">
                                        <Trash3 width="16" height="16" />Hapus
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="text-xs text-gray-700 uppercase bg-blue-100">
                            <tr>
                                <td class="px-3 py-3 text-center truncate">
                                    #
                                </td>
                                <td class="px-3 py-3 font-bold truncate" colspan="3">
                                    Total
                                </td>
                                <td class="px-3 py-3 font-bold truncate" colspan="3">
                                    {{ $formatCurrency(totalAmount) }}
                                </td>
                            </tr>
                        </tfoot>
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

                <div class="p-2 bg-white">
                    <h3 class="text-sm font-semibold mb-2 text-gray-800">Keterangan Status</h3>
                    <ul class="space-y-1">
                        <li class="flex items-start">
                            <strong class="w-20 text-gray-800 text-sm">Pending:</strong>
                            <span class="text-gray-800 text-sm">Pesanan dibuat, menunggu pembayaran atau
                                konfirmasi.</span>
                        </li>
                        <li class="flex items-start">
                            <strong class="w-20 text-gray-800 text-sm">Paid:</strong>
                            <span class="text-gray-800 text-sm">Pembayaran diterima, pesanan siap diproses.</span>
                        </li>
                        <li class="flex items-start">
                            <strong class="w-20 text-gray-800 text-sm">Shipped:</strong>
                            <span class="text-gray-800 text-sm">Pesanan telah dikirim, dalam perjalanan ke
                                pelanggan.</span>
                        </li>
                        <li class="flex items-start">
                            <strong class="w-20 text-gray-800 text-sm">Completed:</strong>
                            <span class="text-gray-800 text-sm">Pesanan selesai dan diterima oleh pelanggan.</span>
                        </li>
                        <li class="flex items-start">
                            <strong class="w-20 text-gray-800 text-sm">Cancelled:</strong>
                            <span class="text-gray-800 text-sm">Pesanan dibatalkan, tidak akan diproses lebih
                                lanjut.</span>
                        </li>
                    </ul>
                </div>

                <!-- Detail payment item modal -->
                <Modal :show="showingModalPayment">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Detail Pesanan <strong>{{ selectedPayment.order.user.name }}</strong>
                        </h2>
                        <table class="mt-1 w-full text-sm text-left rtl:text-right text-gray-500">
                            <tbody>
                                <tr class="bg-white border-b hover:bg-blue-100">
                                    <td class="pe-6 py-1.5 text-black truncate">Kode Pesanan</td>
                                    <td class="pe-6 py-1.5 truncate">{{ selectedPayment.order.order_code }}</td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-blue-100">
                                    <td class="pe-6 py-1.5 text-black truncate">Status</td>
                                    <td class="pe-6 py-1.5 truncate capitalize">{{ selectedPayment.order.status }}</td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-blue-100">
                                    <td class="pe-6 py-1.5 text-black truncate">Total</td>
                                    <td class="pe-6 py-1.5 truncate">{{
                                        $formatCurrency(selectedPayment.order.total_amount) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-6 flex justify-start">
                            <PrimaryButton @click="closeModal">Ok</PrimaryButton>
                        </div>
                    </div>
                </Modal>

                <Modal :show="confirmingPaymentDeletion">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus pesanan ID <strong>{{ selectedPayment.id }}</strong>?
                        </h2>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah pesanan ID <strong>{{ selectedPayment.id }}</strong> dihapus, semua
                            sumber daya dan datanya akan dihapus secara permanen.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                            <DangerButton class="ms-3" @click="deletePayment">Hapus</DangerButton>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
