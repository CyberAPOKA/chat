<script setup>
import { ref, onMounted } from 'vue';
import { useConversationStore } from '@/stores/conversationStore';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    conversation: Object
});

const page = usePage();
const userId = ref(page.props.auth.user.id);
const conversationStore = useConversationStore();

const loadConversation = (conversationId) => {
    conversationStore.loadConversation(conversationId).then(() => {
        conversationStore.resetUnreadMessages(conversationId);
    });
};


const updateProfilePhotoInConversation = (userId, profilePhotoUrl) => {
    const user = props.conversation.users.find(user => user.id === userId);
    if (user) {
        user.profile_photo_url = profilePhotoUrl;
    }
};

const updateNameInConversation = (userId, name) => {
    const user = props.conversation.users.find(user => user.id === userId);
    if (user) {
        user.name = name;
    }
};

onMounted(() => {
    window.Echo.channel('global')
        .listen('ProfilePhotoUpdated', (data) => {
            if (data.user_id !== userId.value) {
                updateProfilePhotoInConversation(data.user_id, data.profile_photo_url);
            }
        })
        .listen('NameUpdated', (data) => {
            if (data.user_id !== userId.value) {
                updateNameInConversation(data.user_id, data.name);
            }
        });
});

const getOtherUser = (conversation) => {
    const userId = usePage().props.auth.user.id;
    return conversation?.users?.find(user => user.id !== userId);
};
</script>

<template>
    <div class="flex items-center mb-2 cursor-pointer hover:bg-[--surface-300] rounded-md p-2"
        @click="loadConversation(conversation.id)">
        <div class="size-12 bg-[--surface-300] rounded-full mr-3">
            <img :src="getOtherUser(conversation)?.profile_photo_url || '/assets/images/user.png'" alt="User Avatar"
                class="size-12 rounded-full">
        </div>
        <div class="flex-1 overflow-hidden">
            <h2 class="text-lg font-semibold truncate flex items-center">
                {{ getOtherUser(conversation)?.name }}
                <span v-if="conversation.unreadCount"
                    class="ml-2 text-sm text-[--surface-0] bg-[--surface-900] rounded-full w-5 h-5 flex items-center justify-center">
                    {{ conversation.unreadCount }}
                </span>
            </h2>
            <p class="text-gray-600 truncate max-w-full text-sm">
                {{ conversation.messages[0]?.content }}
            </p>
        </div>
    </div>
</template>
