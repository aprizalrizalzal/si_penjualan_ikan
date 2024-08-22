<script setup>
import { ref, computed, watch } from 'vue';
import CardView from '@/Components/CardView.vue';
import SearchInput from '@/Components/SearchInput.vue';
import productDetail from '../product/productDetail.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import ButtonImage from '@/Components/ButtonImage.vue';
import BackIcon from '@/Components/Icon/BackIcon.vue';
import NextIcon from '@/Components/Icon/NextIcon.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Product from './Products/Detail/Product.vue';

const props = defineProps({
    products: Array,
});

const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(12);

const filteredProducts = computed(() => {
    if (!searchQuery.value) {
        return props.products;
    }
    return props.products.filter(product =>
        product.category_spare_part.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
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

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
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
</script>

<template>
    <div class="bg-white flex justify-between items-center py-4 px-8 rounded mt-2 gap-4">
        <div class="flex items-center">
            <h2 class="flex items-center gap-2 font-bold text-lg text-green-900 leading-4 flex-none py-4">
                <span>Spare Part</span>
            </h2>
        </div>
        <div class="flex w-full items-center">
            <SearchInput v-model:searchQuery="searchQuery"
                placeholder="Search for the part name, category or description" />
        </div>


    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-2 my-2 text-sm font-bold text-green-900">
        <div v-for="product in paginatedProducts" :key="product.id">
            <CardView @click="showModalProductDetail(product)" :category="product.category_spare_part.name"
                :name="product.name" :price="formatCurrency(product.price)">
                <template #img>
                    <img :src="product.image_path" :alt="product.name"
                        class="h-33 sm:h-44 w-full object-cover shadow" />
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

    <div class="flex justify-center gap-4 items-center pt-6">
        <ButtonImage class="py-2" @click="previousPage" :disabled="currentPage === 1">
            <BackIcon />
        </ButtonImage>
        <span>{{ currentPage }} / {{ totalPages }}</span>
        <ButtonImage class="py-2" @click="nextPage" :disabled="currentPage === totalPages">
            <NextIcon />
        </ButtonImage>
    </div>

    <Modal maxWidth="7xl" :show="showingModalProductDetail">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Detail Spare Part</span>
                <DangerButton @click="closeModal">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <Product :product="selectedProduct" />
        </div>
    </Modal>
</template>
