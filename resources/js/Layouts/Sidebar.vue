<script setup>
import { ref, onMounted } from 'vue';
import { useConversationStore } from '@/stores/conversationStore';
import Button from "primevue/button";
import Popover from 'primevue/popover';
import Contacts from '@/Components/Contacts.vue';
import Individual from '@/Components/Conversations/Individual.vue';
import Group from '@/Components/Conversations/Group.vue';

const conversationStore = useConversationStore();
const showContacts = ref(null);

const toggleContacts = (event) => {
    showContacts.value.toggle(event);
};

onMounted(() => {
    conversationStore.loadConversations();

    window.Echo.channel('global')
        .listen('ProfilePhotoUpdated', (data) => {
            if (data.user_id !== conversationStore.userId) {
                conversationStore.updateProfilePhoto(data.user_id, data.profile_photo_url);
            }
        });
});
</script>
<template>
    <div class="bg-[--surface-50] border-r border-[--surface-500] w-full xl:w-96">
        <!-- Sidebar Header -->
        <header
            class="p-4 border-b border-[--surface-500] flex justify-between items-center bg-[--surface-0] text-[--text-color]">
            <h1 class="text-2xl font-semibold text-[--text-color]">{{ $t('chats') }}</h1>
            <div class="flex gap-2">
                <Button icon="pi pi-pencil" @click="toggleContacts" />
            </div>
        </header>
        <Popover ref="showContacts" class="!bg-[--surface-0] max-h-[34rem] overflow-y-auto">
            <Contacts />
        </Popover>

        <!-- Conversas -->
        <div class="bg-[--surface-50] text-[--text-color] overflow-y-auto h-screen p-2 mb-9 pb-20" v-motion-slide-right>
            <template v-for="conversation in conversationStore.conversations" :key="conversation.id">
                <template v-if="!conversation.is_group">
                    <Individual :conversation="conversation" />
                </template>
                <template v-else>
                    <Group :conversation="conversation" />
                </template>
            </template>
        </div>
    </div>
</template>
