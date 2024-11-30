<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { timeToHuman } from '@/utils/toHumanFormats';
import UserProfilePicture from '@/Components/UserProfilePicture.vue';
import VideoTag from '@/Components/VideoTag.vue';

const props = defineProps({
    video: {
        type: Object,
        required: true,
    },
});

const thumbnailUrl = computed(() => {
    return `/storage/${props.video.thumbnail}`;
});

const duration = computed(() => {
    return timeToHuman(props.video.duration);
});

const createdAt = computed(() => {
    const date = new Date(props.video.created_at);
    return date.toLocaleDateString();
});

const commentsCount = computed(() => {
    if (!props.video.comments_count) {
        return 0;
    }
    return props.video.comments_count;
});

const linkRoute = computed(() => {
    if (props.video.status === 'failed') {
        return route('videos.index');
    }
    return route('videos.show', props.video.id);
});
</script>

<template>
    <Link :href="linkRoute" class="group">
        <div class="relative overflow-hidden">
            <div
                v-if="props.video.status === 'processing'"
                class="w-full aspect-video bg-slate-600 rounded-xl overflow-hidden"
            >
                <div class="w-full aspect-video bg-gradient-to-r from-transparent via-rose-100/20 to-transparent -translate-x-full animate-[shimmer_2s_infinite]" />
            </div>
            <div
                v-else-if="props.video.status === 'failed'"
                class="w-full aspect-video bg-slate-600 rounded-xl overflow-hidden"
            />
            <img
                v-else-if="props.video.status === 'processed'"
                class="w-full aspect-video object-cover rounded-xl"
                :src="thumbnailUrl"
                :alt="video.title"
            >


            <div class="absolute bottom-2 right-2 text-xs bg-black bg-opacity-70 text-white px-2 py-1 rounded-xl">
                <span>{{ duration }}</span>
            </div>
            <slot name="overlay"></slot>
        </div>
        <div class="flex flex-row py-3 gap-3">
            <UserProfilePicture :user="video.user" />
            <div class="w-full">
                <div class="flex flex-row items-center gap-3">
                    <span class="text-white font-bold">{{ video.title }}</span>
                </div>
                <div >
                    <span class="text-gray-400 text-sm capitalize">{{ video.user.name }}</span>
                </div>
                <div>
                    <span class="text-gray-400 text-sm">{{ createdAt }}</span>
                    •
                    <span class="text-gray-400 text-sm">{{ commentsCount }} comments</span>
                </div>
                <div v-if="video.tags" class="flex flex-row gap-2 mt-1">
                    <VideoTag v-for="tag in video.tags" :key="tag.id" :tag="tag" />
                </div>

                <slot name="footer"></slot>
            </div>
        </div>
    </Link>
</template>

<style lang="scss" scoped>

</style>
