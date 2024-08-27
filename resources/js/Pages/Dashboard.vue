<script setup>
import CardButton from '@/Components/CardButton.vue';
import DateTimePicker from '@/Components/DateTimePicker.vue';
import ArrowClockwise from '@/Components/Icons/ArrowClockwise.vue';
import BoxSeam from '@/Components/Icons/BoxSeam.vue';
import CardHeading from '@/Components/Icons/CardHeading.vue';
import CreditCard from '@/Components/Icons/CreditCard.vue';
import Inbox from '@/Components/Icons/Inbox.vue';
import People from '@/Components/Icons/People.vue';
import LineChart from '@/Components/LineChart.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watchEffect } from 'vue';
import Chart from '@/Components/Icons/Chart.vue';
import Person from '@/Components/Icons/Person.vue';

const props = defineProps({
    sellers: Array,
    products: Array,
    users: Array,
    orders: Array,
    payments: Array,
})

const { auth } = usePage().props;
const roles = ref(auth.roles);
const hasRole = (role) => roles.value.includes(role);

const isAdmin = computed(() => hasRole('admin'));
const isUser = computed(() => hasRole('user'));

const userHasRole = (user, roleName) => {
    return user.roles.some(role => role.name === roleName);
};

const normalUsers = computed(() => {
    return props.users.filter(user => userHasRole(user, 'user'));
});

const goToSellers = () => {
    router.visit(route('show.sellers'))
}

const goToProducts = () => {
    router.visit(route('show.products'))
}

const goToUsers = () => {
    router.visit(route('show.users'))
}

const goToOrders = () => {
    router.visit(route('show.orders'))
}

const goToPayments = () => {
    router.visit(route('show.payments'))
}

const goToReports = () => {
    router.visit(route('show.reports'))
}

const selectedPayment = ref(null);
const selectedOrderItems = ref(null);
const showingModalPayment = ref(false);

const showModalPayment = (payment) => {
    showingModalPayment.value = true;
    selectedPayment.value = payment;
    selectedOrderItems.value = payment.order.order_items
};

const closeModal = () => {
    showingModalPayment.value = false;
};

// Inisialisasi tanggal default
const defaultStartDate = new Date();
defaultStartDate.setDate(defaultStartDate.getDate() - 6);
const defaultEndDate = new Date();

// Refs untuk tanggal
const start_date_line_chart = ref(defaultStartDate);
const end_date_line_chart = ref(defaultEndDate);

const datePickerKeys = ref({
    startDate: 0,
    endDate: 0,
});

const resetDateLineChartFilters = () => {
    start_date_line_chart.value = new Date(defaultStartDate);
    end_date_line_chart.value = new Date(defaultEndDate);

    datePickerKeys.value.startDate += 1;
    datePickerKeys.value.endDate += 1;
};

const filteredDateLineChart = ref({});
const dataCharts = ref([]);

const lableCharts = ref([
    'Check',
    'Pending',
    'Paid',
    'Shipped',
    'Complete',
    'Cancelled'
]);

const computeFilteredDateLineChart = () => {
    if (start_date_line_chart.value && end_date_line_chart.value) {
        const start = new Date(start_date_line_chart.value);
        const end = new Date(end_date_line_chart.value);

        return {
            payments: props.payments.filter(payment => {
                const createdDate = new Date(payment.created_at);
                return createdDate >= start && createdDate <= end;
            }),
        };
    } else {
        return { payments: props.payments };
    }
};

const updateDataCharts = () => {
    if (isAdmin.value) {
        const filteredData = computeFilteredDateLineChart();
        const statusCounts = {
            check: 0,
            pending: 0,
            paid: 0,
            shipped: 0,
            completed: 0,
            cancelled: 0
        };

        filteredData.payments.forEach(payment => {
            if (statusCounts.hasOwnProperty(payment.status)) {
                statusCounts[payment.status]++;
            }
        });

        dataCharts.value = Object.values(statusCounts);
    } else {
        dataCharts.value = [];
    }
};

