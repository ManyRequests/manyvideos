<script setup>
import { computed } from 'vue';

import VideoCommentsCreate from '@/Components/VideoCommentsCreate.vue';
import VideoCommentsItem from '@/Components/VideoCommentsItem.vue';

const props = defineProps({
    video: Object,
});

const commentsCount = computed(() => {
    if (!props.video.comments) {
        return 0;
    }
    return props.video.comments.length;
});
</script>

<template>
    <div class="py-4">
        <h2 class="text-lg font-semibold mb-1 text-white">{{ commentsCount }} Comment{{ commentsCount === 1 ? '' : 's'  }}</h2>

        <VideoCommentsCreate :video="video" />

        <div class="flex flex-col gap-5 mt-10">
            <VideoCommentsItem v-for="comment in video.comments" :key="comment.id" :comment="comment" />
        </div>
    </div>
</template>

<style lang="scss" scoped>

</style>
