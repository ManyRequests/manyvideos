<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    video: Object,
});

const playing = ref(false);

const play = () => {
    playing.value = true;
    const video = document.getElementById('video');
    video.play();
};
</script>

<template>
    <div class="border border-gray-800 rounded-lg overflow-hidden">
        <div class="px-4 py-2">
            <h3 class="font-semibold">{{ video.title }}</h3>
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
