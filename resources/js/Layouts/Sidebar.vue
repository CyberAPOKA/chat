<script setup>
import { ref } from 'vue';
import Button from "primevue/button"
import Popover from 'primevue/popover';
import Contacts from '@/Components/Contacts.vue';

const props = defineProps({
    conversations: Array
});
const showContacts = ref(null);
const selectedConversation = ref(null);
const loadConversation = (id) => {
    axios.get(route('load.conversation', id))
        .then(function (response) {
            // manipula o sucesso da requisição
            console.log(response);
            selectedConversation.value = response.data
        })
        .catch(function (error) {
            // manipula erros da requisição
            console.error(error);
        })
        .finally(function () {
            // sempre será executado
        });
};

const toggleContacts = (event) => {
    showContacts.value.toggle(event);
}
</script>
<template>
    <div class="bg-[--surface-50] border-r border-[--surface-500] w-full xl:w-96">

        <!-- Sidebar Header -->
        <header
            class="p-4 border-b border-[--surface-500] flex justify-between items-center bg-[--surface-0] text-[--text-color]">
            <h1 class="text-2xl font-semibold text-[--text-color]">Chats</h1>
            <div class="flex gap-2">
                <Button icon="pi pi-pencil" @click="toggleContacts" />
            </div>
        </header>
        <Popover ref="showContacts" class="!bg-[--surface-0]">
            <Contacts />
        </Popover>

        <!-- Contact List -->
        <div class="bg-[--surface-50] text-[--text-color] overflow-y-auto h-screen p-2 mb-9 pb-20" v-motion-slide-right>
            <div v-for="conversation in conversations" :key="conversation.id"
                class="flex items-center mb-2 cursor-pointer hover:bg-[--surface-300] rounded-md p-2"
                @click="loadConversation(conversation.id)">
                <div class="size-12 bg-[--surface-300] rounded-full mr-3">
                    <img :src="conversation.is_group ? '/assets/images/users.png' : conversation?.users?.[0]?.profile_photo_url || ''"
                        alt="User Avatar" class="size-12 rounded-full">
                </div>
                <div class="flex-1 overflow-hidden">
                    <h2 class="text-lg font-semibold truncate">
                        {{ conversation.is_group ? conversation.name : conversation?.users?.[0]?.name }}
                    </h2>
                    <p class="text-gray-600 truncate max-w-full text-sm">
                        {{ conversation?.messages?.[0]?.content }}
                    </p>
                </div>
            </div>
        </div>

    </div>
</template>