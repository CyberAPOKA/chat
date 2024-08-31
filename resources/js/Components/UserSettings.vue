<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Popover from 'primevue/popover';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ImageCropper from '@/Components/ImageCropper.vue';
import InputText from 'primevue/inputtext';

const page = usePage();

const op = ref();

const toggle = (event) => {
    op.value.toggle(event);
}

const handleImageCropped = ({ blob }) => {
    const formData = new FormData();
    formData.append('profile_photo', blob);

    axios.post(route('update.profile.photo'), formData)
        .then(response => {
            // Atualiza a imagem instantaneamente para o usuário logado
            page.props.auth.user.profile_photo_url = response.data.profile_photo_url;
        })
        .catch(error => {
            console.error('Erro ao atualizar a imagem:', error);
        });
};

const name = ref(page.props.auth.user.name);
const isEditing = ref(false);

const updateName = () => {
    isEditing.value = false;
    if (name.value === page.props.auth.user.name) return;

    axios.post(route('update.name'), { name: name.value })
        .then(response => {
            page.props.auth.user.name = response.data.name;
        })
        .catch(error => console.error('Erro ao atualizar o nome:', error));
};
</script>

<template>
    <div>
        <button @click="toggle">
            <!-- Foto do usuário logado -->
            <img :src="page.props.auth.user.profile_photo_url" alt=""
                class="size-16 rounded-full border-[3px] border-dashed border-[--surface-900]">
        </button>
        <Popover ref="op" class="!bg-[--surface-50] p-4">
            <div class="flex flex-col gap-4 w-64">
                <div class="relative group rounded-lg w-fit">
                    <img :src="page.props.auth.user.profile_photo_url" alt="" class="size-28 rounded-full">
                    <div class="absolute inset-0 hidden items-center justify-center gap-4 group-hover:flex">
                        <ImageCropper :aspectRatio="1 / 1" imageType="profile_photo" @imageCropped="handleImageCropped"
                            class="group/item">
                            <template #trigger="{ triggerFileInput }">
                                <PrimaryButton type="button" @click="triggerFileInput">
                                    <i class="pi pi-pencil text-lg text-[--text-color]"></i>
                                </PrimaryButton>
                            </template>
                        </ImageCropper>
                    </div>
                </div>
                <InputText v-if="isEditing" v-model="name" @blur="updateName" class="!bg-[--surface-50] !text-[--text-color] font-semibold" />
                <div v-else class="flex justify-between items-center">
                    <h1 class="font-bold text-lg text-[--text-color] user-name" :data-user-id="page.props.auth.user.id">{{ name }}</h1>
                    <i class="pi pi-pencil text-lg text-[--text-color] hover:cursor-pointer" @click="isEditing = !isEditing"></i>
                </div>
            </div>
        </Popover>
    </div>
</template>