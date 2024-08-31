<script setup>
import SwitchTheme from '@/Components/SwitchTheme.vue';
import UserSettings from '@/Components/UserSettings.vue';
</script>

<template>
    <div class="hidden xl:flex flex-col justify-between gap-4 p-2 bg-[--surface-0]">
        <SwitchTheme /> <!-- Altera o tema (Claro/Escuro) -->
        <UserSettings /> <!-- Configurações do usuário -->
    </div>
</template>