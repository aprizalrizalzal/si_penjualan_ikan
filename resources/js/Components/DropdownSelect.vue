<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
    id: String,
    label: String,
    valueProperty: String,
    optionProperty: String,
    options: {
        type: Array,
        required: true,
    },
    placeholder: String,
});

const modelValue = ref('');
const searchTerm = ref('');
const showDropdown = ref(false);
const filteredOptions = ref([...props.options]);
const dropdownContainer = ref(null);

const emit = defineEmits(['update:modelValue']);

watch(modelValue, (value) => {
    emit('update:modelValue', value);
});

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

const filterOptions = () => {
    filteredOptions.value = props.options.filter(option =>
        option[props.optionProperty].toLowerCase().includes(searchTerm.value.toLowerCase())
    );
};

const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value;
};

const selectOption = (option) => {
    modelValue.value = option[props.valueProperty];
    searchTerm.value = option[props.optionProperty];
    showDropdown.value = false;
};

const handleClickOutside = (event) => {
    if (dropdownContainer.value && !dropdownContainer.value.contains(event.target)) {
        showDropdown.value = false;
    }
};
</script>

<template>
    <div ref="dropdownContainer">
        <label class="block font-medium text-sm text-gray-700" :for="id">{{ label }}</label>
        <div class="relative">
            <input type="text"
                class="mt-1 w-full border-blue-300 focus:border-blue-300 focus:ring-blue-700 rounded shadow" :id="id"
                v-model="searchTerm" @input="filterOptions" @focus="showDropdown = true" :placeholder="placeholder" />
            <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" @click="toggleDropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-chevron-down" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708" />
                </svg>
            </span>
            <div v-if="showDropdown"
                class="absolute z-10 bg-white border border-blue-700 rounded mt-1 w-full max-h-20 overflow-auto">
                <ul>
                    <li v-for="option in filteredOptions" :key="option[valueProperty]" @click="selectOption(option)"
                        class="p-2 text-sm hover:bg-blue-100 cursor-pointer">
                        {{ option[optionProperty] }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped>
select {
    padding: 0.5rem;
    border: 0.5px solid #256125;
    border-radius: 4px;
    width: 100%;
}

.max-h-40 {
    max-height: 10rem;
    /* Adjust this value as needed */
}
</style>
