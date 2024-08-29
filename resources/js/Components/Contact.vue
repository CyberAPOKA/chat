<script setup>
import InputMask from 'primevue/inputmask';
const emit = defineEmits('back');
</script>

<template>
    <div>
        <button @click="emit('back')">
            <i class="pi pi-arrow-left"></i>
        </button>
    </div>
    <div v-motion-slide-left>
        Número
        <InputMask v-model="test" mask="+99 99 9 9999-9999" placeholder="+99 99 9 9999-9999" />
    </div>
</template>