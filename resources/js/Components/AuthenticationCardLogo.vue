<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link :href="'/'">
        <div class="page-name">
            ManyVideos
        </div>
    </Link>
</template>

<style lang="postcss" scoped>
.page-name {
    @apply text-gray-200;

    font-family: Grand Hotel, cursive;
    font-weight: 400;
    font-size: 3rem;
    letter-spacing: 0.1rem;
}
</style>
