<template>
    <div class="flex items-center gap-1">
        <template v-for="i in 5" :key="i">
            <i
                class="pi"
                :class="[
                    'cursor-pointer text-xl transition',
                    i <= hover || i <= modelValue
                        ? 'pi-star-fill text-yellow-500'
                        : 'pi-star text-gray-400',
                ]"
                @mouseover="hover = i"
                @mouseleave="hover = 0"
                @click="$emit('update:modelValue', i)"
            />
        </template>
    </div>
</template>

<script setup>
import { ref } from "vue";
defineProps({ modelValue: Number });
const hover = ref(0);
</script>
