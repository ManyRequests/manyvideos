<script setup>

const props = defineProps({
    comment: Object,
});

</script>

<template>
    <div class="flex flex-row items-center gap-3">
        <img :src="comment.user.profile_photo_url" class="h-12 w-12 rounded-full border border-gray-300">
        <div>
            <div class="capitalize">
                <span class="font-semibold mr-2 text-base">{{ comment.user.name }}</span>
                <span class="text-xs font-medium text-gray-700">{{ comment.created_at }}</span>
            </div>
            <div>
                <p class="text-base">{{ comment.content }}</p>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>

</style>
