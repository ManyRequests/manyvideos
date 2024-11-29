<script setup>
import { onMounted, ref } from 'vue';
import SecondaryButton from './SecondaryButton.vue';
import TextInput from './TextInput.vue';

const props = defineProps({
    tags: {
        type: Array,
        default: () => [],
    },
});

const tag = ref('');

const modelValue = defineModel()

function addTag() {
    let value = tag.value.trim();
    if (!value || modelValue.value.includes(value) || value.length < 3 || value === '') {
        return;
    }
    modelValue.value.push(value);
    tag.value = '';
}

function removeTag(index) {
    modelValue.value.splice(index, 1);
}
</script>

<template>
    <div class="w-full">
        <div
            class="
                flex
                flex-wrap
                gap-4
                min-h-20
                items-center
                border
                border-gray-200
                rounded-md
                p-3
                overflow-y-auto
                overflow-x-hidden
            "
        >
            <span v-for="(tag, index) in modelValue">
                <span
                    class="
                        bg-gray-100
                        text-gray-700
                        px-2
                        py-1
                        rounded-full
                        text-sm
                        flex
                        items-center
                        gap-1
                    "
                >
                    {{ tag }}
                    <button
                        type="button"
                        @click="removeTag(index)"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </span>
            </span>

            <TextInput
                v-model="tag"
                @keydown.enter.prevent="addTag(tag)"
                type="text"
                class="form-input w-32"
                placeholder="Add tag"
            />

            <SecondaryButton
                @click="addTag(tag)"
            >
                Add

            </SecondaryButton>
        </div>
    </div>
</template>

<style lang="scss" scoped>

</style>
