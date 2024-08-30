<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-[--surface-900] text-[--surface-0] border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest">
        <slot />
    </button>
</template>
