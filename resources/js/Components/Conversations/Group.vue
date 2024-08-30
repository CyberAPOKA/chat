<script setup>
import { useConversationStore } from '@/stores/conversationStore';

const props = defineProps({
    conversation: Object
});

const conversationStore = useConversationStore();

const loadConversation = () => {
    conversationStore.loadConversation(props.conversation.id);
};
</script>

<template>
    <div class="flex items-center mb-2 cursor-pointer hover:bg-[--surface-300] rounded-md p-2"
        @click="loadConversation">
        <div class="size-12 bg-[--surface-300] rounded-full mr-3">
            <img src="/assets/images/users.png" alt="Group Avatar" class="size-12 rounded-full">
        </div>
        <div class="flex-1 overflow-hidden">
            <h2 class="text-lg font-semibold truncate">
                {{ conversation.name }}
            </h2>
            <p class="text-gray-600 truncate max-w-full text-sm">
                {{ conversation?.messages?.[0]?.content }}
            </p>
        </div>
    </div>
</template>
