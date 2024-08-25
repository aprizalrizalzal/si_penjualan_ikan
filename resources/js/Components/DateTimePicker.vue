<script setup>
import { ref, onMounted, watch } from 'vue';

import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

const props = defineProps({
  id: {
    type: String,
    required: true
  },
  label: String,
  placeholder: {
    type: String,
    default: "Pilih Tanggal"
  },
  modelValue: String
});

const emit = defineEmits(['update:modelValue']);
const datepicker = ref(null);
const formattedDate = ref(props.modelValue);

onMounted(() => {
  const fp = flatpickr(datepicker.value, {
    dateFormat: "d/m/Y",
    defaultDate: formattedDate.value,
    onChange: (selectedDates, dateStr) => {
      formattedDate.value = dateStr;
    }
  });

  datepicker.value.addEventListener('focus', () => {
    fp.open();
  });

  datepicker.value.addEventListener('blur', () => {
    setTimeout(() => {
      if (!fp.calendarContainer.contains(document.activeElement)) {
        fp.close();
      }
    }, 100);
  });
});

watch(formattedDate, (newValue) => {
  emit('update:modelValue', newValue);
});
</script>

<template>
  <div class="date-picker">
    <label :for="id" class="block font-medium text-sm text-blue-700 mb-1">{{ label }}</label>
    <input :id="id" v-model="formattedDate"
      class="w-full border-blue-700 focus:border-blue-700 focus:ring-blue-700 rounded shadow" :placeholder="placeholder"
      ref="datepicker" />
  </div>
</template>

<style scoped>
.flatpickr-input.flatpickr-mobile {
  display: none;
}
</style>