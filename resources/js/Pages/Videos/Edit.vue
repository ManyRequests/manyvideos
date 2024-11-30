<script setup>
import { ref, reactive, onMounted } from 'vue';
import { usePage, useForm, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import VideoTagsSelect from '@/Components/VideoTagsSelect.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const page = usePage();
const file = ref(null);

const video = useForm({
    _method: 'PUT',
    title: page.props.video.title,
    description: page.props.video.description,
    tags: [],
    file: null,
});

const currentTags = ref([]);

const submit = async () => {
    video.file = file.value.files[0];

    video.post(route('videos.update', page.props.video.id), {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });
}

onMounted(() => {
    if (page.props.video.tags) {
        video.tags = page.props.video.tags.map(tag => tag.name);
        currentTags.value = page.props.video.tags.map(tag => tag.name);
    }
});
</script>

<template>
    <AppLayout title="Edit Video">
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Edit Video
            </h2>
        </template>

        <div class="mx-auto max-w-5xl w-full py-6 px-2 sm:px-4 lg:px-0">
            <form @submit.prevent="submit">
                <div class="flex flex-col gap-4">
                    <div>
                        <InputLabel for="title" value="Title" />
                        <TextInput v-model="video.title" id="title" class="w-full"/>
                        <InputError v-if="video.errors.title" :message="video.errors.title" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Description" />
                        <InputError v-if="video.errors.description" :message="video.errors.description" />
                        <TextAreaInput v-model="video.description" id="description" class="w-full"/>
                    </div>

                    <div>
                        <InputError v-if="video.errors.tags" :message="video.errors.tags" />
                        <VideoTagsSelect
                            v-model="video.tags"
                            :tags="currentTags"
                        />
                    </div>

                    <div>
                        <InputLabel value="Thumbnail" />
                        <Link :href="route('videos.show', {
                            video: page.props.video.id
                        })">
                            <img
                                :src="`/storage/${page.props.video.thumbnail}`"
                                alt="thumbnail"
                                class="max-w-xl w-full rounded-xl border shadow"
                            >
                        </Link>
                    </div>

                    <div>
                        <InputLabel value="Video" />
                        <input
                            id="video"
                            ref="file"
                            type="file"
                            name="video"
                            accept="video/*"
                            class="form-input mt-1 block w-full rounded-lg bg-gray-700 text-white"
                        />
                        <InputError v-if="video.errors.file" :message="video.errors.file" />
                    </div>

                    <div>
                        <PrimaryButton type="submit">
                            Update Video
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<style lang="scss" scoped>

</style>
