<script setup>
import { ref } from 'vue';
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    aspectRatio: {
        type: Number,
        default: 1
    },
    imageType: {
        type: String,
        required: true
    }
});

const emit = defineEmits(['imageCropped']);

const imageElement = ref(null);
const cropperInstance = ref(null);
const previewImageUrl = ref(null);

const showImageCropperModal = ref(false);
const openImageCropperModal = () => {
    showImageCropperModal.value = true;
};
const closeImageCropperModal = () => {
    showImageCropperModal.value = false;
};

const fileInputRef = ref(null);

const triggerFileInput = () => {
    fileInputRef.value.click();
};

const onFileChange = (e) => {
    const files = e.target.files;
    if (files.length > 0) {
        const file = files[0];
        const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!validImageTypes.includes(file.type)) {
            console.log('imagem invalida');
            return;
        }
        openImageCropperModal();
        const reader = new FileReader();
        reader.onload = (event) => {
            imageElement.value = event.target.result;
            cropperInstance.value.replace(event.target.result);

        };
        reader.readAsDataURL(file);
    }
};


const getCroppedImage = () => {
    if (cropperInstance.value) {
        const croppedCanvas = cropperInstance.value.getCroppedCanvas();
        croppedCanvas.toBlob((blob) => {
            const url = URL.createObjectURL(blob);
            previewImageUrl.value = url;
            emit('imageCropped', { blob, imageType: props.imageType, url });
            closeImageCropperModal();
        });
    }
};

</script>

<template>
    <div>
        <slot name="trigger" :triggerFileInput="triggerFileInput"></slot>
        <input type="file" ref="fileInputRef" @change="onFileChange" class="hidden" accept="image/*">
        <Modal :show="showImageCropperModal" @close="closeImageCropperModal" :max-width="'4xl'">
            <div class="px-6 py-4">
                <h1 class="text-lg font-medium text-[--text-color]">Recorte a Imagem antes de Enviar</h1>
            </div>
            <hr>
            <div class="px-6 py-4">
                <div class="flex flex-col lg:flex-row items-center lg:items-start justify-between gap-y-4">
                    <div>
                        <h1 class="text-[--text-color] font-medium text-sm lg:text-lg">Imagem inserida</h1>
                        <vue-cropper ref="cropperInstance" :src="imageElement" :aspect-ratio="aspectRatio"
                            :view-mode="1" class="w-auto max-w-96 h-auto max-h-96" :preview="'.img-preview'">
                        </vue-cropper>
                    </div>
                    <div v-if="imageElement">
                        <h1 class="text-[--text-color] font-medium text-sm lg:text-lg">(Pré-visualização)</h1>
                        <div class="img-preview w-full h-full overflow-hidden">
                            <img :src="previewImageUrl" alt="Cropped Image"
                                style="width: 20rem !important; height: 20rem !important;">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="px-6 py-4 flex w-full justify-end gap-2">
                <PrimaryButton @click="getCroppedImage">
                    Recortar
                </PrimaryButton>
                <PrimaryButton @click="closeImageCropperModal">
                    Cancelar
                </PrimaryButton>
            </div>
        </Modal>
    </div>
</template>
