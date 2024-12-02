<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import PublicVideos from '@/Components/PublicVideos.vue';

const page = usePage();

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Home" />
    <div class="bg-slate-800">
        <div class="relative min-h-screen flex flex-col items-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative size-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="flex items-center gap-2 py-10">
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 items-center">
                        <Link
                            :href="route('home')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            <ApplicationMark class="text-4xl mr-auto"></ApplicationMark>
                        </Link>


                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('videos.index')"
                            class="rounded-md px-3 py-2 ml-auto text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            My Videos
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md ml-auto px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="flex-1 mt-6 pb-10">
                    <PublicVideos :videos="page.props.videos"></PublicVideos>
                </main>

            </div>
        </div>
        <Footer></Footer>
    </div>
</template>
