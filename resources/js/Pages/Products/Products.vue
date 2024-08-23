<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SearchInput from '@/Components/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PencilSquare from '@/Components/Icons/PencilSquare.vue';
import Trash3 from '@/Components/Icons/Trash3.vue';
import PlusCircle from '@/Components/Icons/PlusCircle.vue';
import BoxArrowInRight from '@/Components/Icons/BoxArrowInRight.vue';
import CardHeading from '@/Components/Icons/CardHeading.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import DropdownSelect from '@/Components/DropdownSelect.vue';
import Product from './Detail/Product.vue';
import Images from './Images.vue';

const props = defineProps({
    products: Array,
    categories: Array,
});

const form = useForm({
    name: '',
    description: '',
    price: '',
    stock: '',
    weight: '',
    category_id: '',
});

const goToCategories = () => {
    router.visit(route('show.categories'))
}

const searchQuery = ref('');

const filteredProductsSearch = computed(() => {
    if (!searchQuery.value) {
        return props.products;
    }
    return props.products.filter(product =>
        product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        product.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const currentPage = ref(1);
const itemsPerPage = 10;

const paginatedProducts = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredProductsSearch.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredProductsSearch.value.length / itemsPerPage);
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

const selectedProduct = ref(null);
const selectedProductImage = ref(null);

const showingModalProduct = ref(false);
const showingModalAddProductImages = ref(false);
const confirmingProductImageDeletion = ref(false);
const showingModalProductDetail = ref(false);
const confirmingProductDeletion = ref(false);

const showModalProduct = (product) => {
    showingModalProduct.value = true;
    selectedProduct.value = product;

    if (product) {
        form.name = product.name;
        form.description = product.description;
        form.price = product.price;
        form.stock = product.stock;
        form.weight = product.weight;
        form.category_id = product.category_id;
    }
};

const showModalAddProductImages = (product) => {
    selectedProduct.value = product;
    showingModalAddProductImages.value = true;
};

const confirmProductImageDeletion = (productImage) => {
    selectedProductImage.value = productImage;
    confirmingProductImageDeletion.value = true;
};

const deleteProductImage = () => {
    router.delete(route('destroy.product.image', { id: selectedProductImage.value.id }), {
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

const showModalProductDetail = (product) => {
    selectedProduct.value = product;
    showingModalProductDetail.value = true;
};

const confirmProductDeletion = (product) => {
    confirmingProductDeletion.value = true;
    selectedProduct.value = product;
    form.id = product.id;
};

const submitForm = () => {
    if (!selectedProduct.value.id) {
        form.post(route('store.product'), {
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
    } else {
        form.put(route('update.product', { id: selectedProduct.value.id }), {
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
}

const deleteProduct = () => {
    router.delete(route('destroy.product', { id: selectedProduct.value.id }), {
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
    showingModalProduct.value = false;
    showingModalAddProductImages.value = false;
    confirmingProductImageDeletion.value = false;
    showingModalProductDetail.value = false;
    confirmingProductDeletion.value = false;
};
</script>

<template>

    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Produk</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="flex items-center justify-between sm:flex-row flex-col gap-4 pt-2 pb-4 px-4 sm:px-0 bg-white">
                    <div class="flex items-center gap-2 me-auto">
                        <PrimaryButton @click="showModalProduct" class="gap-2 shadow-none py-2.5 capitalize">
                            <PlusCircle width="16" height="16" />Produk
                        </PrimaryButton>
                        <span>/</span>
                        <SecondaryButton @click="goToCategories" class="gap-2 shadow-none py-2.5 capitalize">
                            <BoxArrowInRight width="16" height="16" /> Kategori
                        </SecondaryButton>
                    </div>
                    <SearchInput v-model:searchQuery="searchQuery" placeholder="Cari" />
                </div>
                <div class="overflow-x-auto sm:rounded-md pb-4">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-blue-100">
                            <tr>
                                <th scope="col" class="px-3 py-3 truncate">
                                    No.
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Gambar
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Nama
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Deskripsi
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Harga
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Berat
                                </th>
                                <th scope="col" class="px-2 py-3 text-center truncate" colspan="3">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, index) in paginatedProducts" :key="product.id"
                                class="bg-white border-b hover:bg-blue-100">
                                <td class="w-4 p-4 text-center"> {{ (currentPage - 1) * itemsPerPage + index + 1 }}.
                                </td>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <div class="flex items-center">
                                        <div v-for="(productImage) in product.product_images" :key="productImage.id"
                                            class="relative me-2">
                                            <img :src="`${productImage.image}`" :alt="productImage.alt"
                                                class="h-16 w-16 object-cover rounded " style="max-width: 128px;" />
                                            <botton @click="confirmProductImageDeletion(productImage)"
                                                class="absolute top-0.5 right-0.5 inline-flex bg-white items-center p-0.5 rounded font-semibold text-xs text-red-900 tracking-widest shadow hover:bg-red-100 focus:outline-none focus:ring-1 focus:ring-red-900 opacity-75 transition ease-in-out duration-150">
                                                <Trash3 width="16" height="16" class="hover:w-6 hover:h-6" />
                                            </botton>
                                        </div>
                                        <botton v-if="product.product_images.length < 4"
                                            @click="showModalAddProductImages(product)"
                                            class="bg-white items-center p-0.5 rounded font-semibold text-xs text-blue-900 tracking-widest hover:bg-blue-100 focus:outline-none focus:ring-1 focus:ring-blue-900 transition ease-in-out duration-150">
                                            <PlusCircle width="16" height="16" class="hover:w-6 hover:h-6" />
                                        </botton>
                                    </div>
                                </td>
                                <th scope="row" class="flex items-center px-2 py-3 text-gray-900 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-base font-semibold">{{ product.name }} </div>
                                        <div class="font-normal text-gray-500">{{
                                            product.category.name }}
                                        </div>
                                    </div>
                                </th>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <div class="font-normal text-gray-500">{{ product.description }}</div>
                                </td>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <div class="font-normal text-gray-500">{{ $formatCurrency(product.price) }}</div>
                                </td>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <div class="font-normal text-gray-500">{{ product.weight }} Kg</div>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Modal toggle Edit-->
                                    <a href="#" type="button" @click="showModalProduct(product)"
                                        class="flex gap-2 items-center justify-center font-normal px-2 text-blue-600 hover:underline">
                                        <PencilSquare width="16" height="16" />Sunting
                                    </a>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Modal toggle Detail-->
                                    <a href="#" type="button" @click="showModalProductDetail(product)"
                                        class="flex gap-2 items-center justify-center font-normal px-2 text-gray-600 hover:underline">
                                        <CardHeading width="16" height="16" />Detail
                                    </a>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Modal toggle Hapus-->
                                    <a href="#" type="button" @click="confirmProductDeletion(product)"
                                        class="flex gap-2 items-center justify-center font-normal px-2 text-red-600 hover:underline">
                                        <Trash3 width="16" height="16" />Hapus
                                    </a>
                                </td>
                            </tr>
                        </tbody>
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

                <!-- Store product image modal-->
                <Modal :show="showingModalAddProductImages">
                    <div class="m-6">
                        <div class="flex justify-between items-center ps-6 ms-6 text-blue-900">
                            <span class="font-bold text-center w-full">Tambah Gambar</span>
                            <DangerButton @click="closeModal">X</DangerButton>
                        </div>
                        <Images :product="selectedProduct" @addProductImage="closeModal" />
                    </div>
                </Modal>

                <Modal :show="confirmingProductImageDeletion">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus gambar produk
                            <strong>{{ selectedProductImage.alt }}</strong>?
                        </h2>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah gambar produck <strong>{{ selectedProductImage.alt }}</strong> dihapus,
                            semua
                            sumber daya
                            dan
                            datanya
                            akan dihapus secara permanen.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                            <DangerButton class="ms-3" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing" @click="deleteProductImage">
                                Delete
                            </DangerButton>
                        </div>
                    </div>
                </Modal>

                <!-- Update product modal -->
                <Modal :show="showingModalProduct">
                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <h2 v-if="!selectedProduct" class="text-lg font-medium text-gray-900">
                                Tambah Product
                            </h2>
                            <h2 v-else class="text-lg font-medium text-gray-900">
                                Product <strong>{{ selectedProduct.name }}</strong>
                            </h2>
                            <DangerButton @click="closeModal">X</DangerButton>
                        </div>
                        <form @submit.prevent="submitForm" class="mt-3 space-y-3">
                            <div>
                                <InputLabel for="name" value="Nama" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name"
                                    placeholder="Nama Ikan" required autofocus />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>
                            <div>
                                <DropdownSelect id="category_id" label="Kategori" optionProperty="name"
                                    valueProperty="id" :options="categories" v-model="form.category_id"
                                    :placeholder='selectedProduct && selectedProduct.category ? selectedProduct.category.name : "Pilih Kategori"'
                                    class="w-full" />
                                <InputError class="mt-2" :message="form.errors.category_id" />
                            </div>
                            <div>
                                <InputLabel for="description" value="Deskripsi" />
                                <TextInput id="description" type="text" class="mt-1 block w-full"
                                    v-model="form.description" placeholder="Deskripsi" required autofocus />
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                            <div>
                                <InputLabel for="price" value="Harga (Rp)" />
                                <TextInput id="price" type="text" class="mt-1 block w-full" v-model="form.price"
                                    placeholder="Harga (Rp)" required autofocus />
                                <InputError class="mt-2" :message="form.errors.price" />
                            </div>
                            <div>
                                <InputLabel for="stock" value="Stok" />
                                <TextInput id="stock" type="text" class="mt-1 block w-full" v-model="form.stock"
                                    placeholder="Stok" required autofocus />
                                <InputError class="mt-2" :message="form.errors.stock" />
                            </div>
                            <div>
                                <InputLabel for="weight" value="Berat (Kg)" />
                                <TextInput id="weight" type="text" class="mt-1 block w-full" v-model="form.weight"
                                    placeholder="Berat (Kg)" required autofocus />
                                <InputError class="mt-2" :message="form.errors.weight" />
                            </div>
                            <div class="mt-6 flex justify-start">
                                <PrimaryButton>Simpan</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </Modal>

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

                <!-- Delete product modal -->
                <Modal :show="confirmingProductDeletion">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus produk <strong>{{
                                selectedProduct.name
                            }}</strong>?
                        </h2>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah produck <strong>{{ selectedProduct.name }}</strong> dihapus,
                            semua
                            sumber daya
                            dan
                            datanya
                            akan dihapus secara permanen.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                            <DangerButton class="ms-3" @click="deleteProduct">Hapus</DangerButton>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>