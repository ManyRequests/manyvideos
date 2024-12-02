<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-full shadow-sm bg-slate-800 text-white placeholder:text-gray-400"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
