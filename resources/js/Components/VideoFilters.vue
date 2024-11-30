<script setup>
import { ref, reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import Input from '@/Components/Input.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const form = reactive({
    search: '',
    size_min: null,
    size_max: null,
    duration_min: null,
    duration_max: null,
});

const clearFilters = () => {
    Object.keys(form).forEach((key) => {
        form[key] = null;
    });

    applyFilters();
};

const applyFilters = () => {
    const data = Object.fromEntries(
        Object.entries(form).filter(([key, value]) => value !== null)
    );

    router.get(route('home'), {
        ...data,
    }, {
        preserveState: true,
    });
};
</script>

<template>
    <div class="mx-auto">
        <!-- search -->
         <div class="flex flew-row items-center mb-4">
            <TextInput
                v-model="form.search"
                label="Search"
                placeholder="Search videos..."
                class="inline w-96"
                @keydown.enter="applyFilters"
            />
            <div
                class="inline p-2.5 rounded-full bg-gray-800 hover:bg-gray-700 ml-4 transition-colors"
                @click="applyFilters"
            >
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
            </div>
            <div
                v-if="form.search"
                class="inline p-2.5 rounded-full bg-gray-800 hover:bg-gray-700 ml-2 transition-colors"
            >
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
            </div>
         </div>
        <!-- size -->
         <div>
            <h5 class="font-bold text-white flex flex-row gap-1 mb-2">
                <span>
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-adjustments-horizontal"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 6m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M4 6l8 0" /><path d="M16 6l4 0" /><path d="M8 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M4 12l2 0" /><path d="M10 12l10 0" /><path d="M17 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M4 18l11 0" /><path d="M19 18l1 0" /></svg>
                </span>
                <span>
                    Filters
                </span>
            </h5>
            <div class="flex flex-row gap-5">
                <div>
                    <Input
                        v-model="form.size_min"
                        type="number" label="Min Size"
                        placeholder="Min size..."
                        class="inline w-28 mr-2"
                        min="0"
                        :max="form.size_max"
                    />
                    <Input
                        v-model="form.size_max"
                        type="number"
                        label="Max Size"
                        placeholder="Max size..."
                        class="inline w-28"
                        :min="form.size_min"
                    />
                </div>
                <div>
                    <Input
                        v-model="form.duration_min"
                        type="number"
                        label="Min Duration"
                        placeholder="Min duration..."
                        class="inline w-28 mr-2"
                        min="0"
                        :max="form.duration_max"
                    />
                    <Input
                        v-model="form.duration_max"
                        type="number"
                        label="Max Duration"
                        :min="form.duration_min"
                        placeholder="Max duration..."
                        class="inline w-28"
                    />
                </div>
                <div class="flex items-center">
                    <PrimaryButton @click="applyFilters">Apply</PrimaryButton>
                    <SecondaryButton @click="clearFilters" class="ml-2">Clear</SecondaryButton>
                </div>
            </div>
         </div>
    </div>
</template>

<style lang="scss" scoped>

</style>
