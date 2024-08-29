<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import Button from "primevue/button"
import Popover from 'primevue/popover';
import Contacts from '@/Components/Contacts.vue';

const props = defineProps({
    conversations: Array
});
const page = usePage();
const userId = ref(page.props.auth.user.id);
const selectedConversation = ref(null);
const showContacts = ref(null);

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
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <div class="w-full xl:w-fit flex">
            <!-- Config bar -->
            <div class="hidden xl:flex flex-col gap-4 p-6 bg-gray-100">
                <Button label="1" icon="pi pi-check" />
                <Button label="2" icon="pi pi-check" />
                <Button label="3" icon="pi pi-check" />
            </div>
            <div class="bg-white border-r border-gray-300 w-full xl:w-96">

                <!-- Sidebar Header -->
                <header class="p-4 border-b border-gray-300 flex justify-between items-center bg-indigo-600 text-white">
                    <h1 class="text-2xl font-semibold">Chats</h1>
                    <div class="flex gap-2">
                        <Button icon="pi pi-pencil" @click="toggleContacts" />
                    </div>
                </header>
                <Popover ref="showContacts">
                    <Contacts />
                </Popover>

                <!-- Contact List -->
                <div class="overflow-y-auto h-screen p-2 mb-9 pb-20" v-motion-slide-right>
                    <div v-for="conversation in conversations" :key="conversation.id"
                        class="flex items-center mb-2 cursor-pointer hover:bg-gray-100 rounded-md p-2"
                        @click="loadConversation(conversation.id)">
                        <div class="w-12 h-12 bg-gray-300 rounded-full mr-3">
                            <img :src="conversation.is_group ? 'path/to/group_default_image.jpg' : conversation?.users?.[0]?.profile_photo_url || ''"
                                alt="User Avatar" class="w-12 h-12 rounded-full">
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
        </div>

        <!-- Main Chat Area -->
        <div class="flex-1 hidden xl:block">
            <!-- Chat Header -->
            <header class="bg-white p-4 text-gray-700" v-if="selectedConversation">
                <h1 class="text-2xl font-semibold">{{ selectedConversation?.name ||
                    selectedConversation?.users?.[0]?.name }}</h1>
            </header>

            <!-- Chat Messages -->
            <div class="h-screen overflow-y-auto p-4 pb-36" v-if="selectedConversation">
                <div v-for="message in selectedConversation?.messages" :key="message.id"
                    :class="message.user_id === userId ? 'justify-end' : 'justify-start'"
                    class="flex mb-4 cursor-pointer">

                    <!-- Avatar do outro usuário (esquerda) -->
                    <div v-if="message.user_id !== userId"
                        class="w-9 h-9 rounded-full flex items-center justify-center mr-2">
                        <img :src="selectedConversation?.users?.find(user => user.id === message.user_id)?.profile_photo_url || ''"
                            alt="User Avatar" class="w-8 h-8 rounded-full">
                    </div>

                    <!-- Mensagem -->
                    <div :class="message.user_id === userId ? 'bg-indigo-500 text-white' : 'bg-white text-gray-700'"
                        class="flex max-w-96 rounded-lg p-3 gap-3">
                        <p>{{ message.content }}</p>
                    </div>

                </div>
            </div>

            <Footer v-if="selectedConversation" />
        </div>
    </div>
</template>