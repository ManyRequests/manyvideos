<script setup>
import VideoItem from '@/Components/VideoItem.vue';

const props = defineProps({
    videos: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <div>
        <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-3">
            <VideoItem v-for="video in videos.data" :key="video.id" :video="video" />
        </div>
    </div>
</template>

<style lang="scss" scoped>

</style>
