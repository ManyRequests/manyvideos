<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';

import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import UserVideoItem from '@/Components/UserVideoItem.vue';
import { onMounted } from 'vue';

const page = usePage();

const notificationsRequiringReload = [
    'App\\Notifications\\VideoProcessingCompleted',
    'App\\Notifications\\VideoProcessingFailedNotification',
];

onMounted(() => {
    window.Echo.private(`App.Models.User.${page.props.auth.user.id}`)
        .notification((notification) => {
            if (notificationsRequiringReload.includes(notification.type)) {
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
                <h2 class="font-semibold text-xl text-white leading-tight">
                    My Videos
                </h2>

                <Link :href="route('videos.create')">
                    <PrimaryButton>
                        Create Video
                    </PrimaryButton>
                </Link>
            </div>
        </template>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 mx-auto w-full gap-4 my-4">
            <UserVideoItem v-for="video in page.props.videos" :key="video.id" :video="video" />
        </div>
    </AppLayout>
</template>

<style lang="scss" scoped>

</style>
