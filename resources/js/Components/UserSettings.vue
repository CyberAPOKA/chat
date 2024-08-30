<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Popover from 'primevue/popover';
const page = usePage();

const op = ref();

const toggle = (event) => {
    op.value.toggle(event);
}
</script>
<template>
    <div>
        <button @click="toggle">
            <!-- Foto do usuário logado -->
            <img :src="page.props.auth.user.profile_photo_url" alt="" class="size-16 rounded-full border-[3px] border-dashed border-[--surface-900]">
        </button>
        <Popover ref="op" >
            <div class="flex flex-col gap-4 w-[25rem]" v-motion-slide-visible-once-bottom>
                <img :src="page.props.auth.user.profile_photo_url" alt="" class="size-28 rounded-full">
            </div>
        </Popover>
    </div>
</template>