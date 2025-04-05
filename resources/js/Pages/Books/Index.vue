<!-- For API -->

<script setup>
import { defineProps } from "vue";

defineProps({
    books: Array,
    searchQuery: String,
});
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">
            Search Results for "{{ searchQuery }}"
        </h1>
        <ul v-if="books.length">
            <li v-for="book in books" :key="book.id" class="mb-2">
                📖 {{ book.title }}
            </li>
        </ul>
        <p v-else class="text-gray-500">No books found.</p>
    </div>
</template>
