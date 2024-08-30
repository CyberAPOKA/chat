import { defineStore } from 'pinia';
import axios from 'axios';

export const useConversationStore = defineStore('conversation', {
    state: () => ({
        contacts: [],
        selectedConversation: null,
    }),
    actions: {
        async loadContacts() {
            try {
                const response = await axios.get(route('get.contacts'));
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
        }
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
