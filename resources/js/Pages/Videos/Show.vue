<script setup>
import { computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { sizeToHuman, timeToHuman } from '@/utils/toHumanFormats';
import AppLayout from '@/Layouts/AppLayout.vue';
import VideoTag from '@/Components/VideoTag.vue';

const page = usePage()


const url = computed(() => {
    return `/storage/${page.props.video.url}`;
});

const size = computed(() => {
    return sizeToHuman(page.props.video.size);
});

const time = computed(() => {
    return timeToHuman(page.props.video.duration);
});
</script>

<template>
    <AppLayout>

        <div class="py-4">
            <video id="video" controls class="rounded-md w-full">
                <source :src="url" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <h1 class="text-xl font-semibold mt-3 mb-3">{{ page.props.video.title }}</h1>
            <div class="flex items-center">
                <div class="h-12 w-12 bg-gray-600 rounded-full"></div>
                <div class="capitalize">
                    <span class="font-semibold ml-2">{{ page.props.video.user.name }}</span>
                </div>
            </div>
            <div class="py-4 px-4 bg-slate-800 text-white rounded-md mt-4">
                <div class="flex flex-row gap-4">
                    <span class="font-semibold">{{ page.props.video.created_at }}</span>

                    <div class="inline-flex bg-gray-600 font-semibold px-2 rounded-full gap-3">
                        <span>{{ page.props.video.width }} x {{ page.props.video.height }}</span>
                        <span>{{ size }}</span>
                        <span>{{ time }}</span>
                    </div>
                </div>
                <p class="font-medium mt-4">{{ page.props.video.description }}</p>

                <div class="flex flex-row flex-wrap gap-2 mt-4">
                    <VideoTag v-for="tag in page.props.video.tags" :key="tag.id" :tag="tag" />
                </div>

            </div>
        </div>
    </AppLayout>
</template>

<style lang="scss" scoped>

</style>
