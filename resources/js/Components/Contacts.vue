<script setup>
import { ref } from 'vue';

const addingContact = ref(false);
</script>

<template>
    <div v-motion-slide-left v-if="!addingContact">
        <h1>Nova conversa</h1>
        <input type="text" placeholder="Pesquisar nome ou número">
        <button class="flex items-center gap-2" @click="addingContact = true">
            <i class="pi pi-users p-4 border rounded-full"></i>
            <span>Adicionar contato</span>
        </button>
    </div>
    <div v-if="addingContact" v-motion-slide-left>
        Nome do contato
    </div>
</template>