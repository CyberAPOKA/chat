<script setup>
import { useConversationStore } from '@/stores/conversationStore';

const props = defineProps({
    conversation: Object
});

const conversationStore = useConversationStore();

const loadConversation = (conversationId) => {
    conversationStore.loadConversation(conversationId).then(() => {
        conversationStore.resetUnreadMessages(conversationId);
    });
};

</script>

<template>
    <div class="flex items-center mb-2 cursor-pointer hover:bg-[--surface-300] rounded-md p-2"
        @click="loadConversation(conversation.id)">
        <div class="size-12 bg-[--surface-300] rounded-full mr-3">
            <img src="/assets/images/users.png" alt="Group Avatar" class="size-12 rounded-full">
        </div>
        <div class="flex-1 overflow-hidden">
            <h2 class="text-lg font-semibold truncate flex items-center">
                {{ conversation.name }}
                <span v-if="conversation.unreadCount"
                    class="ml-2 text-sm text-white bg-red-500 rounded-full w-5 h-5 flex items-center justify-center">
                    {{ conversation.unreadCount }}
                </span>
            </h2>
            <p class="text-gray-600 truncate max-w-full text-sm">
                {{ conversation?.messages?.[0]?.content }}
            </p>
        </div>
    </div>
</template>
