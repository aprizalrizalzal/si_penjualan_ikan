<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SearchInput from '@/Components/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import DateTimePicker from '@/Components/DateTimePicker.vue';
import ArrowClockwise from '@/Components/Icons/ArrowClockwise.vue';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const { auth } = usePage().props;

const props = defineProps({
    sellers: Array,
});

// const getPaymentStatusFromSellers = (sellers) => {
//     const paymentStatus = [];

//     sellers.forEach((seller) => {
//         seller.products.forEach((product) => {
//             product.order_items.forEach((orderItem) => {
//                 if (orderItem.order && orderItem.order.payment) {
//                     paymentStatus.push(orderItem.order.payment.status);
//                 }
//             });
//         });
//     });

//     return paymentStatus;
// };

// const paymentStatus = getPaymentStatusFromSellers(props.sellers);

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

const printContent = ref(null);

const handlePrint = () => {
    const pdf = new jsPDF({
        format: 'a4',
        orientation: 'landscape',
    });

    // Ambil elemen tabel dari template
    const printContentEl = printContent.value;

    // Tambahkan logo
    const logoImage = new Image();
    logoImage.src = 'storage/images/header/logo-sipi.png'; // Ganti dengan path gambar logo Anda
    pdf.addImage(logoImage, 'PNG', 14, 4, 25, 25); // Menambahkan gambar logo dengan posisi dan ukuran

    // Tambahkan nama perusahaan
    pdf.setFontSize(14);
    pdf.text('SIPI-Desa Soro', pdf.internal.pageSize.width / 7, 16);
    pdf.setFontSize(10);
    pdf.text('Kampung Nelayan Desa Soro, Kecamatan Kempo, Dompu, NTB.', pdf.internal.pageSize.width / 7, 22);

    pdf.setTextColor(0, 0, 0);
    pdf.setFontSize(10);
    pdf.text(`${new Date(start_date.value).toLocaleDateString('id-ID')} - ${new Date(end_date.value).toLocaleDateString('id-ID')}`, pdf.internal.pageSize.width - 14, 22, { align: 'right' });

    // Ambil isi tabel dari elemen HTML
    const columns = Array.from(printContentEl.querySelectorAll('thead th')).map(th => th.innerText);
    const rows = Array.from(printContentEl.querySelectorAll('tbody tr')).map(tr => {
        return Array.from(tr.querySelectorAll('td, th')).map(td => td.innerText.trim());
    });

    // Ambil isi tfoot dari elemen HTML, termasuk colspan
    const footer = Array.from(printContentEl.querySelectorAll('tfoot tr')).map(tr => {
        return Array.from(tr.querySelectorAll('td, th')).map(td => ({
            content: td.innerText.trim(),
            colSpan: td.getAttribute('colspan') ? parseInt(td.getAttribute('colspan')) : 1,
        }));
    });

    // Tambahkan tabel ke PDF dengan colspan di footer
    pdf.autoTable({
        head: [columns],
        body: rows,
        foot: footer.map(row => row.map(cell => ({ content: cell.content, colSpan: cell.colSpan }))),
        startY: 28,
        styles: { font: 'helvetica', fontSize: 10 },
    });

    // Tambahkan footer dokumen PDF
    const totalPages = pdf.internal.getNumberOfPages();
    const timestamp = new Date().toLocaleString('id-ID');
    const userName = auth.user.name;

    for (let i = 1; i <= totalPages; i++) {
        pdf.setPage(i);
        pdf.setFontSize(8);
        pdf.text(`Dibuat menggunakan Sistem Informasi Penjualan Ikan (SIPI-Desa Soro) pada tanggal ${timestamp} oleh ${userName} sebagai bukti Transaksi`, pdf.internal.pageSize.getWidth() - 55, pdf.internal.pageSize.getHeight() - 10, { align: 'right' });
    }

    // Buat dan buka PDF
    const blob = pdf.output('blob');
    const pdfURL = URL.createObjectURL(blob);
    const newTabPdf = window.open('', '_blank');
    newTabPdf.location.href = pdfURL;
};
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
                    <table ref="printContent" class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-blue-100">
                            <tr>
                                <th scope="col" class="px-3 py-3 truncate">No.</th>
                                <th scope="col" class="px-3 py-3 truncate">Penjual</th>
                                <th scope="col" class="px-3 py-3 truncate">Telepon</th>
                                <th scope="col" class="px-3 py-3 truncate">Kode Pesanan</th>
                                <th scope="col" class="px-3 py-3 truncate">Kode Pembayaran</th>
                                <th scope="col" class="px-3 py-3 truncate">Status</th>
                                <th scope="col" class="px-3 py-3 truncate">Total</th>
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
                                <td class="px-3 py-3 capitalize truncate">
                                    <ul v-for="product in seller.products" :key="product.id">
                                        <li v-for="orderItem in product.order_items" :key="orderItem.id">
                                            <ul v-for="order in [orderItem.order]" :key="order.id">
                                                <li v-if="order.payment" :key="order.payment.id">
                                                    {{ order.payment.status }}
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
                            </tr>
                        </tbody>
                        <tfoot class="text-xs text-gray-700 uppercase bg-blue-100">
                            <tr>
                                <td class="w-4 p-4 text-center truncate">#</td>
                                <td class="px-3 py-3 font-bold truncate" colspan="5">Total</td>
                                <td class="px-3 py-3 font-bold truncate">
                                    {{ $formatCurrency(totalAmount) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <PrimaryButton @click="handlePrint" class="gap-2 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-printer" viewBox="0 0 16 16">
                        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                        <path
                            d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1" />
                    </svg>
                    <span>Print</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8" />
                    </svg>
                </PrimaryButton>
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
