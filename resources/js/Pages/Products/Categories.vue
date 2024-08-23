<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SearchInput from '@/Components/SearchInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PencilSquare from '@/Components/Icons/PencilSquare.vue';
import Trash3 from '@/Components/Icons/Trash3.vue';
import PlusCircle from '@/Components/Icons/PlusCircle.vue';
import BoxArrowLeft from '@/Components/Icons/BoxArrowLeft.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    categories: Array,
});

const form = useForm({
    name: '',
    description: '',
});

const goToProducts = () => {
    router.visit(route('show.products'))
}

const searchQuery = ref('');

const filteredCategoriesSearch = computed(() => {
    if (!searchQuery.value) {
        return props.categories;
    }
    return props.categories.filter(category =>
        category.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        category.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const currentPage = ref(1);
const itemsPerPage = 10;

const paginatedCategories = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredCategoriesSearch.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredCategoriesSearch.value.length / itemsPerPage);
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

const selectedCategory = ref(null);

const showingModalCategory = ref(false);
const confirmingCategoryDeletion = ref(false);

const showModalCategory = (category) => {
    showingModalCategory.value = true;
    selectedCategory.value = category;

    if (category) {
        form.name = category.name;
        form.description = category.description;
    }
};

const confirmcategoryDeletion = (category) => {
    confirmingCategoryDeletion.value = true;
    selectedCategory.value = category;
    form.id = category.id;
};

const submitForm = () => {
    if (!selectedCategory.value.id) {
        form.post(route('store.category'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
            onError: (errors) => {
                if (errors.name || errors.description) {
                    alert('Category failed!');
                } else {
                    const errorMessages = Object.values(errors).flat();
                    alert(`${errorMessages}`);
                }
            }
        });
    } else {
        form.put(route('update.category', { id: selectedCategory.value.id }), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
            onError: (errors) => {
                if (errors.name || errors.description) {
                    alert('Category failed!');
                } else {
                    const errorMessages = Object.values(errors).flat();
                    alert(`${errorMessages}`);
                }
            }
        });
    }
}

const deleteCategory = () => {
    router.delete(route('destroy.category', { id: selectedCategory.value.id }), {
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
    showingModalCategory.value = false;
    confirmingCategoryDeletion.value = false;
};
</script>

<template>

    <Head title="Categories" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kategori</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="flex items-center justify-between sm:flex-row flex-col gap-4 pt-2 pb-4 px-4 sm:px-0 bg-white">
                    <div class="flex items-center gap-2 me-auto">
                        <PrimaryButton @click="showModalCategory" class="gap-2 shadow-none py-2.5 capitalize">
                            <PlusCircle width="16" height="16" />Kategori
                        </PrimaryButton>
                        <span>/</span>
                        <SecondaryButton @click="goToProducts" class="gap-2 shadow-none py-2.5 capitalize">
                            <BoxArrowLeft width="16" height="16" /> Produk
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
                                    Kategori
                                </th>
                                <th scope="col" class="px-3 py-3 truncate">
                                    Deskripsi
                                </th>
                                <th scope="col" class="px-2 py-3 text-center truncate" colspan="2">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(category, index) in paginatedCategories" :key="category.id"
                                class="bg-white border-b hover:bg-blue-100">
                                <td class="w-4 p-4 text-center"> {{ (currentPage - 1) * itemsPerPage + index + 1 }}.
                                </td>
                                <th scope="row" class="flex items-center px-2 py-3 text-gray-900 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-base font-semibold">{{ category.name }} </div>
                                        <div class="font-normal text-gray-500">Kuantitas: {{
                                            category.products_count }}
                                        </div>
                                    </div>
                                </th>
                                <td class="px-3 py-3 truncate max-w-xs">
                                    <div class="font-normal text-gray-500">{{ category.description }}</div>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Modal toggle Edit -->
                                    <a href="#" type="button" @click="showModalCategory(category)"
                                        class="flex gap-2 items-center justify-center font-normal px-2 text-blue-600 hover:underline">
                                        <PencilSquare width="16" height="16" />Sunting
                                    </a>
                                </td>
                                <td class="px-3 py-3 truncate">
                                    <!-- Modal toggle Delete -->
                                    <a href="#" type="button" @click="confirmcategoryDeletion(category)"
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

                <!-- Store/Update category modal -->
                <Modal :show="showingModalCategory">
                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <h2 v-if="!selectedCategory" class="text-lg font-medium text-gray-900">
                                Tambah Kategori
                            </h2>
                            <h2 class="text-lg font-medium text-gray-900">
                                Kategori <strong>{{ selectedCategory.name }}</strong>
                            </h2>
                            <DangerButton @click="closeModal">X</DangerButton>
                        </div>
                        <form @submit.prevent="submitForm" class="mt-3 space-y-3">
                            <div>
                                <InputLabel for="name" value="Nama" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name"
                                    placeholder="Nama Kategori" required autofocus />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>
                            <div>
                                <InputLabel for="description" value="Deskripsi" />
                                <TextInput id="description" type="text" class="mt-1 block w-full"
                                    v-model="form.description" placeholder="Deskripsi" required autofocus />
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                            <div class="mt-6 flex justify-start">
                                <PrimaryButton>Simpan</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </Modal>

                <!-- Delete category modal -->
                <Modal :show="confirmingCategoryDeletion">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus kategori <strong>{{
                                selectedCategory.name
                                }}</strong>?
                        </h2>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah kategori <strong>{{ selectedCategory.name }}</strong> dihapus,
                            semua
                            sumber daya
                            dan
                            datanya
                            akan dihapus secara permanen.
                        </p>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                            <DangerButton class="ms-3" @click="deleteCategory">Hapus</DangerButton>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>