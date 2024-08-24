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
import PlusCircle from '@/Components/Icons/PlusCircle.vue';
import InputError from '@/Components/InputError.vue';
import DropdownSelect from '@/Components/DropdownSelect.vue';
import Images from './Images.vue';

const { auth } = usePage().props;
const roles = ref(auth.roles);
const hasRole = (role) => roles.value.includes(role);

const isAdmin = computed(() => hasRole('admin'));

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
const selectedPaymentImage = ref(null);

const showingModalPayment = ref(false);
const showingModalUploadProofPayment = ref(false);
const confirmingPaymentImageDeletion = ref(false);
const confirmingPaymentDeletion = ref(false);

const showModalUploadProofPayment = (payment) => {
    showingModalUploadProofPayment.value = true;
    selectedPayment.value = payment;
};

const confirmPaymentImageDeletion = (paymentImage) => {
    selectedPaymentImage.value = paymentImage;
    confirmingPaymentImageDeletion.value = true;
};

const deletePaymentImage = () => {
    router.delete(route('destroy.payment.image', { id: selectedPaymentImage.value.id }), {
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

const form = useForm({
    status: '',
});

const status = [
    { id: 1, name: 'check' },
    { id: 2, name: 'pending' },
    { id: 3, name: 'paid' },
    { id: 4, name: 'shipped' },
    { id: 5, name: 'completed' },
    { id: 6, name: 'cancelled' }
];

const showingModalStatusUpdate = ref(false);

const showModalStatusUpdate = (payment) => {
    showingModalStatusUpdate.value = true;
    selectedPayment.value = payment;

    if (payment) {
        form.status = payment.status;
    }
};

const submitForm = () => {
    form.put(route('update.payment', {
        id: selectedPayment.value.id,
        order_id: selectedPayment.value.order_id,
        amount: selectedPayment.value.amount,
        payment_method: selectedPayment.value.payment_method,
    }), {
        preserveScroll: true,
        onSuccess: () => {
            form.data();
            closeModal();
        },
        onError: (errors) => {
            if (errors.status) {
                alert('update failed!');
            } else {
                const errorMessages = Object.values(errors).flat();
                alert(`${errorMessages}`);
            }
        }
    });
};

const closeModal = () => {
    showingModalPayment.value = false;
    showingModalStatusUpdate.value = false;
    showingModalUploadProofPayment.value = false;
    confirmingPaymentImageDeletion.value = false;
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
                                <th scope="col" class="px-3 py-3 truncate">Bukti Pembayaran</th>
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
                                <td class="px-3 py-3 truncate ">
                                    <div class="flex items-center">
                                        <div v-for="(paymentImages) in payment.payment_images" :key="paymentImages.id"
                                            class="relative me-2">
                                            <img :src="`${paymentImages.image}`" :alt="paymentImages.alt"
                                                class="h-16 w-16 object-cover rounded " style="max-width: 128px;" />
                                            <botton @click="confirmPaymentImageDeletion(paymentImages)"
                                                class="absolute top-0.5 right-0.5 inline-flex bg-white items-center p-0.5 rounded font-semibold text-xs text-red-900 tracking-widest shadow hover:bg-red-100 focus:outline-none focus:ring-1 focus:ring-red-900 opacity-75 transition ease-in-out duration-150">
                                                <Trash3 width="16" height="16" class="hover:w-6 hover:h-6" />
                                            </botton>
                                        </div>
                                        <botton v-if="payment.payment_images.length < 2"
                                            @click="showModalUploadProofPayment(payment)"
                                            class="bg-white items-center p-0.5 rounded font-semibold text-xs text-blue-900 tracking-widest hover:bg-blue-100 focus:outline-none focus:ring-1 focus:ring-blue-900 transition ease-in-out duration-150">
                                            <PlusCircle width="16" height="16" class="hover:w-6 hover:h-6" />
                                        </botton>
                                    </div>
                                </td>
                                <td class="px-3 py-3 truncate">{{ payment.payment_code }}</td>
                                <td class="px-3 py-3 truncate capitalize">
                                    <a v-if="isAdmin" href="#" type="button" @click="showModalStatusUpdate(payment)"
                                        class="flex gap-2 items-center font-normal text-blue-600 hover:underline">
                                        {{ payment.status }}
                                        <PencilSquare width="16" height="16" />
                                    </a>
                                    <p v-else class="flex gap-2 items-center font-normal text-blue-600 hover:font-bold">
                                        {{ payment.status }}
                                    </p>

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
                                <td class="w-4 p-4 text-center truncate">
                                    #
                                </td>
                                <td class="px-3 py-3 font-bold truncate" colspan="4">
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
                            <strong class="w-24 text-gray-800 text-sm">Check:</strong>
                            <span class="text-gray-800 text-sm">Pesanan sedang dalam pengecekan awal sebelum diproses
                                lebih
                                lanjut.</span>
                        </li>
                        <li class="flex items-start">
                            <strong class="w-24 text-gray-800 text-sm">Pending:</strong>
                            <span class="text-gray-800 text-sm">Pesanan dibuat, menunggu pembayaran atau
                                konfirmasi.</span>
                        </li>
                        <li class="flex items-start">
                            <strong class="w-24 text-gray-800 text-sm">Paid:</strong>
                            <span class="text-gray-800 text-sm">Pembayaran diterima, pesanan siap diproses.</span>
                        </li>
                        <li class="flex items-start">
                            <strong class="w-24 text-gray-800 text-sm">Shipped:</strong>
                            <span class="text-gray-800 text-sm">Pesanan telah dikirim, dalam perjalanan ke
                                pelanggan.</span>
                        </li>
                        <li class="flex items-start">
                            <strong class="w-24 text-gray-800 text-sm">Completed:</strong>
                            <span class="text-gray-800 text-sm">Pesanan selesai dan diterima oleh pelanggan.</span>
                        </li>
                        <li class="flex items-start">
                            <strong class="w-24 text-gray-800 text-sm">Cancelled:</strong>
                            <span class="text-gray-800 text-sm">Pesanan dibatalkan, tidak akan diproses lebih
                                lanjut.</span>
                        </li>
                    </ul>
                </div>

                <!-- Update payment item modal -->
                <Modal :show="showingModalStatusUpdate">
                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900">
                                Pembayaran <strong>{{ selectedPayment.payment_code }}</strong>
                            </h2>
                            <DangerButton @click="closeModal">X</DangerButton>
                        </div>
                        <form @submit.prevent="submitForm" class="mt-3 space-y-3">
                            <div>
                                <DropdownSelect id="status" label="Status" optionProperty="name" valueProperty="name"
                                    :options="status" v-model="form.status"
                                    :placeholder='selectedPayment ? selectedPayment.status : "Pilih Status"'
                                    class="capitalize w-full" />
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>
                            <h3 class="text-sm font-semibold mb-2 text-gray-800">Keterangan Status</h3>
                            <ul class="space-y-1">
                                <li class="flex items-start">
                                    <strong class="w-24 text-gray-800 text-sm">Check:</strong>
                                    <span class="text-gray-800 text-sm">Pesanan sedang dalam pengecekan awal sebelum
                                        diproses
                                        lebih
                                        lanjut.</span>
                                </li>
                                <li class="flex items-start">
                                    <strong class="w-24 text-gray-800 text-sm">Pending:</strong>
                                    <span class="text-gray-800 text-sm">Pesanan dibuat, menunggu pembayaran atau
                                        konfirmasi.</span>
                                </li>
                                <li class="flex items-start">
                                    <strong class="w-24 text-gray-800 text-sm">Paid:</strong>
                                    <span class="text-gray-800 text-sm">Pembayaran diterima, pesanan siap
                                        diproses.</span>
                                </li>
                                <li class="flex items-start">
                                    <strong class="w-24 text-gray-800 text-sm">Shipped:</strong>
                                    <span class="text-gray-800 text-sm">Pesanan telah dikirim, dalam perjalanan ke
                                        pelanggan.</span>
                                </li>
                                <li class="flex items-start">
                                    <strong class="w-24 text-gray-800 text-sm">Completed:</strong>
                                    <span class="text-gray-800 text-sm">Pesanan selesai dan diterima oleh
                                        pelanggan.</span>
                                </li>
                                <li class="flex items-start">
                                    <strong class="w-24 text-gray-800 text-sm">Cancelled:</strong>
                                    <span class="text-gray-800 text-sm">Pesanan dibatalkan, tidak akan diproses lebih
                                        lanjut.</span>
                                </li>
                            </ul>
                            <div class="mt-6 flex justify-end" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                                <PrimaryButton>Simpan</PrimaryButton>
                            </div>
                        </form>

                    </div>
                </Modal>

                <!-- Detail payment item modal -->
                <Modal maxWidth="4xl" :show="showingModalPayment">
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
                                    <td class="pe-6 py-1.5 truncate capitalize">{{ selectedPayment.status }}</td>
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
                        <div v-if="selectedPayment.payment_images.length > 0" class="mt-2">
                            <p class="text-md text-center font-medium text-gray-900 mb-2">Bukti Pembayaran</p>
                            <div class="flex flex-col sm:flex-row gap-4 m-auto">
                                <div v-for="(paymentImages) in selectedPayment.payment_images" :key="paymentImages.id"
                                    class="m-auto">
                                    <img :src="`${paymentImages.image}`" :alt="paymentImages.alt"
                                        class="h-64 w-128 m-auto object-cover rounded " style="max-width: 1080px;" />
                                </div>
                            </div>
                            <p class="text-md text-center font-medium text-gray-900 mt-2">Jika gambar terlihat terlalu
                                kecil,
                                klik kanan pada gambar dan pilih 'Buka gambar di tab
                                baru'
                                untuk melihatnya dalam ukuran penuh.</p>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <PrimaryButton @click="closeModal">Ok</PrimaryButton>
                        </div>
                    </div>
                </Modal>

                <Modal :show="showingModalUploadProofPayment">
                    <div class="p-6">
                        <div class="flex justify-between items-center ps-6 ms-6 text-blue-900">
                            <span class="font-bold text-center w-full">Tambah Gambar</span>
                            <DangerButton @click="closeModal">X</DangerButton>
                        </div>
                        <Images :payment="selectedPayment" @uploadProofPaymentImage="closeModal" />
                    </div>
                </Modal>

                <Modal :show="confirmingPaymentImageDeletion">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus gambar bukti pembayaran
                            <strong>{{ selectedPaymentImage.alt }}</strong>?
                        </h2>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah gambar bukti pembayaran <strong>{{ selectedPaymentImage.alt }}</strong> dihapus,
                            semua
                            sumber daya
                            dan
                            datanya
                            akan dihapus secara permanen.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                            <DangerButton @click="deletePaymentImage" class="ms-3">
                                Delete
                            </DangerButton>
                        </div>
                    </div>
                </Modal>

                <Modal :show="confirmingPaymentDeletion">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus pembayaran <strong>{{ selectedPayment.payment_code
                                }}</strong>?
                        </h2>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah pembayaran <strong>{{ selectedPayment.payment_code }}</strong> dihapus, semua
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
