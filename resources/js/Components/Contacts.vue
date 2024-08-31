<script setup>
import { ref, computed, onMounted } from 'vue';
import { useConversationStore } from '@/stores/conversationStore';
import Group from './Group.vue';
import Contact from './Contact.vue';
import InputText from 'primevue/inputtext';

const addingContact = ref(false);
const addingGroup = ref(false);
const contacts = ref([]);
const search = ref('');
const conversationStore = useConversationStore();

onMounted(() => {
    conversationStore.loadContacts();
});

const filteredContacts = computed(() => {
    return conversationStore.filteredContacts(search.value);
});

const selectContactConversation = (contact) => {
    console.log(contact);

    if (!contact.conversation_id) {
        // Se não houver uma conversa, cria uma nova
        axios.post(route('create.conversation'), {
            contact_id: contact.id
        })
            .then(function (response) {
                conversationStore.setSelectedConversation(response.data);
            })
            .catch(function (error) {
                console.error(error);
            });
    } else {
        // Se a conversa já existir, apenas carrega a conversa
        axios.get(route('load.conversation', contact.conversation_id))
            .then(function (response) {
                conversationStore.setSelectedConversation(response.data);
            })
            .catch(function (error) {
                console.error(error);
            });
    }
}


</script>

<template>
    <div>
        <div v-motion-slide-left v-if="!addingContact && !addingGroup" class="flex flex-col gap-2">
            <h1 class="text-[--text-color] font-semibold">{{ $t('new_conversation') }}</h1>

            <InputText type="text" v-model="search" :placeholder="$t('search_number_or_name')" class="w-full lg:w-64" />

            <button class="flex items-center gap-2 hover:bg-[--surface-300] text-[--text-color] p-2 rounded-lg"
                @click="addingGroup = true">
                <i class="pi pi-users p-4 border rounded-full border-[--text-color]"></i>
                <span>{{ $t('new_group') }}</span>
            </button>
            <button class="flex items-center gap-2 hover:bg-[--surface-300] text-[--text-color] p-2 rounded-lg"
                @click="addingContact = true">
                <i class="pi pi-user-plus p-4 border rounded-full border-[--text-color]"></i>
                <span>{{ $t('add_contact') }}</span>
            </button>
        </div>
        <Group v-if="addingGroup" @back="addingGroup = false" />
        <Contact v-if="addingContact" @back="addingContact = false" />
        <div v-motion-slide-right v-if="!addingContact && !addingGroup" class="mt-2">
            <h2 class="text-[--text-color]">{{ $t('contact_list') }}</h2>
            <ul>
                <li v-for="contact in filteredContacts" :key="contact.id"
                    class="mt-2 hover:bg-[--surface-300] p-2 rounded-lg hover:cursor-pointer"
                    @click="selectContactConversation(contact)">
                    <div class="flex items-center gap-2">
                        <img :src="contact.profile_photo_path ? 'storage/' + contact.profile_photo_path : '/assets/images/user.png'"
                            alt="" class="size-12 rounded-full">
                        <h1 class="text-sm text-[--text-color]">{{ contact.name }}</h1>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>