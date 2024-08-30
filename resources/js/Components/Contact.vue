<script setup>
import { useForm } from 'laravel-precognition-vue-inertia';
import InputMask from 'primevue/inputmask';
import FloatLabel from 'primevue/floatlabel';
import InputLabel from '@/Components/InputLabel.vue';
import { ref, watch } from 'vue';
import axios from 'axios';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useConversationStore } from '@/stores/conversationStore';
import { useMotion } from '@vueuse/motion';

const emit = defineEmits(['back']);
const conversationStore = useConversationStore();
const form = useForm('post', route('add.contact'), {
    phone: '',
});
const target = ref(null);
const phoneFormatted = ref('');
const userFound = ref(false);
const userNotFound = ref(false);
const user = ref(null);
const success = ref(false);
const { variant } = useMotion(target, {
    initial: {
        opacity: 0,
        x: -200,
    },
    enter: {
        opacity: 1,
        x: 0,
    },
    leave: {
        opacity: 0,
        x: 200,
    },
});
const submit = () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            success.value = true;
            conversationStore.loadContacts();
            setTimeout(() => {
                variant.value = 'leave';
                setTimeout(() => {
                    success.value = false;
                    variant.value = 'initial';
                }, 500); // Tempo para a animação de saída
            }, 1500); // 1,5 segundos de exibição
        },
    });
};

watch(() => form.phone, (newValue) => {
    userFound.value = false;
    userNotFound.value = false;
    user.value = false;
    form.errors.phone = '';
    phoneFormatted.value = newValue.replace(/[^0-9]/g, ''); // Remove os caracteres especiais

    if (phoneFormatted.value.length === 13) {
        axios.post(route('check.user.phone'), { phone: phoneFormatted.value })
            .then(function (response) {
                user.value = response.data;
                userFound.value = true;
                userNotFound.value = false;
            })
            .catch(function (error) {
                userNotFound.value = true;
                userFound.value = false;
            });
    }
});
</script>

<template>
    <div class="flex gap-4">
        <button @click="emit('back')">
            <i class="pi pi-arrow-left"></i>
        </button>
        <h1>Novo contato</h1>
    </div>
    <div v-motion-slide-left class="mt-8">
        <form>
            <FloatLabel>
                <InputMask v-model="form.phone" mask="+99 99 9 9999-9999" placeholder="+99 99 9 9999-9999" fluid
                    @change="form.validate('phone')" />
                <InputLabel for="phone">Número</InputLabel>
            </FloatLabel>
            <div v-if="userFound" class="flex flex-col items-center">
                <div class="flex items-center gap-2 mt-3">
                    <img :src="user.profile_photo_path ? user.profile_photo_path : '/assets/images/user.png'" alt=""
                        class="size-12 rounded-full">
                    <span class="text-sm font-semibold">{{ user.name }}</span>
                </div>
                <div class="text-red-500 text-sm mt-2">
                    {{ form.errors.phone }}
                </div>
                <PrimaryButton class="mt-2" @click.prevent="submit">Adicionar</PrimaryButton>
            </div>
        </form>
        <div v-if="userNotFound" class="text-red-500 mt-1">
            Usuário não encontrado
        </div>
        <div v-if="success" ref="target" class="bg-[--surface-800] text-green-500 p-2 mt-2 rounded-lg text-center">
            Usuário adicionado
        </div>
    </div>
</template>
