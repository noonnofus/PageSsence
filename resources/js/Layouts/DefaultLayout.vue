<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const searchQuery = ref("");
const searchResults = ref([]);
const showResults = ref(false);
const page = usePage();

const isAdmin = computed(() => page.props.auth?.user?.role === "admin");

const fetchBooks = async (query) => {
    try {
        if (!query.trim()) {
            showResults.value = false;
            searchResults.value = [];
            return;
        }
        const response = await fetch(
            `/api/books/search?title=${encodeURIComponent(query)}`
        );

        if (!response.ok) throw new Error("Network response was not ok");

        const data = await response.json();
        searchResults.value = data;
        showResults.value = true;
    } catch (error) {
        console.error("Error fetching books:", error);
    }
};

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const isAuthenticated = computed(() => !!page.props.auth?.user);
</script>

<template>
    <Toast />
    <header
        class="w-full flex items-center justify-between py-4 px-6 bg-white shadow"
    >
        <Link href="/" class="flex-shrink-0">
            <ApplicationLogo class="h-14 w-14 fill-current text-gray-500" />
        </Link>

        <div class="flex-1 mx-4">
            <div class="input-group input-group-sm w-full">
                <input
                    type="search"
                    class="form-control form-control-sm w-full rounded px-3 py-1 text-sm"
                    placeholder="Search Books"
                    aria-label="Search"
                    aria-describedby="search-addon"
                    v-model="searchQuery"
                    @input="fetchBooks(searchQuery)"
                />
                <ul
                    v-if="showResults"
                    class="absolute z-10 bg-white border border-gray-300 rounded mt-1 shadow-lg"
                >
                    <li
                        v-for="book in searchResults"
                        :key="book.id"
                        class="px-3 py-2 hover:bg-gray-200"
                    >
                        <Link :href="`/book/${book.id}`" class="block">
                            {{ book.title }}
                        </Link>
                    </li>
                    <li
                        v-if="searchResults.length === 0"
                        class="px-3 py-2 text-gray-500"
                    >
                        No results found
                    </li>
                </ul>
                <span
                    class="input-group-text border-0 p-2 cursor-pointer"
                    id="search-addon"
                    @click="handleSearch"
                >
                    <i class="fas fa-search search-icon"></i>
                </span>
            </div>
        </div>

        <nav v-if="canLogin" class="flex items-center gap-3 whitespace-nowrap">
            <Link
                v-if="isAuthenticated"
                :href="route('books.index')"
                class="rounded-md px-3 py-1 text-black text-sm ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
                Books
            </Link>

            <Link
                v-if="isAuthenticated && isAdmin"
                :href="route('books.create')"
                class="rounded-md px-3 py-1 text-black text-sm ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
                Add Book
            </Link>

            <Link
                v-if="isAuthenticated"
                :href="route('dashboard')"
                class="rounded-md px-3 py-1 text-black text-sm ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
                Dashboard
            </Link>

            <template v-else-if="!isAuthenticated">
                <Link
                    v-if="props.canLogin"
                    :href="route('login')"
                    class="rounded-md px-3 py-1 text-black text-sm ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Log in
                </Link>

                <Link
                    v-if="props.canRegister"
                    :href="route('register')"
                    class="rounded-md px-3 py-1 text-black text-sm ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                    Register
                </Link>
            </template>
        </nav>
    </header>
    <main class="p-6">
        <slot />
    </main>
</template>
