<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import ApplicationLogo from './ApplicationLogo.vue';

const page = usePage()
</script>

<template>
    <footer class=" bg-slate-900 flex flex-wrap">
        <div class="py-8 px-8 w-full flex">
            <ApplicationLogo class="h-10" />
            <div class="ml-auto my-auto pr-10 text-gray-500 font-medium">
                Made by <a href="https://budare.dev" target="_blank" class="text-indigo-400">Pedro García - Budaredev</a>
            </div>
        </div>
    </footer>
</template>

<style lang="scss" scoped>

</style>
