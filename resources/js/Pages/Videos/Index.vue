<script setup>
import { usePage } from '@inertiajs/vue3';

import AppLayout from '@/Layouts/AppLayout.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import FormSection from '@/Components/FormSection.vue';

const page = usePage();

</script>

<template>
    <AppLayout>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
           <tr v-for="video in page.props.videos" :key="video.id">
                <td>{{ video.title }}</td>
                <td>
                    <div class="flex">
                        <ResponsiveNavLink :href="route('videos.edit', video.id)" as="a">
                            Edit
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('videos.destroy', video.id)" as="button">
                            Delete
                        </ResponsiveNavLink>
                    </div>
                </td>
           </tr>
        </table>
    </AppLayout>
</template>

<style lang="scss" scoped>

</style>
