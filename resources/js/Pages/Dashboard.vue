<script setup>
import CardButton from '@/Components/CardButton.vue';
import DateTimePicker from '@/Components/DateTimePicker.vue';
import ArrowClockwise from '@/Components/Icons/ArrowClockwise.vue';
import BoxSeam from '@/Components/Icons/BoxSeam.vue';
import CreditCard from '@/Components/Icons/CreditCard.vue';
import Inbox from '@/Components/Icons/Inbox.vue';
import People from '@/Components/Icons/People.vue';
import LineChart from '@/Components/LineChart.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watchEffect } from 'vue';

const props = defineProps({
    products: Number,
    users: Number,
    orders: Number,
    payments: Array,
})

const { auth } = usePage().props;
const roles = ref(auth.roles);
const hasRole = (role) => roles.value.includes(role);

const isAdmin = computed(() => hasRole('admin'));
const isUser = computed(() => hasRole('user'));

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

const start_date_line_chart = ref('');
const end_date_line_chart = ref('');

const defaultStartDate = new Date();
defaultStartDate.setDate(defaultStartDate.getDate() - 7);
const defaultEndDate = new Date();

const datePickerKeys = ref({
    startDate: 0,
    endDate: 0,
});

const resetDateLineChartFilters = () => {
    start_date_line_chart.value = defaultStartDate;
    end_date_line_chart.value = defaultEndDate;

    datePickerKeys.value.startDate += 1;
    datePickerKeys.value.endDate += 1;
};

start_date_line_chart.value = defaultStartDate;
end_date_line_chart.value = defaultEndDate;

let filteredDateLineChart = ref({});
let lableCharts = ref([
    'Check',
    'Pending',
    'Paid',
    'Shipped',
    'Complate',
    'Canncelled'
]);

let dataCharts = ref([]);

const computeFilteredDateLineChart = () => {
    let filteredDataLineChart = {};

    if (start_date_line_chart.value && end_date_line_chart.value) {
        // Filter untuk semua entitas
        filteredDataLineChart = {
            payments: props.payments.filter(payment => {
                const createdDate = new Date(payment.created_at);
                const start = new Date(start_date_line_chart.value);
                const end = new Date(end_date_line_chart.value);
                return createdDate >= start && createdDate <= end;
            }),
        };
    } else {
        // Jika tidak ada rentang tanggal yang dipilih, gunakan semua data
        filteredDataLineChart = {
            payments: props.payments,
        };
    }

    return filteredDataLineChart;
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

watchEffect(() => {
    filteredDateLineChart.value = computeFilteredDateLineChart();
    updateDataCharts();
});

// computed property untuk filter status
const selectedStatus = ref('pending'); // Default status
const filteredPayments = computed(() => {
    return props.payments.filter(payment => payment.status === selectedStatus.value);
});

const statusCounts = computed(() => {
    return props.payments.reduce((counts, payment) => {
        if (counts.hasOwnProperty(payment.status)) {
            counts[payment.status]++;
        } else {
            counts[payment.status] = 1;
        }
        return counts;
    }, {
        check: 0,
        pending: 0,
        paid: 0,
        shipped: 0,
        completed: 0,
        cancelled: 0
    });
});

const totalAmount = computed(() => {
    return filteredPayments.value.reduce((total, payment) => {
        const amount = parseFloat(payment.amount);
        return total + (isNaN(amount) ? 0 : amount);
    }, 0);
});


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
                        <div class="grid grid-cols sm:grid-cols-4 mx-2 gap-4">
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
                            <CardButton @click="goToUsers" title="Pelanggan" description="Daftar Pelanggan."
                                class="mx-auto">
                                <template #svg>
                                    <div class="bg-blue-100 p-4 rounded-tl-3xl">
                                        <People width="24" height="24" />
                                    </div>
                                </template>
                                <template #end>
                                    <div class="bg-blue-100 p-4 rounded-br-3xl">
                                        <p class="text-md p-0.5 font-bold">{{ users.length }}</p>
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
                                        <ArrowClockwise width="16" height="16" /> Atur Ulang
                                    </PrimaryButton>
                                </div>
                            </div>
                            <LineChart :lableCharts="lableCharts" :dataCharts="dataCharts" />
                        </div>
                    </div>
                </div>
                <div v-if="isUser" class="bg-white overflow-hidden sm:rounded-lg py-6">
                    <ol class="flex items-center w-full mb-4 sm:mb-5 py-6">
                        <li
                            class="flex w-full text-xs items-center text-blue-500 after:content-['Check'] after:w-full after:h-1 after:border-b after:border-blue-50 after:border-4 after:inline-block ">
                            <div class="bg-blue-100 text-blue-700 text-sm font-bold p-3.5 rounded-tl-2xl">
                                {{
                                    statusCounts.check }}
                            </div>
                            <a href="#" @click="selectedStatus = 'check'"
                                class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-br-2xl lg:h-12 lg:w-12 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    class="bi bi-check-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                    <path
                                        d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                                </svg>
                            </a>
                        </li>
                        <li
                            class="flex w-full text-xs items-center text-blue-500 after:content-['Pending'] after:w-full after:h-1 after:border-b after:border-blue-100 after:border-4 after:inline-block">
                            <div class="bg-blue-100 text-blue-700 text-sm font-bold p-3.5 rounded-tl-2xl">{{
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
                            <div class="bg-blue-100 text-blue-700 text-sm font-bold p-3.5 rounded-tl-2xl">{{
                                statusCounts.paid }}
                            </div>
                            <a href="#" @click="selectedStatus = 'paid'"
                                class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-br-2xl lg:h-12 lg:w-12 shrink-0">
                                <CreditCard />
                            </a>
                        </li>
                        <li
                            class="flex w-full text-xs items-center text-blue-500 after:content-['Shipped'] after:w-full after:h-1 after:border-b after:border-blue-300 after:border-4 after:inline-block">
                            <div class="bg-blue-100 text-blue-700 text-sm font-bold p-3.5 rounded-tl-2xl">{{
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
                            <div class="bg-blue-100 text-blue-700 text-sm font-bold p-3.5 rounded-tl-2xl">{{
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
                            <div class="bg-red-100 text-red-700 text-sm font-bold p-3.5 rounded-tl-2xl">{{
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
                        <div class="overflow-x-auto sm:rounded-md pb-4">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-blue-100">
                                    <tr>
                                        <th scope="col" class="px-3 py-3 truncate">No.</th>
                                        <th scope="col" class="px-3 py-3 truncate">Kode Pembayaran</th>
                                        <th scope="col" class="px-3 py-3 truncate">Status</th>
                                        <th scope="col" class="px-3 py-3 truncate">Metode Pembayaran</th>
                                        <th scope="col" class="px-3 py-3 truncate">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(payment, index) in filteredPayments" :key="payment.id"
                                        class="bg-white border-b hover:bg-blue-100">
                                        <td class="w-4 p-4 text-center">{{ index + 1
                                            }}.</td>
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
                                        <td class="px-3 py-3 truncate">{{ payment.payment_method }}</td>
                                        <td class="px-3 py-3 truncate">{{ $formatCurrency(payment.amount) }}</td>
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
        </div>
    </AuthenticatedLayout>
</template>
