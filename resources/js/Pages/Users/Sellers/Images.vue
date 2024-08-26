<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    seller: Object,
});

const form = useForm({
    image: null,
});

const previewUrl = ref(null);

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const submitForm = () => {
    form.post(route('store.seller.image', {
        seller_id: props.seller.id,
        alt: props.seller.phone,
    }), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('image');
            previewUrl.value = null;
            emit('addSellerImage');
        },
        onError: (errors) => {
            if (errors.image) {
                alert('addition failed!');
            } else {
                const errorMessages = Object.values(errors).flat();
                alert(`${errorMessages}`);
            }
        }
    });
};

const emit = defineEmits(['addSellerImage']);
</script>

<template>
    <div class="relative flex w-full flex-1 items-stretch">
        <div class="w-full">
            <form @submit.prevent="submitForm" class="mt-3 space-y-3">
                <div>
                    <InputLabel for="image" value="Gambar" />
                    <input type="file" id="image" @change="handleFileChange" class="mt-1 block w-full" />
                    <InputError :message="form.errors.image" />
                </div>
                <div v-if="previewUrl" class="my-4">
                    <p class="font-semibold">Pratinjau</p>
                    <img :src="previewUrl" alt="Image Preview" class="w-full h-auto mt-2 rounded" />
                </div>
                <div>
                    <PrimaryButton class="mt-6 mb-3" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        Simpan
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
