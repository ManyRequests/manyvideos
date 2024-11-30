<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label class="block font-semibold text-white">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
