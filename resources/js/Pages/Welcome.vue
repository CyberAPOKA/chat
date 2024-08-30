<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Footer from '@/Layouts/Footer.vue';
import ConfigBar from '@/Layouts/ConfigBar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
const props = defineProps({
    conversations: Array
});

const page = usePage();
const userId = ref(page.props.auth.user.id);
const selectedConversation = ref(null);

</script>

<template>

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <div class="w-full xl:w-fit flex">
            <!-- Config bar -->
            <ConfigBar />
            <Sidebar :conversations="conversations" />
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
                    <div class="flex max-w-96 rounded-lg p-3 gap-3 bg-[--surface-100] text-[--text-color]">
                        <p>{{ message.content }}</p>
                    </div>

                </div>
            </div>

            <Footer v-if="selectedConversation" />

        </div>
    </div>
</template>