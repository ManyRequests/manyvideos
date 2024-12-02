<script setup>
import { ref, reactive } from 'vue';
import { router, Link, usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import VideoTagsSelect from '@/Components/VideoTagsSelect.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import InputError from '@/Components/InputError.vue';

const page = usePage();
const file = ref(null);
const allowedMimeTypes = page.props.config.videos.mime_types;
const allowedExtensions = page.props.config.videos.extensions.join(', ');
const maxFileSize = page.props.config.videos.max_file_size / 1024 / 1024;

const form = useForm({
    title: '',
    description: '',
    tags: [],
    file: null,
});

function submit() {
    form.file = file.value.files[0];

    form.post(route('videos.store'), {
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
                <h2 class="font-semibold text-xl text-white leading-tight">
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

        <div class="mx-auto max-w-5xl w-full py-6 px-2 sm:px-4 lg:px-0">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-6">
                    <div class="block">
                        <InputLabel :value="'Title'" />
                        <TextInput v-model="form.title"name="title" id="title" class="mt-1 block w-full" />
                        <InputError v-if="form.errors.title" :message="form.errors.title" />
                    </div>

                    <div class="block">
                        <InputLabel :value="'Description'" />
                        <TextAreaInput v-model="form.description" name="description" id="description" class="mt-1 block w-full" />
                        <InputError v-if="form.errors.description" :message="form.errors.description" />
                    </div>

                    <div class="block">
                        <InputLabel :value="'Tags'" />
                        <InputError v-if="form.errors.tags" :message="form.errors.tags" />
                        <VideoTagsSelect v-model="form.tags" :tags="page.props.tags"/>
                    </div>

                    <div class="block">
                        <InputLabel :value="'Video'" />
                        <small class="text-gray-400">Max file size: {{ maxFileSize }}MB</small>
                        <small class="text-gray-400 ml-1">Allowed formats: {{ allowedExtensions }}</small>
                        <input
                            id="video"
                            ref="file"
                            type="file"
                            name="video"
                            :accept="allowedMimeTypes"
                            class="form-input mt-1 block w-full rounded-lg bg-gray-700 text-white"
                        />
                        <InputError v-if="form.errors.file" :message="form.errors.file" />
                    </div>

                    <div class="block">
                        <PrimaryButton type="submit">
                            Create Video
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<style lang="scss" scoped>

</style>
