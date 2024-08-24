<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

</script>
<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Foto Profil</h2>

            <p class="mt-1 text-sm text-gray-600">
                Perbarui foto profil Anda.
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <img class="rounded-full shadow w-80 h-80 m-auto"
                src="../../../../../storage/app/public/images/header/logo-sipi.png" alt="image description">
            <div class="flex items-center gap-4">
                <SecondaryButton class="mt-4">Unggah</SecondaryButton>
                <DangerButton class="mt-4">Hapus</DangerButton>
            </div>
        </div>
    </section>
</template>