// WatchEffect perubahan pada tanggal
watchEffect(() => {
    filteredDateLineChart.value = computeFilteredDateLineChart();
    updateDataCharts();
});

// Status filtering
const selectedStatus = ref('pending');
const filteredPayments = computed(() => {
    return props.payments.filter(payment => payment.status === selectedStatus.value);
});

// Status counts
const statusCounts = computed(() => {
    return props.payments.reduce((counts, payment) => {
        if (counts.hasOwnProperty(payment.status)) {
            counts[payment.status]++;
        } else {
            counts[payment.status] = 1;
        }
        return counts;
    }, {
        pending: 0,
        paid: 0,
        shipped: 0,
        completed: 0,
        cancelled: 0,
    });
});

// Calculate total amount
const totalAmount = computed(() => {
    return filteredPayments.value.reduce((total, payment) => {
        const amount = parseFloat(payment.amount);
        return total + (isNaN(amount) ? 0 : amount);
    }, 0);
});

const printContent = ref(null);

const handlePrint = () => {
    const pdf = new jsPDF({
        format: 'a5',
        orientation: 'landscape',
    });

    const printContentEl = printContent.value;

    const logoImage = new Image();
    logoImage.src = 'storage/images/header/logo-sipi.png'; // Ganti dengan path gambar logo Anda
    pdf.addImage(logoImage, 'PNG', 14, 4, 25, 25); // Menambahkan gambar logo dengan posisi dan ukuran

    pdf.setFontSize(14);
    pdf.text(`SIPI-Desa Soro`, pdf.internal.pageSize.width / 5, 16);  // Menambahkan nama perusahaan
    pdf.setFontSize(10);
    pdf.text(`Kampung Nelayan Desa Soro, Kecamatan Kempo, Dompu, NTB.`, pdf.internal.pageSize.width / 5, 22);  // Menambahkan nama perusahaan

    const rows = [];
    const content = printContentEl.querySelectorAll('tbody tr');
    content.forEach((row) => {
        const cells = row.querySelectorAll('td');
        const rowData = Array.from(cells).map(cell => cell.innerText);
        rows.push(rowData);
    });

    pdf.autoTable({
        body: rows,
        startY: 28,
        styles: { font: 'helvetica', fontSize: 10 },
    });

    const totalPages = pdf.internal.getNumberOfPages();
    const timestamp = new Date().getTime();

    pdf.setPage(totalPages);
    pdf.setFontSize(6);
    pdf.text(`Dibuat menggunakan Sistem Informasi Penjualan Ikan (SIPI-Desa Soro) pada tanggal ${new Date().toLocaleString('id-ID')} / ${timestamp} oleh ${auth.user.name} sebagai bukti Transaksi Pembeli`, pdf.internal.pageSize.getWidth() - 30, pdf.internal.pageSize.getHeight() - 10, { align: 'right' });

    const blob = pdf.output('blob');
    const pdfURL = URL.createObjectURL(blob);

    const newTabPdf = window.open('', '_blank');
    newTabPdf.location.href = pdfURL;
};
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="isAdmin">
                    <div class="flex flex-col gap-4 pb-4 ">
                        <div class="grid grid-cols-2 sm:grid-cols-5 mx-2 gap-4">
                            <CardButton @click="goToSellers" title="Penjual" description="Daftar Penjual."
                                class="mx-auto">
                                <template #svg>
                                    <div class="bg-blue-100 p-4 rounded-tl-3xl">
                                        <Person widht="24" height="24" />
                                    </div>
                                </template>
                                <template #end>
                                    <div class="bg-blue-100 p-4 rounded-br-3xl">
                                        <p class="text-md p-0.5 font-bold">{{ sellers.length }}</p>
                                    </div>
                                </template>
                            </CardButton>
                            <CardButton @click="goToProducts" title="Produk" description="Daftar Produk."
                                class="mx-auto">
                                <template #svg>
                                    <div class="bg-blue-100 p-4 rounded-tl-3xl">
                                        <BoxSeam width="24" height="24" />
                                    </div>
                                </template>
                                <template #end>
                                    <div class="bg-blue-100 p-4 rounded-br-3xl">
                                        <p class="text-md p-0.5 font-bold">{{ products.length }}</p>
                                    </div>
                                </template>
                            </CardButton>
                            <CardButton @click="goToUsers" title="Pengguna" description="Daftar Pengguna."
                                class="mx-auto">
                                <template #svg>
                                    <div class="bg-blue-100 p-4 rounded-tl-3xl">
                                        <People width="24" height="24" />
                                    </div>
                                </template>
                                <template #end>
                                    <div class="bg-blue-100 p-4 rounded-br-3xl">
                                        <p class="text-md p-0.5 font-bold">{{ normalUsers.length }}</p>
                                    </div>
                                </template>
                            </CardButton>
                            <CardButton @click="goToOrders" title="Pesanan" description="Daftar pesanan."
                                class="mx-auto">
                                <template #svg>
                                    <div class="bg-blue-100 p-4 rounded-tl-3xl">
                                        <Inbox width="24" height="24" />
                                    </div>
                                </template>
                                <template #end>
                                    <div class="bg-blue-100 p-4 rounded-br-3xl">
                                        <p class="text-md p-0.5 font-bold">{{ orders.length }}</p>
                                    </div>
                                </template>
                            </CardButton>
                            <CardButton @click="goToPayments" title="Pembayaran" description="Daftar pembayaran."
                                class="mx-auto">
                                <template #svg>
                                    <div class="bg-blue-100 p-4 rounded-tl-3xl">
                                        <CreditCard width="24" height="24" />
                                    </div>
                                </template>
                                <template #end>
                                    <div class="bg-blue-100 p-4 rounded-br-3xl">
                                        <p class="text-md p-0.5 font-bold">{{ payments.length }}</p>
                                    </div>
                                </template>
                            </CardButton>
                        </div>
                        <hr>
                        <div class="flex flex-col items-center sm:justify-start justify-center gap-4 mx-2">
                            <div class="flex flex-col items-center gap-2 me-auto">
                                <div class="flex items-center gap-2 bg-white">
                                    <DateTimePicker :key="datePickerKeys.startDate" id="start_date_line_chart"
                                        label="Tanggal awal" v-model="start_date_line_chart" />
                                    <DateTimePicker :key="datePickerKeys.endDate" id="end_date_line_chart"
                                        label="Tanggal akhir" v-model="end_date_line_chart" />
                                    <PrimaryButton @click="resetDateLineChartFilters"
                                        class="mt-auto gap-2 shadow-none py-3 capitalize">
                                        <ArrowClockwise width="16" height="16" />
                                    </PrimaryButton>
                                </div>
                            </div>
                            <LineChart :lableCharts="lableCharts" :dataCharts="dataCharts" />
                        </div>
                        <div class="px-2">
                            <PrimaryButton @click="goToReports" class="gap-2 py-3">
                                <Chart widht="16" height="16" />
                                <span>Laporan</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8" />
                                </svg>
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
                <div v-if="isUser" class="bg-white overflow-hidden sm:rounded-lg py-6">
                    <ol class="flex items-center w-full mb-4 sm:mb-5 py-8">
                        <li
                            class="flex w-full text-xs items-center text-blue-500 after:content-['Pending'] after:w-full after:h-1 after:border-b after:border-blue-100 after:border-4 after:inline-block">
                            <div class="bg-blue-100 text-blue-700 text-sm font-bold p-2 rounded-tl-2xl">{{
                                statusCounts.pending }}
                            </div>
                            <a href="#" @click="selectedStatus = 'pending'"
                                class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-br-2xl lg:h-12 lg:w-12 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-hourglass" viewBox="0 0 16 16">
                                    <path
                                        d="M2 1.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1-.5-.5m2.5.5v1a3.5 3.5 0 0 0 1.989 3.158c.533.256 1.011.791 1.011 1.491v.702c0 .7-.478 1.235-1.011 1.491A3.5 3.5 0 0 0 4.5 13v1h7v-1a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351v-.702c0-.7.478-1.235 1.011-1.491A3.5 3.5 0 0 0 11.5 3V2z" />
                                </svg>
                            </a>
                        </li>
                        <li
                            class="flex w-full text-xs items-center text-blue-500 after:content-['Paid'] after:w-full after:h-1 after:border-b after:border-blue-200 after:border-4 after:inline-block">
                            <div class="bg-blue-100 text-blue-700 text-sm font-bold p-2 rounded-tl-2xl">{{
                                statusCounts.paid }}
                            </div>
                            <a href="#" @click="selectedStatus = 'paid'"
                                class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-br-2xl lg:h-12 lg:w-12 shrink-0">
                                <CreditCard />
                            </a>
                        </li>
                        <li
                            class="flex w-full text-xs items-center text-blue-500 after:content-['Shipped'] after:w-full after:h-1 after:border-b after:border-blue-300 after:border-4 after:inline-block">
                            <div class="bg-blue-100 text-blue-700 text-sm font-bold p-2 rounded-tl-2xl">{{
                                statusCounts.shipped }}
                            </div>
                            <a href="#" @click="selectedStatus = 'shipped'"
                                class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-br-2xl lg:h-12 lg:w-12 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-truck" viewBox="0 0 16 16">
                                    <path
                                        d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                                </svg>
                            </a>
                        </li>
                        <li
                            class="flex w-full text-xs items-center text-blue-500 after:content-['Completed'] after:w-full after:h-1 after:border-b after:border-blue-400 after:border-4 after:inline-block">
                            <div class="bg-blue-100 text-blue-700 text-sm font-bold p-2 rounded-tl-2xl">{{
                                statusCounts.completed
                            }}
                            </div>
                            <a href="#" @click="selectedStatus = 'completed'"
                                class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-br-2xl lg:h-12 lg:w-12 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                            </a>
                        </li>
                        <li
                            class="flex w-full text-xs items-center text-red-500 after:content-['Cancelled'] after:w-full after:h-1 after:border-b after:border-red-400 after:border-4 after:inline-block">
                            <div class="bg-red-100 text-red-700 text-sm font-bold p-2 rounded-tl-2xl">{{
                                statusCounts.cancelled }}
                            </div>
                            <a href="#" @click="selectedStatus = 'cancelled'"
                                class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-br-2xl lg:h-12 lg:w-12 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-x-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                    <path
                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                </svg>
                            </a>
                        </li>
                    </ol>
                    <div class="py-4">
                        <div class="flex items-center justify-center font-bold text-lg py-2">
                            <h2>{{ selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1) }}</h2>
                        </div>
                        <div class="overflow-x-auto sm:rounded-md pb-4">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-blue-100">
                                    <tr>
                                        <th scope="col" class="px-3 py-3 truncate">No.</th>
                                        <th scope="col" class="px-3 py-3 truncate">Nama</th>
                                        <th scope="col" class="px-3 py-3 truncate">Kode Pembayaran</th>
                                        <th scope="col" class="px-3 py-3 truncate">Status</th>
                                        <th scope="col" class="px-3 py-3 truncate">Metode Pembayaran</th>
                                        <th scope="col" class="px-3 py-3 truncate">Jumlah</th>
                                        <th scope="col" class="px-3 py-3 text-center truncate">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(payment, index) in filteredPayments" :key="payment.id"
                                        class="bg-white border-b hover:bg-blue-100">
                                        <td class="w-4 p-4 text-center">{{ index + 1
                                            }}.</td>
                                        <th scope="row"
                                            class="flex items-center px-2 py-3 text-gray-900 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <div class="text-base font-semibold">{{ payment.order.user.name }}</div>
                                                <div class="font-normal text-gray-500">{{ payment.order.user.email }}
                                                </div>
                                            </div>
                                        </th>
                                        <td class="px-3 py-3 truncate">{{ payment.payment_code }}</td>
                                        <td class="px-3 py-3 truncate capitalize">
                                            <a v-if="isAdmin" href="#" type="button"
                                                @click="showModalStatusUpdate(payment)"
                                                class="flex gap-2 items-center font-normal text-blue-600 hover:underline">
                                                {{ payment.status }}
                                                <PencilSquare width="16" height="16" />
                                            </a>
                                            <p v-else
                                                class="flex gap-2 items-center font-normal text-blue-600 hover:font-bold">
                                                {{ payment.status }}
                                            </p>

                                        </td>
                                        <th scope="row"
                                            class="flex items-center px-2 py-3 text-gray-900 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <div class="text-base font-semibold">{{ payment.payment_method }}</div>
                                                <div class="font-normal text-gray-500">
                                                    {{ $formatDate(payment.created_at) }}
                                                </div>
                                            </div>
                                        </th>
                                        <td class="px-3 py-3 truncate">{{ $formatCurrency(payment.amount) }}</td>
                                        <td class="px-3 py-3 truncate">
                                            <!-- Modal toggle Detail-->
                                            <a href="#" type="button" @click="showModalPayment(payment)"
                                                class="flex gap-2 items-center justify-center font-normal px-2 text-gray-600 hover:underline">
                                                <CardHeading width="16" height="16" />Detail
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="text-xs text-gray-700 uppercase bg-blue-100">
                                    <tr>
                                        <td class="w-4 p-4 text-center truncate">
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
                    </div>
                </div>
            </div>

            <Modal :show="showingModalPayment">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Detail Pesanan
                    </h2>
                    <table ref="printContent" class="mt-1 w-full text-sm text-left rtl:text-right text-gray-500">
                        <tbody>
                            <tr class="bg-white border-b hover:bg-blue-100">
                                <td class="pe-6 py-1.5 text-black truncate">Nama</td>
                                <td class="pe-6 py-1.5 truncate">{{ selectedPayment.order.user.name }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-blue-100">
                                <td class="pe-6 py-1.5 text-black truncate">Email</td>
                                <td class="pe-6 py-1.5 truncate">{{ selectedPayment.order.user.email }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-blue-100">
                                <td class="pe-6 py-1.5 text-black truncate">Telepon</td>
                                <td class="pe-6 py-1.5 truncate">{{ selectedPayment.order.user.customer.phone }}
                                </td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-blue-100">
                                <td class="pe-6 py-1.5 text-black truncate">Alamat</td>
                                <td class="pe-6 py-1.5 truncate">{{ selectedPayment.order.user.customer.address
                                    }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-blue-100">
                                <td class="pe-6 py-1.5 text-black truncate">Kode Pesanan</td>
                                <td class="pe-6 py-1.5 truncate">{{ selectedPayment.order.order_code }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-blue-100">
                                <td class="pe-6 py-1.5 text-black truncate">Kode Pembayaran</td>
                                <td class="pe-6 py-1.5 truncate">{{ selectedPayment.payment_code }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-blue-100">
                                <td class="pe-6 py-1.5 text-black truncate">Tanggal Pembayaran</td>
                                <td class="pe-6 py-1.5 truncate">{{ $formatDate(selectedPayment.created_at) }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-blue-100">
                                <td class="pe-6 py-1.5 text-black truncate">Status</td>
                                <td class="pe-6 py-1.5 truncate capitalize">{{ selectedPayment.status }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-blue-100">
                                <td class="pe-6 py-1.5 text-black truncate">Metode Pembayaran</td>
                                <td class="pe-6 py-1.5 truncate">{{ selectedPayment.payment_method }}</td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-blue-100">
                                <td class="pe-6 py-1.5 text-black truncate">Total</td>
                                <td class="pe-6 py-1.5 truncate">{{
                                    $formatCurrency(selectedPayment.order.total_amount) }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <div v-if="selectedPayment.status === 'check'" class="pt-4">Saat ini status pembayaran
                                Anda
                                masih dalam tahap pengecekan. Silakan
                                upload
                                bukti
                                pembayaran Anda untuk mempercepat proses verifikasi.</div>
                        </tfoot>
                    </table>
                    <div class="mt-6 flex justify-end gap-4">
                        <SecondaryButton @click="handlePrint">Print</SecondaryButton>
                        <PrimaryButton @click="closeModal">Ok</PrimaryButton>
                    </div>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
