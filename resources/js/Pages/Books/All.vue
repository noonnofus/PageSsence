<script setup>
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import Dialog from "primevue/dialog";

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    books: Array,
});

const todaysBook = props.books.length > 0 ? props.books[0] : null;

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

const saveBook = () => {
    // need to make a logic to store bookId to save.
    console.log("Saved book:", selectedBook.value);
};
</script>

<template>
    <Head title="Books" />
    <DefaultLayout :canLogin="canLogin" :canRegister="canRegister">
        <Carousel
            :value="props.books"
            :numVisible="4"
            :numScroll="1"
            :circular="true"
            :autoplayInterval="5000"
            class="px-6"
        >
            <template #item="slotProps">
                <Card
                    @click="openBookModal(slotProps.data.id)"
                    class="cursor-pointer h-[400px] w-full mx-2 shadow-md border border-gray-200 rounded-lg flex flex-col justify-between hover:shadow-lg transition"
                >
                    <template #title>
                        <h3 class="font-semibold text-lg truncate">
                            {{ slotProps.data.title }}
                        </h3>
                    </template>

                    <template #content>
                        <img
                            :src="
                                slotProps.data.cover_image_url ||
                                '/default-cover.jpeg'
                            "
                            alt="Book Cover"
                            class="w-full h-60 object-cover rounded-md"
                        />
                        <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                            by
                            <span class="underline">{{
                                slotProps.data.author
                            }}</span>
                        </p>
                    </template>
                </Card>
            </template>
        </Carousel>

        <div v-if="todaysBook" class="mt-16 px-6">
            <h2 class="text-4xl font-bold mb-6 text-center">Today's Book</h2>
            <div class="flex justify-center">
                <Card
                    @click="openBookModal(todaysBook.id)"
                    class="max-w-md mx-auto shadow-lg border border-gray-300 rounded-md overflow-hidden"
                >
                    <template #header>
                        <img
                            :src="
                                todaysBook.cover_image_url ||
                                '/default-cover.jpeg'
                            "
                            alt="Book Cover"
                            class="w-full h-72 object-cover"
                        />
                    </template>
                    <template #title>
                        <h3 class="text-xl pl-3 font-semibold text-gray-800">
                            {{ todaysBook.title }}
                        </h3>
                    </template>
                    <template #subtitle>
                        <p class="text-sm pl-3 text-gray-500">
                            by {{ todaysBook.author }}
                        </p>
                    </template>
                    <template #content>
                        <p class="text-sm pl-3 text-gray-700 leading-relaxed">
                            {{
                                todaysBook.description ||
                                "No description available."
                            }}
                        </p>
                    </template>
                    <template #footer>
                        <div class="flex justify-end gap-2 mt-2 mb-2 pr-2">
                            <Button
                                label="Details"
                                icon="pi pi-search"
                                outlined
                                class="px-3 py-1 text-sm"
                            />
                            <Button
                                label="Save"
                                icon="pi pi-bookmark"
                                class="px-3 py-1 text-sm"
                            />
                        </div>
                    </template>
                </Card>
            </div>
        </div>
        <Dialog
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
                v-if="selectedBook"
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
    </DefaultLayout>
</template>
