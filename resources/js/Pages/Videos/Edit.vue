<script setup>
import { ref, reactive } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
const file = ref(null);

const video = reactive({
    title: page.props.video.title,
    description: page.props.video.description,
});

const submit = async () => {
    const data = new FormData();

    data.append('_method', 'PUT');
    data.append('title', video.title);
    data.append('description', video.description);

    if (file.value.files[0]) {
        data.append('file', file.value.files[0]);
    }

    router.post(route('videos.update', page.props.video.id), data, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
}
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Video
            </h2>
        </template>

        <div class="flex flex-col mx-auto max-w-7xl w-max gap-4 my-4">
            <form @submit.prevent="submit">
                <div class="flex flex-col gap-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            Title
                        </label>
                        <input
                            v-model="video.title"
                            type="text"
                            id="title"
                            name="title"
                            class="mt-1 block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"
                        >
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea
                            v-model="video.description"
                            id="description"
                            name="description"
                            class="mt-1 block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"
                        >
                        </textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Actual Video
                        </label>
                        <img
                            :src="`/storage/${page.props.video.thumbnail}`"
                            alt="thumbnail"
                            class="max-w-xl w-full rounded-xl border shadow"
                        >
                    </div>

                    <div>
                        <label for="video" class="block text-sm font-medium text-gray-700">
                            Video
                        </label>
                        <input type="file" id="video" ref="file" name="video" class="mt-1 block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                    </div>

                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<style lang="scss" scoped>

</style>
