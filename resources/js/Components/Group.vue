<script setup>
import { onMounted, computed, ref } from 'vue';
import { useConversationStore } from '@/stores/conversationStore';
import Checkbox from 'primevue/checkbox';
import axios from 'axios';
import InputText from 'primevue/inputtext';

const emit = defineEmits(['back']);

const search = ref('');
const conversationStore = useConversationStore();

onMounted(() => {
    conversationStore.loadContacts();
});

const filteredContacts = computed(() => {
    return conversationStore.filteredContacts(search.value);
});

const selectedContacts = ref([]);

const toggleSelection = (contactId) => {
    const index = selectedContacts.value.indexOf(contactId);
    if (index === -1) {
        selectedContacts.value.push(contactId);
    } else {
        selectedContacts.value.splice(index, 1);
    }
};
const name = ref('');
const createGroup = () => {
    axios.post(route('create.group'), {
        name: name.value,
        contact_ids: selectedContacts.value
    })
        .then(response => {
            emit('back');
        })
        .catch(error => {
            console.error('Error creating group:', error);
        });
};

</script>

<template>
    <div class="flex gap-4">
        <button @click="emit('back')">
            <i class="pi pi-arrow-left text-[--text-color]"></i>
        </button>
        <h1 class="text-[--text-color]">
            {{ $t('new_group') }}
        </h1>
    </div>

    <div v-motion-slide-left class="mt-2">
        <h1 class="text-[--text-color]">{{ $t('select_users') }}</h1>
        <div class="my-2">
            <InputText type="text" v-model="name" placeholder="Nome do grupo" class="bg-[surface-0] text-[--text-color]"
                v-if="selectedContacts.length > 0" v-motion-slide-visible-once-bottom />
            <button @click="createGroup" v-if="selectedContacts.length > 0 && name.length > 0"
                :disabled="!selectedContacts.length" class="bg-[--primary-color] text-white p-2 rounded-lg mt-2 ml-2"
                v-motion-slide-visible-once-top>
                {{ $t('create_group') }}
            </button>
        </div>
        <div v-motion-slide-right class="mt-2">
            <h2 class="text-[--text-color]">{{ $t('contact_list') }}</h2>
            <ul>
                <li v-for="contact in filteredContacts" :key="contact.id"
                    class="mt-2 hover:bg-[--surface-300] p-2 rounded-lg hover:cursor-pointer"
                    @click="toggleSelection(contact.id)">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex gap-2">
                            <img :src="contact.profile_photo_path ? 'storage/' + contact.profile_photo_path : '/assets/images/user.png'"
                                alt="" class="size-12 rounded-full">
                            <div class="flex flex-col justify-around">
                                <h1 class="text-sm text-[--text-color]">{{ contact.name }}</h1>
                                <p class="text-xs text-[--text-color]">Status...</p>
                            </div>
                        </div>
                        <Checkbox v-model="selectedContacts" :value="contact.id" @click.stop />
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
