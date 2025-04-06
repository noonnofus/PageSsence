<template>
    <div class="flex items-center gap-1">
        <span v-for="n in 5" :key="n" class="text-yellow-400 text-xl">
            <i v-if="n <= Math.floor(rating)" class="pi pi-star-fill"></i>
            <i v-else-if="n - rating <= 0.5" class="pi pi-star-half"></i>
            <i v-else class="pi pi-star"></i>
        </span>
        <span class="text-sm text-gray-700 font-medium">
            {{ rating }} / 5
        </span>
    </div>
</template>

<script setup>
defineProps({
    rating: Number,
});
</script>
