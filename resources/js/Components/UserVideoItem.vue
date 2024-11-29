<script setup>
import { ref } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import SecondaryButton from './SecondaryButton.vue';
import DangerButton from './DangerButton.vue';
import VideoTag from './VideoTag.vue';

const props = defineProps({
    video: Object,
});

const playing = ref(false);

const play = () => {
    playing.value = true;
    const video = document.getElementById('video');
    video.play();
};

const remove = async () => {
    if (confirm('Are you sure you want to delete this video?')) {
        router.delete(route('videos.destroy', {
            video: props.video.id,
        }), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <div class="border border-gray-800 rounded-lg overflow-hidden">
        <div class="flex flex-row px-4 py-2 items-center">
            <h3 class="font-semibold">{{ video.title }}</h3>
            <div class="ml-auto">
                <Link :href="route('videos.edit', video.id)">
                    <SecondaryButton>
                        Edit
                    </SecondaryButton>
                </Link>
                <DangerButton @click="remove" class="ml-1">
                    Delete
                </DangerButton>
            </div>
        </div>
        <div class="px-4 py-2 bg-gray-200">
            <div v-if="video.status === 'processing'">
                <p>Procesando</p>
            </div>
            <div v-else-if="video.status === 'processed'" class="flex flex-col">
                <div>
                    <p>{{ video.description }}</p>
                </div>
                <div class="flex gap-4">
                    <span>Dimensions: {{ video.width }} x {{video.height }}</span>
                    <span>Size: {{ video.size }}</span>
                    <span>Duration: {{ video.duration }}s</span>
                    <div>
                        <span>Tags:</span>
                        <div class="flex gap-2">
                            <VideoTag v-for="tag in video.tags" :key="tag.id" :tag="tag"/>
                        </div>
                    </div>
                </div>
                <div v-if="!playing">
                    <img :src="`/storage/${video.thumbnail}`" alt="thumbnail" class="rounded-xl" @click="play"/>
                </div>
                <div v-else-if="playing">
                    <video :src="`/storage/${video.url}`" controls class="rounded-xl"></video>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>

</style>
