<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';

import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import UserVideoItem from '@/Components/UserVideoItem.vue';
import { onMounted } from 'vue';

const page = usePage();

onMounted(() => {
    window.Echo.private(`App.Models.User.${page.props.auth.user.id}`)
        .notification((notification) => {
            if (notification.type === 'App\\Notifications\\VideoProcessingCompleted') {
                // Refresh the page
                router.reload();
            }
        });
});
</script>

<template>
    <AppLayout title="My Videos">
        <template #header>
            <div class="flex flex-row items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    My Videos
                </h2>

                <Link :href="route('videos.create')">
                    <PrimaryButton>
                        Create Video
                    </PrimaryButton>
                </Link>
            </div>
        </template>
        <div class="flex flex-col mx-auto max-w-7xl w-max gap-4 my-4">
            <UserVideoItem v-for="video in page.props.videos" :key="video.id" :video="video" />
        </div>
    </AppLayout>
</template>

<style lang="scss" scoped>

</style>
