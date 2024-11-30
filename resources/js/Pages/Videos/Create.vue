<script setup>
import { ref, reactive } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import VideoTagsSelect from '@/Components/VideoTagsSelect.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';

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
                    </div>

                    <div class="block">
                        <InputLabel :value="'Description'" />
                        <TextAreaInput v-model="form.description" name="description" id="description" class="mt-1 block w-full" />
                    </div>

                    <div class="block">
                        <InputLabel :value="'Tags'" />
                        <VideoTagsSelect v-model="form.tags" :tags="page.props.tags"/>
                    </div>

                    <div class="block">
                        <InputLabel :value="'Video'" />
                        <input
                            id="video"
                            ref="file"
                            type="file"
                            name="video"
                            accept="video/*"
                            class="form-input mt-1 block w-full rounded-lg bg-gray-700 text-white"
                        />
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
