<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Images from './Images.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    customer: Object,
});

const selectedCustomer = ref(null);
const selectedCustomerImage = ref(null);
const showingModalAddCustomerImages = ref(false);
const confirmingCustomerImageDeletion = ref(false);

const showModalAddCustomerImages = (customer) => {
    selectedCustomer.value = customer;
    showingModalAddCustomerImages.value = true;
};

const confirmCustomerImageDeletion = (CustomerImage) => {
    selectedCustomerImage.value = CustomerImage;
    confirmingCustomerImageDeletion.value = true;
};

const deleteCustomerImage = () => {
    router.delete(route('destroy.customer.image', { id: selectedCustomerImage.value.id }), {
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

const closeModal = () => {
    showingModalAddCustomerImages.value = false;
    confirmingCustomerImageDeletion.value = false;
};
</script>
<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Foto Profil</h2>

            <p class="mt-1 text-sm text-gray-600">
                Perbarui foto profil Anda.
            </p>
        </header>

        <div class="animate-fade-right mt-6 space-y-6">
            <img v-if="customer.customer_image !== null" :src="customer.customer_image.image"
                :alt="customer.customer_image.alt" class="rounded-full shadow-lg w-80 h-80 object-cover m-auto" />
            <svg v-else xmlns="http://www.w3.org/2000/svg" width="256" height="256" fill="currentColor"
                class="bi bi-person-circle m-auto" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                <path fill-rule="evenodd"
                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
            </svg>
            <div class="flex items-center gap-4">
                <SecondaryButton v-if="customer.customer_image === null" @click="showModalAddCustomerImages(customer)"
                    class="mt-4 mx-auto">Unggah</SecondaryButton>
                <DangerButton v-else @click="confirmCustomerImageDeletion(customer.customer_image)"
                    class="mt-4 mx-auto">Hapus
                </DangerButton>
            </div>
        </div>
    </section>

    <!-- Store customer image modal-->
    <Modal :show="showingModalAddCustomerImages">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-blue-900">
                <span class="font-bold text-center w-full">Tambah Gambar</span>
                <DangerButton @click="closeModal">X</DangerButton>
            </div>
            <Images :customer="selectedCustomer" @addCustomerImage="closeModal" />
        </div>
    </Modal>

    <Modal :show="confirmingCustomerImageDeletion">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Apakah Anda yakin ingin menghapus foto profil?
            </h2>
            <p class="mt-1 text-sm text-gray-700">
                Setelah foto profil dihapus,
                semua
                sumber daya
                dan
                datanya
                akan dihapus secara permanen.
            </p>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                <DangerButton @click="deleteCustomerImage" class="ms-3">
                    Hapus
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>