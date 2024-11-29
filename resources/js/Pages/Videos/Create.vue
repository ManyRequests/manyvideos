<script setup>
import { ref, reactive } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import VideoTagsSelect from '@/Components/VideoTagsSelect.vue';

const page = usePage();

const form = reactive({
    title: '',
    description: '',
    tags: [],
});

const file = ref(null);

function submit() {
    console.log(route('videos.store'));
    router.post(route('videos.store'), {
        ...form,
        file: file.value.files[0],
    }, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
}
</script>

<template>
    <AppLayout title="Create Video">
        <template #header>
            <div class="flex flex-row items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Create Video
                </h2>
                <div>
                    <Link :href="route('videos.index')">
                        <PrimaryButton>
                            Back
                        </PrimaryButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-5xl w-full py-6">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-6">
                    <div class="block">
                        <label for="title" class="text-gray-700">Title</label>
                        <input v-model="form.title" type="text" name="title" id="title" class="form-input mt-1 block w-full" />
                    </div>

                    <div class="block">
                        <label for="description" class="text-gray-700">Description</label>
                        <textarea v-model="form.description" name="description" id="description" class="form-textarea mt-1 block w-full"></textarea>
                    </div>

                    <div class="block">
                        <VideoTagsSelect v-model="form.tags" :tags="page.props.tags"/>
                    </div>

                    <div class="block">
                        <label for="video" class="text-gray-700">Video</label>
                        <input ref="file" type="file" name="video" id="video" class="form-input mt-1 block w-full" />
                    </div>

                    <div class="block">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<style lang="scss" scoped>

</style>
