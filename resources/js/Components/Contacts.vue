<script setup>
import { ref } from 'vue';
import Group from './Group.vue';
import Contact from './Contact.vue';

const addingContact = ref(false);
const addingGroup = ref(false);

</script>

<template>
    <div v-motion-slide-left v-if="!addingContact && !addingGroup" class="flex flex-col gap-2">
        <h1>Nova conversa</h1>
        <input type="text" placeholder="Pesquisar nome ou número">
        <button class="flex items-center gap-2 hover:bg-[--surface-300] text-[--text-color] p-2 rounded-lg"
            @click="addingGroup = true">
            <i class="pi pi-users p-4 border rounded-full border-[--text-color]"></i>
            <span>Novo grupo</span>
        </button>
        <button class="flex items-center gap-2 hover:bg-[--surface-300] text-[--text-color] p-2 rounded-lg"
            @click="addingContact = true">
            <i class="pi pi-user-plus p-4 border rounded-full border-[--text-color]"></i>
            <span>Adicionar contato</span>
        </button>
    </div>
    <Group v-if="addingGroup" @back="addingGroup = false" />
    <Contact v-if="addingContact" @back="addingContact = false"/>

</template>