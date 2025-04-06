<script setup>
import { ref, computed, watch } from "vue";
import { Head } from "@inertiajs/vue3";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import Dialog from "primevue/dialog";
import { useToast } from "primevue/usetoast";
import { usePage } from "@inertiajs/vue3";
import Paginator from "primevue/paginator";
import { onMounted } from "vue";
import Rate from "@/Components/Rate.vue";
import AddRate from "@/Components/AddRate.vue";

onMounted(() => {
    const currentUrl = new URL(window.location.href);
    const bookId = currentUrl.searchParams.get("show");

    if (bookId) {
        openBookModal(bookId);
    }
});

const isAdmin = computed(() => page.props.auth?.user?.role === "admin");

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
const averageRating = ref(null);
const userRating = ref(0);

const searchQuery = ref("");

watch(searchQuery, () => {
    first.value = 0;
});

const rows = 10;
const first = ref(0);

const editModal = ref(false);
const editBook = ref({
    id: null,
    title: "",
    author: "",
    genre: "",
    publication_year: "",
    price: "",
    description: "",
});

const openEditModal = (book) => {
    editBook.value = { ...book };
    editModal.value = true;
};

const onPageChange = (event) => {
    first.value = event.first;
};

const fetchAverageRating = async (bookId) => {
    try {
        const res = await fetch(`/api/book/${bookId}/rating`);
        const data = await res.json();
        averageRating.value = data.average;
    } catch (err) {
        console.error("Error getting star rate:", err);
    }
};

const openBookModal = async (bookId) => {
    try {
        const response = await fetch(`/api/book/${bookId}`);
        const data = await response.json();
        selectedBook.value = data;
        await fetchAverageRating(bookId);
        showModal.value = true;
    } catch (error) {
        console.error("Failed to fetch book:", error);
    }
};

const filteredBooks = computed(() => {
    if (!searchQuery.value.trim()) return props.allBook;

    return props.allBook.filter((book) =>
        [book.title, book.author].some((field) =>
            field?.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
    );
});

const paginatedBooks = computed(() => {
    return filteredBooks.value.slice(first.value, first.value + rows);
});

const closeBookModal = () => {
    showModal.value = false;
    selectedBook.value = null;

    const url = new URL(window.location.href);
    url.searchParams.delete("show");
    window.history.replaceState({}, "", url.pathname);
};

const updateBook = async () => {
    try {
        const res = await fetch(`/api/book/update/${editBook.value.id}`, {
            method: "PUT",
            credentials: "include",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": page.props.csrf_token,
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(editBook.value),
        });

        if (!res.ok) throw new Error("Failed to update book");

        toast.add({
            severity: "success",
            summary: "Book updated!",
            life: 3000,
        });

        editModal.value = false;
        location.reload();
    } catch (err) {
        toast.add({
            severity: "error",
            summary: "Update failed",
            detail: err.message,
            life: 4000,
        });
    }
};

const submitRating = async () => {
    if (!userRating.value || !selectedBook.value) return;

    try {
        const res = await fetch("/api/book/rate", {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": page.props.csrf_token,
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify({
                book_id: selectedBook.value.id,
                rating: userRating.value,
            }),
        });

        if (!res.ok) throw new Error("Rating submission failed");

        const data = await res.json();

        toast.add({
            severity: "success",
            summary: data.message,
            life: 3000,
        });

        await fetchAverageRating(selectedBook.value.id);
        userRating.value = 0;
    } catch (error) {
        toast.add({
            severity: "error",
            summary: "Rating failed",
            detail: error.message,
            life: 3000,
        });
    }
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
                    <template #footer v-if="isAdmin">
                        <Button
                            icon="pi pi-pencil"
                            label="Edit"
                            class="px-2 py-1 text-sm"
                            severity="warning"
                            @click.stop="openEditModal(book)"
                        />
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
                            <div v-if="isAdmin">
                                <Button
                                    icon="pi pi-pencil"
                                    label="Edit"
                                    class="px-2 py-1 text-sm"
                                    severity="warning"
                                    @click.stop="openEditModal(book)"
                                />
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
        </div>
        <h2 class="text-4xl font-bold mt-12 mb-6 text-center">All Books</h2>
        <div class="px-6 mt-6 flex justify-center">
            <input
                type="text"
                v-model="searchQuery"
                class="w-full md:w-1/2 border border-gray-300 rounded px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Search by title or author"
            />
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
                <template #footer v-if="isAdmin">
                    <Button
                        icon="pi pi-pencil"
                        label="Edit"
                        class="px-2 py-1 text-sm"
                        severity="warning"
                        @click.stop="openEditModal(book)"
                    />
                </template>
            </Card>
        </div>

        <div class="flex justify-center mt-8">
            <Paginator
                :rows="rows"
                :totalRecords="filteredBooks.length"
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
            <button
                @click="closeBookModal"
                class="absolute top-4 right-4 text-gray-500 hover:text-black text-xl"
            >
                &times;
            </button>
            <div
                v-if="selectedBook"
                class="relative flex flex-col md:flex-row gap-6 p-6 bg-white rounded shadow-lg"
            >
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
            <div
                class="mt-4 flex flex-col md:flex-row items-center justify-between gap-6"
            >
                <div class="flex items-center gap-2">
                    <Rate
                        v-if="averageRating !== null"
                        :rating="averageRating"
                    />
                </div>

                <div class="flex flex-col items-center md:items-end">
                    <h4 class="font-semibold mb-1">Rate this book:</h4>
                    <AddRate v-model="userRating" />
                    <p class="text-sm text-gray-500 mt-1">
                        {{
                            userRating ? `${userRating} / 5` : "Select a rating"
                        }}
                    </p>
                    <Button
                        label="Submit Rating"
                        icon="pi pi-check"
                        class="mt-2 px-3 py-1 text-sm"
                        severity="info"
                        @click="submitRating"
                        :disabled="!userRating"
                    />
                </div>
            </div>
        </Dialog>
        <Dialog
            v-model:visible="editModal"
            modal
            class="w-full max-w-3xl"
            header="Edit Book"
            @hide="editModal = false"
        >
            <div class="grid gap-4">
                <div class="flex flex-col">
                    <label>Title</label>
                    <InputText v-model="editBook.title" />
                </div>
                <div class="flex flex-col">
                    <label>Author</label>
                    <InputText v-model="editBook.author" />
                </div>
                <div class="flex flex-col">
                    <label>Genre</label>
                    <InputText v-model="editBook.genre" />
                </div>
                <div class="flex flex-col">
                    <label>Publication Year</label>
                    <InputNumber v-model="editBook.publication_year" />
                </div>
                <div class="flex flex-col">
                    <label>Price</label>
                    <InputNumber
                        v-model="editBook.price"
                        mode="currency"
                        currency="USD"
                    />
                </div>
                <div class="flex flex-col">
                    <label>Description</label>
                    <Textarea
                        v-model="editBook.description"
                        autoResize
                        rows="3"
                    />
                </div>
            </div>

            <template #footer>
                <Button
                    label="Cancel"
                    @click="editModal = false"
                    class="p-button-text"
                />
                <Button label="Save Changes" @click="updateBook" />
            </template>
        </Dialog>
    </DefaultLayout>
</template>
