<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import VideoTag from '@/Components/VideoTag.vue';
import VideoItem from '@/Components/VideoItem.vue';

const page = usePage();

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

const videoParsed = computed(() => {
    return {
        ...props.video,
        user: page.props.auth.user,
    };
})
</script>

<template>
    <div class="border border-gray-800 rounded-lg overflow-hidden">
        <div class="px-4 py-2">
            <div v-if="video.status === 'processing'">
                <p>Procesando</p>
            </div>
            <VideoItem v-else-if="video.status === 'processed'" :video="videoParsed" @click="play">
                <template #overlay>
                    <div class="absolute top-2 right-0 flex flex-row px-4 py-2 items-center">
                        <div class="ml-auto">
                            <Link :href="route('videos.edit', video.id)">
                                <SecondaryButton>
                                    <svg  xmlns="http://www.w3.org/2000/svg" class="size-4"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                </SecondaryButton>
                            </Link>
                            <DangerButton @click="remove" class="ml-1">
                                <svg  xmlns="http://www.w3.org/2000/svg" class="size-4"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                            </DangerButton>
                        </div>
                    </div>
                </template>
            </VideoItem>
        </div>
    </div>
</template>

<style lang="scss" scoped>

</style>
