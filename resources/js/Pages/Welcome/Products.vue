<script setup>
import { ref, computed, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CardView from '@/Components/CardView.vue';
import SearchInput from '@/Components/SearchInput.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Product from '../Products/Detail/Product.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CartPlus from '@/Components/Icons/CartPlus.vue';
import CardHeading from '@/Components/Icons/CardHeading.vue';

const props = defineProps({
    products: Array,

    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const { auth } = usePage().props;
const userId = ref('');

if (auth.user !== null) {
    userId = ref(auth.user.id);
}

const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(12);

const filteredProducts = computed(() => {
    if (!searchQuery.value) {
        return props.products;
    }
    return props.products.filter(product =>
        product.category.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        product.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const paginatedProducts = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredProducts.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredProducts.value.length / itemsPerPage.value);
});

const showAllProduct = () => {
    itemsPerPage.value = props.products.length;
    currentPage.value = 1;
};

const hideSomeProduct = () => {
    itemsPerPage.value = 12;
};

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

watch(searchQuery, () => {
    currentPage.value = 1;
});

const showingModalProductDetail = ref(false);
const selectedProduct = ref(null);

const showModalProductDetail = (product) => {
    selectedProduct.value = product;
    showingModalProductDetail.value = true;
};

const closeModal = () => {
    showingModalProductDetail.value = false;
};

const getRandomImage = (product) => {
    const images = product.product_images;
    if (images.length > 1) {
        return images[Math.floor(Math.random() * images.length)].image;
    } else if (images.length === 1) {
        return images[0].image;
    } else {
        return null;
    }
};

const storeCart = (product) => {
    if (userId.value === null) {
        router.get(route('login'));
    }

    router.post(route('store.product.carts', {
        user_id: userId.value,
        product_id: product.id,
        quantity: 1, // Default quantity
    }), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        },
        onError: (errors) => {
            if (errors.name || errors.description || errors.price || errors.stock || errors.waight || errors.categori_id) {
                alert('Product failed!');
            } else {
                const errorMessages = Object.values(errors).flat();
                alert(`${errorMessages}`);
            }
        }
    });

}
</script>

<template>
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between sm:flex-row flex-col gap-4 pt-2 pb-4 sm:px-0 bg-white">
            <div class="flex items-center gap-2 me-auto">
                <span>Produk</span>
            </div>
            <SearchInput v-model:searchQuery="searchQuery" placeholder="Cari" />
        </div>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-2 my-2 text-sm font-bold text-blue-900">
        <div v-for="product in paginatedProducts" :key="product.id">
            <CardView :category="product.category.name" :name="product.name" :price="$formatCurrency(product.price)">
                <template #img>
                    <div v-if="getRandomImage(product)">
                        <img :src="getRandomImage(product)" :alt="product.name"
                            class="h-33 sm:h-44 w-full object-cover" />
                    </div>
                </template>
                <template #button>
                    <div class="flex justify-between gap-4">
                        <SecondaryButton @click="showModalProductDetail(product)">
                            <CardHeading width="16" height="16" />
                        </SecondaryButton>
                        <PrimaryButton>
                            <CartPlus @click="storeCart(product)" width="16" height="16" />
                        </PrimaryButton>
                    </div>
                </template>
            </CardView>

        </div>
    </div>

    <div class="flex flex-col items-center pt-2">
        <SecondaryButton v-if="itemsPerPage === 12" @click="showAllProduct" class="w-full justify-center">Show
            All
            &nbsp;
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-chevron-compact-down" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M1.553 6.776a.5.5 0 0 1 .67-.223L8 9.44l5.776-2.888a.5.5 0 1 1 .448.894l-6 3a.5.5 0 0 1-.448 0l-6-3a.5.5 0 0 1-.223-.67" />
            </svg>
        </SecondaryButton>
        <SecondaryButton v-if="itemsPerPage !== 12" @click="hideSomeProduct" class="w-full justify-center">
            Hiding some &nbsp;
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-chevron-compact-up" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M7.776 5.553a.5.5 0 0 1 .448 0l6 3a.5.5 0 1 1-.448.894L8 6.56 2.224 9.447a.5.5 0 1 1-.448-.894z" />
            </svg>
        </SecondaryButton>
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

    <!-- Detail product modal -->
    <Modal maxWidth="6xl" :show="showingModalProductDetail">
        <div class="p-6">
            <div class="flex justify-between items-center text-blue-900">
                <h2 class="text-lg font-medium text-gray-900">{{ selectedProduct.name }}
                </h2>
                <DangerButton @click="closeModal">X</DangerButton>
            </div>
            <Product :product="selectedProduct" />
        </div>
    </Modal>
</template>
