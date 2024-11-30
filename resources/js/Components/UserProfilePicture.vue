<script setup>
import { computed } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

</script>

<template>
    <img :src="user.profile_photo_url" class="h-12 w-12 rounded-full border border-gray-300">
</template>

<style lang="scss" scoped>

</style>
