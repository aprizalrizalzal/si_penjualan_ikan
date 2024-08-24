<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    carousels: Array,
});

const showModalAddCarousel = ref(false);

const showingModalAddCarouselSuccessfully = ref(false);

const showModalAddCarouselSuccessfully = () => {
    showModalAddCarousel.value = false;
    showingModalAddCarouselSuccessfully.value = true;
};

const closeModalAddCarouselSuccessfully = () => {
    showingModalAddCarouselSuccessfully.value = false;
};

const closeModal = () => {
    showModalAddCarousel.value = false;
};

</script>

<template>

    <Head title="Setting" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center ms-2">

                <h2 class="font-bold text-green-700 text-lg leading-4 flex-none px-2 py-4">Setting</h2>
            </div>
        </template>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded">
                    <h2 class="font-bold mb-4 text-green-700 text-lg mb-4">Carousels</h2>
                    <!-- <CarouselTable :carousels="props.carousels" /> -->
                    <div class="flex flex-col my-2 items-start">
                        <SecondaryButton @click="showModalAddCarousel = true" class="py-3 w-full">Add Carousel
                        </SecondaryButton>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </AuthenticatedLayout>

    <Modal :show="showModalAddCarousel">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Add Carousel</span>
                <DangerButton @click="closeModal">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <!-- <CarouselForm @addCarousel="showModalAddCarouselSuccessfully" /> -->
        </div>
    </Modal>

    <Modal maxWidth="xl" :show="showingModalAddCarouselSuccessfully">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Add Carousel</span>
                <DangerButton @click="closeModalAddCarouselSuccessfully">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <p class="my-4 text-sm text-green-700">
                Carousel Add Successful!
            </p>
            <div class="mt-2 flex">
                <PrimaryButton @click="closeModalAddCarouselSuccessfully">Ok</PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
