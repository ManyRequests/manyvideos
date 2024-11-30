<script setup>
import VideoItem from '@/Components/VideoItem.vue';
import VideoFilters from '@/Components/VideoFilters.vue';
import VideoPagination from '@/Components/VideoPagination.vue';

const props = defineProps({
    videos: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div>
        <VideoFilters />

        <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-3">
            <VideoItem v-for="video in videos.data" :key="video.id" :video="video" />
        </div>

        <VideoPagination :videos="videos" />
    </div>
</template>

<style lang="scss" scoped>

</style>
