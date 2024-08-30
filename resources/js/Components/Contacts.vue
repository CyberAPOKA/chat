<script setup>
import { ref, computed, onMounted } from 'vue';
import Group from './Group.vue';
import Contact from './Contact.vue';
import InputText from 'primevue/inputtext';

const addingContact = ref(false);
const addingGroup = ref(false);
const contacts = ref([]);
const search = ref('');

onMounted(() => {
    getContacts();
});

const getContacts = () => {
    axios.get(route('get.contacts'))
        .then(function (response) {
            contacts.value = response.data;
        })
        .catch(function (error) {
            console.error(error);
        });
}

const filteredContacts = computed(() => {
    if (!search.value) {
        return contacts.value;
    }
    return contacts.value.filter(contact => {
        return contact.name.toLowerCase().includes(search.value.toLowerCase()) || 
               contact.phone.includes(search.value);
    });
});
</script>

<template>
    <div v-motion-slide-left v-if="!addingContact && !addingGroup" class="flex flex-col gap-2">
        <h1 class="text-[--text-color] font-semibold">Nova conversa</h1>

        <InputText type="text" v-model="search" placeholder="Pesquisar nome ou número" class="w-full lg:w-64" />

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
    <Contact v-if="addingContact" @back="addingContact = false" />
    <div>
        <h2 class="text-[--text-color]">Lista de Contatos</h2>
        <ul>
            <li v-for="contact in filteredContacts" :key="contact.id"
                class="mt-2 hover:bg-[--surface-300] p-2 rounded-lg hover:cursor-pointer">
                <div class="flex items-center gap-2">
                    <img v-if="contact.profile_photo_path" :src="contact.profile_photo_path" alt=""
                        class="size-12 rounded-full">
                    <img v-else src="/assets/images/user.png" alt="" class="size-12 border-2 rounded-full">
                    <h1 class="text-sm text-[--text-color]">{{ contact.name }}</h1>
                </div>
            </li>
        </ul>
    </div>
</template>