<script setup>
import { computed } from 'vue';
import DropdownLink from "@/Components/DropdownLink.vue";

const props = defineProps({
    notification: Object,
})

const types = {
    'App\\Notifications\\VideoProcessingCompleted': () => {
        return {
            title: 'Your video has been processed!',
            href: route('videos.index'),
        }
    },
};

const notificationData = computed(() => {
    return types[props.notification.type]();
});
</script>

<template>
    <div>
        <DropdownLink
            :href="notificationData.href"
        >
            <div class="block text-xs">
                {{ notificationData.title }}
            </div>
        </DropdownLink>
        <div class="border-t border-gray-200" />
    </div>
</template>

<style lang="scss" scoped>

</style>
