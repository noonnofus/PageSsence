<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import { ref } from "vue";

const props = defineProps({
    savedBooks: Array,
    username: String,
});

const showModal = ref(false);
const selectedBook = ref(null);

const openBookModal = async (bookId) => {
    try {
        const response = await fetch(`/api/book/${bookId}`);
        const data = await response.json();
        selectedBook.value = data;
        showModal.value = true;
    } catch (error) {
        console.error("Failed to fetch book:", error);
    }
};

const closeBookModal = () => {
    showModal.value = false;
    selectedBook.value = null;
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Hello, {{ username }}!</div>
                </div>

                <div class="mt-3">
                    <h3 class="text-lg font-bold mb-4">Your Saved Books</h3>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                    >
                        <Card
                            v-for="book in props.savedBooks"
                            :key="book.id"
                            @click="openBookModal(book.id)"
                            class="shadow-md border border-gray-200 rounded-lg cursor-pointer"
                        >
                            <template #title>{{ book.title }}</template>
                            <template #subtitle>{{ book.author }}</template>
                            <template #content>
                                <p class="text-sm text-gray-600">
                                    Genre: {{ book.genre }}<br />
                                    Year: {{ book.publication_year }}<br />
                                    Price: ${{ book.price }}
                                </p>
                            </template>
                        </Card>
                    </div>
                </div>
            </div>
        </div>

        <Dialog
            v-if="selectedBook"
            v-model:visible="showModal"
            modal
            :dismissableMask="true"
            :closable="false"
            :draggable="false"
            position="center"
            class="w-full max-w-6xl"
            @hide="closeBookModal"
        >
            <div
                class="relative flex flex-col md:flex-row gap-6 p-6 bg-white rounded shadow-lg"
            >
                <button
                    @click="closeBookModal"
                    class="absolute top-4 right-4 text-gray-500 hover:text-black text-xl"
                >
                    &times;
                </button>

                <div class="w-full md:w-1/2 flex flex-col gap-4">
                    <h2 class="text-3xl font-bold">{{ selectedBook.title }}</h2>
                    <p class="text-md text-gray-600">
                        by {{ selectedBook.author }}
                    </p>
                    <img
                        :src="
                            selectedBook.cover_image_url ||
                            '/default-cover.jpeg'
                        "
                        alt="Book Cover"
                        class="w-full max-h-[600px] object-contain rounded"
                    />
                </div>

                <div
                    class="w-full md:w-1/2 flex flex-col gap-4 text-base text-gray-700"
                >
                    <div>
                        <span class="font-semibold text-gray-800">Genre:</span>
                        <span class="ml-2">{{
                            selectedBook.genre || "Unknown"
                        }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-800"
                            >Publication Year:</span
                        >
                        <span class="ml-2">{{
                            selectedBook.publication_year || "N/A"
                        }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-800">Price:</span>
                        <span class="ml-2"
                            >${{ selectedBook.price || "0.00" }}</span
                        >
                    </div>
                    <div>
                        <span class="font-semibold text-gray-800"
                            >Description:</span
                        >
                        <p
                            class="mt-1 text-gray-600 leading-relaxed whitespace-pre-line"
                        >
                            {{
                                selectedBook.description ||
                                "No description available."
                            }}
                        </p>
                    </div>

                    <div class="mt-4">
                        <Button
                            label="Save"
                            icon="pi pi-bookmark"
                            class="w-full md:w-auto px-4 py-2 text-sm"
                            severity="primary"
                            @click="saveBook"
                        />
                    </div>
                </div>
            </div>
        </Dialog>
    </AuthenticatedLayout>
</template>
