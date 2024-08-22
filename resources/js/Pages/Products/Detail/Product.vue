<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    product: Object,
});

const productImages = ref(props.product.product_images || []);
const initialImage = ref(
    props.product.product_images[
        Math.floor(Math.random() * props.product.product_images.length)
    ].image
);
const selectedImage = ref(initialImage.value);

const formatDescription = (description) => {
    return description.replace(/\n/g, '<br>');
};

const selectImage = (imagePath) => {
    selectedImage.value = (selectedImage.value === imagePath) ? initialImage.value : imagePath;
};

watch(() => props.product.product_images.image, (newValue) => {
    initialImage.value = newValue;
    selectedImage.value = newValue;
});
</script>

<template>
    <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 mt-4">
        <div class="h-full">
            <img :src="selectedImage" :alt="product.name" class="w-full rounded">
            <div v-if="productImages.length" class="mt-6">
                <div class="grid grid-cols-4 gap-4 mt-4">
                    <div v-for="(productImage, index) in productImages" :key="index" class="flex relative">
                        <img :src="productImage.image" :alt="productImage.alt"
                            class="h-auto w-64 object-cover rounded cursor-pointer"
                            @click="selectImage(productImage.image)" />
                    </div>
                </div>
            </div>
        </div>
        <div class="text-start">
            <p class="text-lg font-medium rounded text-gray-700">
                {{ product.name }} <span class="text-gray-500">({{ product.category.name }})</span>
            </p>
            <p class="text-gray-500 text-sm font-bold">
                <span>Berat: </span>{{ product.weight }} Kg
            </p>
            <p class="text-gray-700 text-sm font-bold mt-2">
                <span>Stok: </span>{{ product.stock }}
            </p>
            <hr>
            <p class="text-gray-700 text-sm overflow-hidden" v-html="formatDescription(product.description)">
            </p>
            <hr>
            <p class="inline-block py-2 rounded font-bold text-gray-900">
                {{ $formatCurrency(product.price) }}
            </p>
        </div>
    </div>
</template>

<style scoped>
.relative {
    position: relative;
}

.cursor-pointer {
    cursor: pointer;
}
</style>
