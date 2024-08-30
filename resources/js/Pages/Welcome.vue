<script setup>
import ConfigBar from '@/Layouts/ConfigBar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Chat from '@/Components/Chat.vue';

const props = defineProps({
    conversations: Array
});
</script>

<template>
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <div class="w-full xl:w-fit flex">
            <ConfigBar />
            <Sidebar :conversations="conversations" />
        </div>

        <!-- Chat principal -->
        <Chat />

    </div>
</template>
