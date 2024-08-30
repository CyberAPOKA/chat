<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useConversationStore } from '@/stores/conversationStore';
import Footer from '@/Layouts/Footer.vue';

const page = usePage();
const userId = ref(page.props.auth.user.id);
const conversationStore = useConversationStore();
const newMessage = ref('');

const sendMessage = () => {
    if (!newMessage.value.trim()) return;

    axios.post(route('messages.store', conversationStore.selectedConversation.id), {
        content: newMessage.value
    })
    .then(response => {
        // Adiciona a nova mensagem na lista de mensagens da conversa atual
        conversationStore.selectedConversation.messages.push(response.data);
        newMessage.value = ''; // Limpa o campo de mensagem
    })
    .catch(error => {
        console.error(error);
    });
};
</script>

<template>
    <div class="flex-1 hidden xl:block">
        <!-- Chat Header -->
        <header class="bg-white p-4 text-gray-700" v-if="conversationStore.selectedConversation">
            <h1 class="text-2xl font-semibold">{{ conversationStore.selectedConversation?.name ||
                conversationStore.selectedConversation?.users?.[0]?.name }}</h1>
        </header>

        <!-- Chat Messages -->
        <div class="h-screen overflow-y-auto p-4 pb-36" v-if="conversationStore.selectedConversation">
            <div v-for="message in conversationStore.selectedConversation?.messages" :key="message.id"
                :class="message.user_id === userId ? 'justify-end' : 'justify-start'" class="flex mb-4 cursor-pointer">

                <!-- Avatar do outro usuário (esquerda) -->
                <div v-if="message.user_id !== userId"
                    class="w-9 h-9 rounded-full flex items-center justify-center mr-2">
                    <img :src="conversationStore.selectedConversation?.users?.find(user => user.id === message.user_id)?.profile_photo_url || '/assets/images/user.png'"
                        alt="User Avatar" class="w-8 h-8 rounded-full">
                </div>

                <!-- Mensagem -->
                <div class="flex max-w-96 rounded-lg p-3 gap-3 bg-[--surface-100] text-[--text-color]">
                    <p>{{ message.content }}</p>
                </div>

            </div>
        </div>

        <!-- Input para Nova Mensagem -->
        <div v-if="conversationStore.selectedConversation" class="absolute bottom-0 left-0 right-0 p-4 bg-white border-t">
            <div class="flex items-center">
                <input v-model="newMessage" @keyup.enter="sendMessage" type="text" placeholder="Escreva uma mensagem..."
                    class="flex-grow p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button @click="sendMessage" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-md">Enviar</button>
            </div>
        </div>
    </div>
</template>
