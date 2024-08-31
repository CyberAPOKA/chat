import { defineStore } from 'pinia';
import axios from 'axios';

export const useConversationStore = defineStore('conversation', {
    state: () => ({
        contacts: [],
        selectedConversation: null,
        conversations: []
    }),
    actions: {
        async loadContacts() {
            try {
                const response = await axios.get(route('get.contacts'));
                console.log('contacts:', response.data);
                this.contacts = response.data;
            } catch (error) {
                console.error('Error loading contacts:', error);
            }
        },
        setSelectedConversation(conversation) {
            this.selectedConversation = conversation;
        },
        async loadConversation(id) {
            try {
                const response = await axios.get(route('load.conversation', id));
                this.setSelectedConversation(response.data);
            } catch (error) {
                console.error('Error loading conversation:', error);
            }
        },
        async loadConversations() {
            try {
                const response = await axios.get(route('get.conversations'));
                this.conversations = response.data;
            } catch (error) {
                console.error('Error loading conversations:', error);
            }
        },
        updateConversationMessage(conversationId, message) {
            const conversation = this.conversations.find(c => c.id === conversationId);
            if (conversation) {
                // Atualiza a última mensagem e o updated_at
                conversation.messages[0] = message;
                conversation.updated_at = message.created_at;

                // Reordena as conversas, movendo a conversa atualizada para o topo
                this.conversations = this.conversations.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
            }
        },
        updateProfilePhoto(userId, profilePhotoUrl) {
            if (this.selectedConversation) {
                const user = this.selectedConversation.users.find(user => user.id === userId);
                if (user) {
                    user.profile_photo_url = profilePhotoUrl;
                }
            }
            // Atualiza a imagem de perfil em todas as conversas
            this.conversations.forEach(conversation => {
                const user = conversation.users.find(user => user.id === userId);
                if (user) {
                    user.profile_photo_url = profilePhotoUrl;
                }
            });
        },
        updateUnreadMessages(conversationId) {
            const conversation = this.conversations.find(c => c.id === conversationId);
            if (conversation && conversation.id !== this.selectedConversation?.id) {
                conversation.unreadCount = (conversation.unreadCount || 0) + 1;
            }
        },

        resetUnreadMessages(conversationId) {
            const conversation = this.conversations.find(c => c.id === conversationId);
            if (conversation) {
                conversation.unreadCount = 0;
            }
        },
    },
    getters: {
        filteredContacts: (state) => (search) => {
            if (!search) {
                return state.contacts;
            }
            return state.contacts.filter(contact => {
                return contact.name.toLowerCase().includes(search.toLowerCase()) ||
                    contact.phone.includes(search);
            });
        }
    }
});
