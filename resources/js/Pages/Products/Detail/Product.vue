<script setup>
import { ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CartPlus from '@/Components/Icons/CartPlus.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    product: Object,
});

const { auth } = usePage().props;
const userId = ref('');

if (auth.user !== null) {
    userId.value = auth.user.id;
}

const form = useForm({
    user_id: userId.value,
    product_id: '',
    quantity: 1, // Default quantity
});

const productImages = ref(props.product.product_images || []);
const initialImage = ref(
    productImages.value.length > 0
        ? productImages.value[Math.floor(Math.random() * productImages.value.length)].image
        : null
);
const selectedImage = ref(initialImage.value);

const formatDescription = (description) => {
    return description.replace(/\n/g, '<br>');
};

const selectImage = (imagePath) => {
    selectedImage.value = (selectedImage.value === imagePath) ? initialImage.value : imagePath;
};

watch(() => props.product.product_images, (newValue) => {
    initialImage.value = newValue.length > 0
        ? newValue[Math.floor(Math.random() * newValue.length)].image
        : null;
    selectedImage.value = initialImage.value;
});

const confirmingProductCart = ref(false);
const selectedProduct = ref(null);

const confirmProductCart = (product) => {
    confirmingProductCart.value = true;
    selectedProduct.value = product;
    if (product) {
        form.product_id = product.id;
    }
};

const storeCart = () => {
    if (userId.value === null) {
        router.get(route('login'));
    } else {
        form.post(route('store.product.carts'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
            onError: (errors) => {
                if (errors) {
                    alert('Cart failed!');
                } else {
                    const errorMessages = Object.values(errors).flat();
                    alert(`${errorMessages}`);
                }
            }
        });
    }
}

const closeModal = () => {
    confirmingProductCart.value = false;
};
</script>

<template>
    <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mt-4">
        <div class="h-full">
            <img :src="selectedImage" :alt="product.name" class="w-full">
            <div v-if="productImages.length" class="mt-6">
                <div class="grid grid-cols-4 gap-4 mt-4">
                    <div v-for="(productImage, index) in productImages" :key="index" class="flex relative">
                        <img :src="productImage.image" :alt="productImage.alt"
                            class="h-auto w-64 object-cover cursor-pointer" @click="selectImage(productImage.image)" />
                    </div>
                </div>
            </div>
        </div>
        <div class="text-start">
            <div class="pb-2">
                <p class="text-lg font-medium text-gray-700">
                    {{ product.name }} <span class="text-gray-500">({{ product.category.name }})</span>
                </p>
                <p class="text-gray-500 text-sm">
                    <span>Berat: </span>{{ product.weight }} Kg
                </p>
                <p class="text-gray-700 text-sm mt-2">
                    <span>Stok: </span>{{ product.stock }}
                </p>
                <hr>
                <p class="text-gray-700 text-sm overflow-hidden py-2" v-html="formatDescription(product.description)">
                </p>
                <hr>
            </div>
            <div class="pt-2">
                <p class="text-lg font-medium text-gray-700 overflow-hidden">
                    {{ product.seller.name }} (Penjual)
                </p>
                <p class="text-gray-700 text-sm overflow-hidden">
                    {{ product.seller.email }}
                </p>
                <p class="text-gray-700 text-sm overflow-hidden mt-2">
                    {{ product.seller.phone }}
                </p>
                <hr>
                <p class="text-gray-700 text-sm overflow-hidden py-2">
                    {{ product.seller.address }}
                </p>
                <hr>
                <p class="inline-block py-2 font-bold text-gray-900">
                    {{ $formatCurrency(product.price) }}
                </p>
            </div>
            <div class="flex justify-center items-center">
                <PrimaryButton @click="confirmProductCart(product)" class="gap-2">
                    <CartPlus width="16" height="16" />
                    <span>Tambah Ke keranjang</span>
                </PrimaryButton>
            </div>
        </div>
    </div>
    <!-- Confirm cart modal -->
    <Modal :show="confirmingProductCart">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Konfirmasi Produk
            </h2>
            <p class="mt-1 text-sm text-gray-700">
                Setelah produk dipesan, produk akan ditambahkan ke keranjang.
            </p>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                <PrimaryButton class="ms-3" @click="storeCart">Pesan</PrimaryButton>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.relative {
    position: relative;
}

.cursor-pointer {
    cursor: pointer;
}
</style>
