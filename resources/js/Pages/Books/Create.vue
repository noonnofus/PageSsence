<script setup>
import { Head } from "@inertiajs/vue3";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { ref } from "vue";
import { zodResolver } from "@primevue/forms/resolvers/zod";
import { useToast } from "primevue/usetoast";
import { z } from "zod";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const toast = useToast();
const initialValues = ref({
    title: "",
    author: "",
    description: "",
    genre: "",
    price: 0,
    publication_year: 0,
});

const resolver = ref(
    zodResolver(
        z.object({
            title: z.string().min(1, { message: "Title is required." }),
            author: z.string().min(1, { message: "Author is required." }),
            description: z.string().max(200, {
                message:
                    "Description is too wrong, please write it in 200 characters.",
            }),
            genre: z.string().min(1, { message: "Genre is required." }),
            price: z
                .number()
                .nonnegative({ message: "Price must be greater than 0." }),
            publication_year: z.number().nonnegative({
                message: "Publication year must be greater than 0.",
            }),
        })
    )
);

const genres = [
    { name: "Fiction" },
    { name: "Non-fiction" },
    { name: "Science Fiction" },
    { name: "Mystery" },
    { name: "Biography" },
    { name: "Historical" },
    { name: "Poetry" },
    { name: "Novel" },
    { name: "Children" },
    { name: "Drama" },
    { name: "Other" },
];

const onFormSubmit = async ({ valid, values }) => {
    if (!valid) return;
    try {
        const res = await fetch("/api/book/create", {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": page.props.csrf_token,
                // prettier-ignore
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(values),
        });

        console.log(await res);

        if (!res.ok) throw new Error("Failed to create book");

        toast.add({
            severity: "success",
            summary: "Book created successfully!",
            life: 3000,
        });
    } catch (error) {
        console.error(error);
        toast.add({
            severity: "error",
            summary: "Failed to create book.",
            detail: error.message,
            life: 4000,
        });
    }
};

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Add Book" />
    <DefaultLayout :canLogin="canLogin" :canRegister="canRegister">
        <Card
            class="h-full w-full shadow-md border border-gray-200 rounded-lg flex flex-col justify-between shadow-lg"
        >
            <template #title>
                <h1 class="ml-4 mt-3 text-3xl">Add/Register new book</h1>
            </template>
            <template #content>
                <Form
                    v-slot="$form"
                    :resolver="resolver"
                    :initialValues="initialValues"
                    @submit="onFormSubmit"
                    class="flex justify-center flex-col gap-3 mt-3"
                >
                    <div class="flex flex-col gap-1">
                        <h3 class="text-md">Book Title</h3>
                        <InputText
                            name="title"
                            type="text"
                            placeholder="Book Title"
                        />
                        <Message
                            v-if="$form.title?.invalid"
                            severity="error"
                            size="small"
                            variant="simple"
                            >{{ $form.title.error?.message }}</Message
                        >
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="text-md">Book's Author</h3>
                        <InputText
                            name="author"
                            type="text"
                            placeholder="Book's Author"
                        />
                        <Message
                            v-if="$form.author?.invalid"
                            severity="error"
                            size="small"
                            variant="simple"
                            >{{ $form.author.error?.message }}</Message
                        >
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="text-md">Book's Description</h3>
                        <Textarea
                            name="description"
                            placeholder="Description up to 200 characters"
                            rows="5"
                            cols="30"
                        />
                        <Message
                            v-if="$form.description?.invalid"
                            severity="error"
                            size="small"
                            variant="simple"
                            >{{ $form.description.error?.message }}</Message
                        >
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="text-md">Book's Genre</h3>
                        <Dropdown
                            name="genre"
                            :options="genres"
                            optionLabel="name"
                            optionValue="name"
                            placeholder="Select a Genre"
                        />

                        <Message
                            v-if="$form.genre?.invalid"
                            severity="error"
                            size="small"
                            variant="simple"
                        >
                            {{ $form.genre.error?.message }}
                        </Message>
                    </div>

                    <div class="flex flex-col gap-1">
                        <h3 class="text-md">Book's Price</h3>
                        <InputGroup>
                            <InputGroupAddon>$</InputGroupAddon>
                            <InputNumber
                                name="price"
                                placeholder="Book's Price"
                            />
                            <InputGroupAddon>.00</InputGroupAddon>
                        </InputGroup>

                        <Message
                            v-if="$form.price?.invalid"
                            severity="error"
                            size="small"
                            variant="simple"
                            >{{ $form.price.error?.message }}</Message
                        >
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="text-md">Book's Publication Year</h3>
                        <InputNumber
                            name="publication_year"
                            inputId="integeronly"
                            placeholder="Publication Year"
                            fluid
                        />
                        <Message
                            v-if="$form.publication_year?.invalid"
                            severity="error"
                            size="small"
                            variant="simple"
                            >{{
                                $form.publication_year.error?.message
                            }}</Message
                        >
                    </div>

                    <Button
                        type="submit"
                        severity="secondary"
                        label="Add Book"
                    />
                </Form>
            </template>
        </Card>
    </DefaultLayout>
</template>
