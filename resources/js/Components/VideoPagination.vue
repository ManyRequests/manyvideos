<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    videos: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div>
        <div
            class="flex items-center justify-between py-3 sm:px-6"
        >
            <div class="flex flex-1 justify-between sm:hidden">
                <Link
                    :href="videos.prev_page_url || '#'"
                    class="relative inline-flex items-center rounded-md bg-indigo-900 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >Previous</Link
                >
                <Link
                    :href="videos.next_page_url || '#'"
                    class="relative ml-3 inline-flex items-center rounded-md  bg-indigo-900 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >Next</Link
                >
            </div>
            <div
                class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between"
            >
                <div>
                    <p class="text-sm text-white">
                        Showing
                        <span class="font-medium">{{ videos.from }}</span>
                        to
                        <span class="font-medium">{{ videos.to }}</span>
                        of
                        <span class="font-medium">{{ videos.total }}</span>
                        results
                    </p>
                </div>
                <div>
                    <nav
                        class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                        aria-label="Pagination"
                    >
                        <a
                            :href="videos.prev_page_url || '#'"
                            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-700 focus:z-20 focus:outline-offset-0"
                            :class="{
                                'hover:bg-indigo-400 hover:text-white': videos.prev_page_url,
                                'hover:bg-transparent hover:text-gray-400': !videos.prev_page_url,
                            }"
                            :disabled="!videos.next_page_url"
                        >
                            <span class="sr-only">Previous</span>
                            <svg
                                class="size-5"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                                data-slot="icon"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </a>
                        <a
                            v-for="page in videos.last_page"
                            :href="route('home', { page: page })"
                            aria-current="page"
                            :class="{
                                'relative z-10 inline-flex items-center bg-indigo-500 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600':
                                    page === videos.current_page,
                                'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-white ring-1 ring-inset ring-gray-700 hover:bg-indigo-400 focus:z-20 focus:outline-offset-0':
                                    page !== videos.current_page,
                            }"
                        >
                            {{ page }}
                        </a>
                        <a
                            :href="videos.next_page_url || '#'"
                            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-700  focus:z-20 focus:outline-offset-0"
                            :class="{
                                'hover:bg-indigo-400 hover:text-white': videos.next_page_url,
                                'hover:bg-transparent hover:text-gray-400': !videos.next_page_url,
                            }"
                            :disabled="!videos.next_page_url"
                        >
                            <span class="sr-only">Next</span>
                            <svg
                                class="size-5"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                                data-slot="icon"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped></style>
