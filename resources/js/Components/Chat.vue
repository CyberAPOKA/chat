<script setup>
import { ref, watch, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useConversationStore } from '@/stores/conversationStore';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';

const page = usePage();
const userId = ref(page.props.auth.user.id);
const conversationStore = useConversationStore();
const newMessage = ref('');
const messageContainer = ref(null);

const sendMessage = () => {
    if (!newMessage.value.trim()) return;

    axios.post(route('messages.store', conversationStore.selectedConversation?.id), {
        content: newMessage.value
    })
        .then(response => {
            conversationStore.selectedConversation?.messages.push(response.data);
            newMessage.value = '';
            scrollToBottom(); // Scrolla para o final
        })
        .catch(error => {
            console.error(error);
        });
};

const listenEvents = (conversationId) => {
    if (!conversationId) return;

    window.Echo.private(`conversations.${conversationId}`)
        .listen('MessageSent', (data) => {
            if (data.message.user_id !== userId.value) {
                conversationStore.selectedConversation.messages.push(data.message);
                scrollToBottom(); // Scrolla para o final
            }
        });
};

const scrollToBottom = () => {
    nextTick(() => {
        if (messageContainer.value) {
            messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
        }
    });
};

// Observa a mensagem selecionada e chama a função para scrollar 
watch(() => conversationStore.selectedConversation?.messages, () => {
    scrollToBottom();
});

watch(() => conversationStore.selectedConversation, (newConversation) => {
    if (newConversation) {
        listenEvents(newConversation.id);
        scrollToBottom(); // Scrolla para o final quando a conversa é carregada
    }
});

</script>

<template>
    <div class="flex-1 hidden xl:flex xl:flex-col">
        <!-- Chat Header -->
        <header class="bg-white p-4 text-gray-700" v-if="conversationStore.selectedConversation">
            <h1 class="text-2xl font-semibold">{{ conversationStore.selectedConversation?.name ||
                conversationStore.selectedConversation?.users?.[0]?.name }}</h1>
        </header>

        <!-- Chat Messages -->
        <div ref="messageContainer" class="h-screen overflow-y-auto p-4 pb-4"
            v-if="conversationStore.selectedConversation">
            <div v-for="(message, index) in conversationStore.selectedConversation?.messages" :key="message.id"
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


        <footer class="bg-[--surface-0] border-t border-gray-300 p-4  w-full"
            v-if="conversationStore.selectedConversation">
            <div class="flex justify-center items-center">
                <Textarea v-model="newMessage" @keyup.enter="sendMessage" rows="1" autoResize
                    placeholder="Escreva uma mensagem..."
                    class="bg-[--surface-50] text-[--text-color] flex-grow p-2 border rounded-md focus:outline-none focus:ring-2 min-h-12 max-h-96 !overflow-y-auto" />

                <div class="flex items-center"> 
                    <button @click="sendMessage" :disabled="!newMessage" :class="{'opacity-75 cursor-not-allowed' : !newMessage}"
                        class="ml-2 bg-[--surface-900] text-[--primary-color] px-4 py-2 rounded-md h-full min-h-12 font-semibold">
                        Enviar
                        <i class="pi pi-send text-[--primary-color]"></i>
                    </button>
                </div>
            </div>
        </footer>
    </div>
</template>
