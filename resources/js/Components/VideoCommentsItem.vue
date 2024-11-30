<script setup>
import { computed } from 'vue';
import UserProfilePicture from '@/Components/UserProfilePicture.vue';

const props = defineProps({
    comment: Object,
});

const createdAt = computed(() => {
    return new Date(props.comment.created_at)
        .toLocaleString();
});
</script>

<template>
    <div class="flex flex-row items-center gap-3">
        <UserProfilePicture :user="comment.user" />
        <div>
            <div class="capitalize">
                <span class="font-semibold mr-2 text-base text-white">{{ comment.user.name }}</span>
                <span class="text-xs font-medium text-gray-400">{{ createdAt }}</span>
            </div>
            <div>
                <p class="text-base text-white">{{ comment.content }}</p>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>

</style>
