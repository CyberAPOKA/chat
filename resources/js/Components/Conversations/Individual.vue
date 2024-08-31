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

const loadConversation = () => {
    conversationStore.loadConversation(props.conversation.id);
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
        @click="loadConversation">
        <div class="size-12 bg-[--surface-300] rounded-full mr-3">
            <img :src="getOtherUser(conversation)?.profile_photo_url || '/assets/images/user.png'" alt="User Avatar"
                class="size-12 rounded-full">
        </div>
        <div class="flex-1 overflow-hidden">
            <h2 class="text-lg font-semibold truncate">
                {{ getOtherUser(conversation)?.name }}
            </h2>
            <p class="text-gray-600 truncate max-w-full text-sm">
                {{ conversation.messages[0]?.content }}
            </p>
        </div>
    </div>
</template>