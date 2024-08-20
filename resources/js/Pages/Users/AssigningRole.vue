<script setup>
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const form = useForm({
    email: '',
    role: ''
});

const props = defineProps({
    userId: Number,
    user: Object,
});

const getUserRoles = (user) => {
    return user.roles.length > 0 ? user.roles : [];
};

const assignRole = (role) => {
    form.role = role;
    form.email = props.user.email;
    form.post(route('assign.roles'), {
        onSuccess: () => {
            emit('assignRole');
        },
        onError: (errors) => {
            const errorMessages = Object.values(errors).flat();
            alert(`${errorMessages}`);
        }
    });
};

const removeRole = (role) => {
    form.role = role;
    form.email = props.user.email;
    form.delete(route('remove.role'), {
        onSuccess: () => {
            emit('assignRole');
        },
        onError: (errors) => {
            const errorMessages = Object.values(errors).flat();
            alert(`${errorMessages}`);
        }
    });
};

const emit = defineEmits(['assignRole']);
</script>

<template>
    <div class="flex flex-col w-full flex-1 items-stretch">
        <p class=" mt-1 text-sm text-gray-700">Silakan pilih peran yang ingin Anda tambahkan atau hapus untuk pengguna
            <strong>{{ props.user.name }}</strong>
        </p>
        <div class="flex flex-row gap-2 mt-1 text-sm text-gray-700">
            <PrimaryButton @click="assignRole('admin')" class="my-2 lowercase">
                <span class="capitalize">Tambah</span>#admin
            </PrimaryButton>
            <PrimaryButton @click="assignRole('user')" class="my-2 lowercase">
                <span class="capitalize">Tambah</span>#user
            </PrimaryButton>
        </div>
        <p class="flex gap-2 mt-1 text-sm text-red-700">Peran yang dipegang oleh pengguna
            <strong>{{ props.user.name }}</strong>
        </p>
        <div class="flex flex-row gap-2 mt-1 text-sm text-red-700">
            <template v-for="role in getUserRoles(props.user)" :key="role.id">
                <DangerButton class="my-2 lowercase" @click="removeRole(role.name)">
                    <span class="capitalize">Hapus</span>#{{ role.name }}
                </DangerButton>
            </template>
        </div>
    </div>
</template>
