<script setup>
import { ref, onMounted } from 'vue';
import Button from 'primevue/button';
const currentTheme = ref(localStorage.getItem('theme') || 'light');

const toggleTheme = () => {
    currentTheme.value = currentTheme.value === 'light' ? 'dark' : 'light';
    localStorage.setItem('theme', currentTheme.value);
    document.documentElement.setAttribute('data-theme', currentTheme.value);
};

onMounted(() => {
    document.documentElement.setAttribute('data-theme', currentTheme.value);
});
</script>

<template>
    <div class="hidden xl:flex flex-col gap-4 p-6 bg-gray-100">
        <Button label="1" icon="pi pi-check" />
        <Button label="2" icon="pi pi-check" />
        <Button label="toggle" icon="pi pi-check" @click="toggleTheme" />
        {{ currentTheme }}
        <h1 class="text-[var(--text-color)]">TESTE</h1>
        <link :href="`/themes/${currentTheme}/theme.css`" rel="stylesheet" class="hidden">
    </div>
</template>