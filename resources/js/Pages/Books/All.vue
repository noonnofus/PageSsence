<script setup>
import { ref, computed } from "vue";
import { Head } from "@inertiajs/vue3";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import Dialog from "primevue/dialog";
import { useToast } from "primevue/usetoast";
import { usePage } from "@inertiajs/vue3";
import Paginator from "primevue/paginator";

const page = usePage();
const toast = useToast();
const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    books: Array,
    allBook: Array,
});

const todaysBook = props.books.length > 0 ? props.books[0] : null;

const showModal = ref(false);
const selectedBook = ref(null);

const rows = 10;
const first = ref(0);
const paginatedBooks = computed(() => {
    return props.allBook.slice(first.value, first.value + rows);
});

const onPageChange = (event) => {
    first.value = event.first;
};

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

const saveBook = async () => {
    if (!selectedBook) return;

    try {
        const res = await fetch("/api/book/save", {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": page.props.csrf_token,
                // prettier-ignore
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify({ bookId: selectedBook.value.id }),
        });

        console.log(await res);

        if (!res.ok) throw new Error("Failed to save book");

        toast.add({
            severity: "success",
            summary:
                "Book saved successfully! You can view on the dashboard page.",
            life: 3000,
        });
    } catch (error) {
        console.error(error);
        toast.add({
            severity: "error",
            summary: "Failed to save book.",
            detail: error.message,
            life: 4000,
        });
    }
};
</script>

<template>
    <Head title="Books" />
    <DefaultLayout :canLogin="canLogin" :canRegister="canRegister">
        <h2 class="text-4xl font-bold mb-6">Latest Books</h2>
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
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-10 px-6"
        >
            <Card
                v-for="book in paginatedBooks"
                :key="book.id"
                @click="openBookModal(book.id)"
                class="cursor-pointer shadow-md border border-gray-200 rounded-lg hover:shadow-lg transition"
            >
                <template #title>
                    <h3 class="font-semibold text-lg truncate">
                        {{ book.title }}
                    </h3>
                </template>
                <template #content>
                    <p class="text-sm text-gray-600 line-clamp-3">
                        by <span class="underline">{{ book.author }}</span
                        ><br />
                        Genre: {{ book.genre }}<br />
                        Price: ${{ book.price }}
                    </p>
                </template>
            </Card>
        </div>

        <div class="flex justify-center mt-8">
            <Paginator
                :rows="rows"
                :totalRecords="props.allBook.length"
                :first="first"
                @page="onPageChange"
                template="PrevPageLink PageLinks NextPageLink"
            />
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
