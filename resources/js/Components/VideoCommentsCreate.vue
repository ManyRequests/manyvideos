<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    video: Object,
});

const content = ref('');
const commentErrors = ref(null);
const loading = ref(false);

const submit = async () => {
    if (!content.value) {
        return;
    }

    loading.value = true;

    try {
        const url = route('videos.comments.store', {
            video: props.video.id,
        });

        await router.post(url, {
            content: content.value,
        }, {
            preserveState: true,
            preserveScroll: true,
        });

        content.value = '';
    } catch (error) {
        console.error(error);

        if (error.response && error.response.status === 422) {
            commentErrors.value = error.response.data.errors.content[0];
            return
        }

        commentErrors.value = 'Something went wrong. Please try again.';
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <div>
        <div v-if="loading" class="flex w-full py-4">
            <svg class="h-8 w-8 mx-auto text-gray-500 animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6l0 -3" /><path d="M16.25 7.75l2.15 -2.15" /><path d="M18 12l3 0" /><path d="M16.25 16.25l2.15 2.15" /><path d="M12 18l0 3" /><path d="M7.75 16.25l-2.15 2.15" /><path d="M6 12l-3 0" /><path d="M7.75 7.75l-2.15 -2.15" /></svg>
        </div>
        <template v-else>
            <TextAreaInput v-model="content" placeholder="Write a comment..." class="w-full" />
            <InputError :message="commentErrors" class="mt-2 float-left" />

            <PrimaryButton @click="submit" class="float-right">Comment</PrimaryButton>
            <SecondaryButton @click="content = ''" class="float-right mr-2">Cancel</SecondaryButton>
        </template>
    </div>
</template>

<style lang="scss" scoped>

</style>
