<script setup>
import { ref } from 'vue';

const imgWidth = ref('');
const imgHeight = ref('');

const headerSrc = '/storage/images/header/logo-sipi.png';
const altText = 'Favicon';

const imgClass = 'block';
</script>

<template>
    <div>
        <img :src="headerSrc" :alt="altText" :class="imgClass">
    </div>
</template>
