<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-[--surface-200] text-[--text-color] border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest">
        <slot />
    </button>
</template>
