<script setup>
import { defineModel } from 'vue';

const props = defineProps({
    label: {
        type: String,
        default: 'Range'
    },
    min: {
        type: [Number],
        default: 0
    },
    max: {
        type: Number,
        default: 100
    },
})

const modelValue = defineModel();
</script>

<template>
    <div>
        <label for="default-range" class="block mb-2 text-sm font-medium text-white">
            <slot name="label">{{ label }}</slot>
        </label>
        <input
            v-model="modelValue"
            id="default-range"
            type="range"
            class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gray-700"
            :min="min"
            :max="max"
        >
    </div>
</template>

<style lang="scss" scoped>

</style>
