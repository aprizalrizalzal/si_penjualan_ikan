<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, router } from '@inertiajs/vue3';
import Footer from '../Footer.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Back from '@/Components/Icons/Back.vue';

const goToWelcome = () => {
    router.get(route('welcome'))
}
</script>
<template>

    <Head title="Info" />
    <div class="py-8">
        <div class="animate-fade-down max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
            <div class="flex items-center justify-between sm:flex-row flex-col gap-4 p-2 sm:px-0 bg-white">
                <div class="flex items-center gap-2 px-2">
                </div>
            </div>
            <div class="p-12 hover:shadow max-w-8xl mx-auto">
                <h1 class="text-2xl text-center font-bold text-blue-700 mb-4">Sistem Informasi Penjualan Ikan (SIPI-Desa
                    Soro)</h1>
                <p class="text-blue-900 mb-4">
                    SIPI-Desa Soro adalah sebuah platform digital yang dirancang untuk memfasilitasi transaksi penjualan
                    ikan secara online di Desa Soro. Sistem ini memudahkan para nelayan dan pembeli untuk melakukan
                    transaksi secara efisien dan transparan, dari proses pemesanan hingga pembayaran.
                </p>
                <h2 class="text-xl font-semibold text-blue-600 mb-2">Fitur Utama:</h2>
                <ul class="list-disc list-inside text-blue-900 mb-4">
                    <li>Pencatatan pesanan ikan secara otomatis.</li>
                    <li>Pelacakan status pesanan (Pending, Paid, Shipped, Completed, Cancelled).</li>
                    <li>Sistem pembayaran yang aman dan terpercaya.</li>
                    <li>Dashboard untuk pemantauan penjualan dan pembelian.</li>
                </ul>
                <p class="text-blue-900">
                    Dengan SIPI-Desa Soro, penjualan ikan menjadi lebih mudah, aman, dan dapat diakses kapan saja dan di
                    mana saja. Dukung perkembangan ekonomi desa melalui teknologi digital!
                </p>
                <p class="text-blue-900 mt-4">
                    Alamat: Kampung Nelayan Desa Soro, Kecamatan Kempo, Dompu, Nusa Tenggara Barat, Indonesia.
                </p>
                <div class="flex justify-center pt-6">
                    <p class="text-xs">Versi.1.0(Beta)</p>
                </div>
            </div>

        </div>
        <div class="animate-fade flex items-center justify-center py-8">
            <SecondaryButton class="gap-2 py-3" @click="goToWelcome">
                <Back widht="16" height="16" />
                Kembali
            </SecondaryButton>
        </div>
        <!-- Page Footer -->
        <footer class="animate-fade-up pb-4 text-center text-sm text-gray-900 bg-white">
            <Footer />
        </footer>
    </div>
</template>