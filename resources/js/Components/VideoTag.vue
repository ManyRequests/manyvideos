<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    tag: Object,
});

const bgColorToTailwindRGBA = computed(() => {
    const hex = props.tag.color;
    const hexToRGB = (hex) => {
        hex = hex.replace('#', '');
        const bigint = parseInt(hex, 16);
        const r = (bigint >> 16) & 255;
        const g = (bigint >> 8) & 255;
        const b = bigint & 255;
        return `${r}, ${g}, ${b}`;
    };
    return `rgba(${hexToRGB(hex)}, 0.7)`;
})
</script>

<template>
    <span class="rounded-md py-0 px-3 border-2 bg-opacity-70 font-semibold" :style="{
        borderColor: tag.color,
        backgroundColor: bgColorToTailwindRGBA,
    }">
        {{ tag.name }}
    </span>
</template>

<style lang="scss" scoped>

</style>
