<script setup>
import { Head } from "@inertiajs/vue3";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <DefaultLayout :canLogin="canLogin" :canRegister="canRegister">
            <div
                class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
            >
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <main class="mt-6">
                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                            <a
                                href="/"
                                id="docs-card"
                                class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
                            >
                                <div
                                    class="relative flex items-center gap-6 lg:items-end"
                                >
                                    <div
                                        id="docs-card-content"
                                        class="flex items-start gap-6 lg:flex-col"
                                    >
                                        <div
                                            class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#10B981]/10 sm:size-16"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="size-5 sm:size-6"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    d="M10.063 21.7917C10.3894 22.1483 11 21.9534 11 21.4699V4C10 3 8.91255 2.57151 7.78361 2.32246C5.78311 1.88113 4.0024 1.97693 2.91265 2.11876C1.70543 2.27587 1 3.34931 1 4.40561V17.5662C1 18.9895 2.18834 20.1115 3.56807 20.066C4.71011 20.0284 6.2952 20.0688 7.73105 20.4158C8.82596 20.6803 9.52237 21.2009 10.063 21.7917Z"
                                                    fill="#000000"
                                                />
                                                <path
                                                    d="M13.937 21.7917C13.6106 22.1483 13 21.9534 13 21.4699V4C14 3 15.0874 2.57151 16.2164 2.32246C18.2169 1.88113 19.9976 1.97693 21.0873 2.11876C22.2946 2.27587 23 3.34931 23 4.40561V17.5662C23 18.9895 21.8117 20.1115 20.4319 20.066C19.2899 20.0284 17.7048 20.0688 16.269 20.4158C15.174 20.6803 14.4776 21.2009 13.937 21.7917Z"
                                                    fill="#000000"
                                                />
                                            </svg>
                                        </div>

                                        <div class="pt-3 sm:pt-5 lg:pt-0">
                                            <h2
                                                class="text-xl font-semibold text-black dark:text-white"
                                            >
                                                Discovery
                                            </h2>

                                            <p class="mt-4 text-sm/relaxed">
                                                PageSsence helps you discover
                                                the perfect book tailored to
                                                your taste. Whether you're an
                                                avid reader or just starting
                                                your literary journey, our
                                                platform guides you to books
                                                that match your interests,
                                                ensuring an enjoyable and
                                                personalized reading experience.
                                            </p>
                                        </div>
                                    </div>

                                    <svg
                                        class="size-6 shrink-0 stroke-[#000000]"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
                                        />
                                    </svg>
                                </div>
                            </a>

                            <a
                                href="/"
                                class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
                            >
                                <div
                                    class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#10B981]/10 sm:size-16"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="size-5 sm:size-6"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <g
                                            id="SVGRepo_bgCarrier"
                                            stroke-width="0"
                                        ></g>
                                        <g
                                            id="SVGRepo_tracerCarrier"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M10.0802 7.89712C11.1568 5.96571 11.6952 5 12.5 5C13.3048 5 13.8432 5.96571 14.9198 7.89712L15.1984 8.3968C15.5043 8.94564 15.6573 9.22007 15.8958 9.40114C16.1343 9.5822 16.4314 9.64942 17.0255 9.78384L17.5664 9.90622C19.6571 10.3793 20.7025 10.6158 20.9512 11.4156C21.1999 12.2153 20.4872 13.0487 19.0619 14.7154L18.6932 15.1466C18.2881 15.6203 18.0856 15.8571 17.9945 16.1501C17.9034 16.443 17.934 16.759 17.9953 17.3909L18.051 17.9662C18.2665 20.19 18.3742 21.3019 17.7231 21.7962C17.072 22.2905 16.0932 21.8398 14.1357 20.9385L13.6292 20.7053C13.073 20.4492 12.7948 20.3211 12.5 20.3211C12.2052 20.3211 11.927 20.4492 11.3708 20.7053L10.8643 20.9385C8.90677 21.8398 7.928 22.2905 7.27688 21.7962C6.62575 21.3019 6.7335 20.19 6.94899 17.9662L7.00474 17.3909C7.06597 16.759 7.09659 16.443 7.00548 16.1501C6.91438 15.8571 6.71186 15.6203 6.30683 15.1466L5.93808 14.7154C4.51276 13.0487 3.8001 12.2153 4.04881 11.4156C4.29751 10.6158 5.34288 10.3793 7.43361 9.90622L7.9745 9.78384C8.56862 9.64942 8.86568 9.5822 9.1042 9.40114C9.34272 9.22007 9.4957 8.94565 9.80165 8.3968L10.0802 7.89712Z"
                                                fill="#000000"
                                            ></path>
                                            <path
                                                d="M4.86752 2.50058C4.89751 2.3948 5.08528 2.39416 5.11598 2.49974C5.25618 2.98185 5.51616 3.69447 5.90928 4.08495C6.30241 4.47543 7.01676 4.73058 7.49981 4.86752C7.6056 4.89751 7.60623 5.08528 7.50065 5.11598C7.01854 5.25618 6.30592 5.51616 5.91545 5.90928C5.52497 6.30241 5.26981 7.01676 5.13287 7.49981C5.10288 7.6056 4.91511 7.60623 4.88441 7.50065C4.74421 7.01854 4.48424 6.30592 4.09111 5.91545C3.69798 5.52497 2.98363 5.26981 2.50058 5.13287C2.3948 5.10288 2.39416 4.91511 2.49974 4.88441C2.98185 4.74421 3.69447 4.48424 4.08495 4.09111C4.47543 3.69798 4.73058 2.98363 4.86752 2.50058Z"
                                                fill="#000000"
                                            ></path>
                                            <path
                                                fill-rule="evenodd"
                                                clip-rule="evenodd"
                                                d="M19 3.25C19.4142 3.25 19.75 3.58579 19.75 4V4.25H20C20.4142 4.25 20.75 4.58579 20.75 5C20.75 5.41421 20.4142 5.75 20 5.75H19.75V6C19.75 6.41421 19.4142 6.75 19 6.75C18.5858 6.75 18.25 6.41421 18.25 6V5.75H18C17.5858 5.75 17.25 5.41421 17.25 5C17.25 4.58579 17.5858 4.25 18 4.25H18.25V4C18.25 3.58579 18.5858 3.25 19 3.25Z"
                                                fill="#000000"
                                            ></path>
                                        </g>
                                    </svg>
                                </div>

                                <div class="pt-3 sm:pt-5">
                                    <h2
                                        class="text-xl font-semibold text-black dark:text-white"
                                    >
                                        Reviews
                                    </h2>

                                    <p class="mt-4 text-sm/relaxed">
                                        PageSsence provides insightful reviews
                                        to help you choose your next great read.
                                        Whether you seek in-depth analysis or
                                        quick recommendations, our
                                        community-driven reviews offer honest
                                        opinions and thoughtful perspectives,
                                        guiding you toward books that truly
                                        resonate with you.
                                    </p>
                                </div>

                                <svg
                                    class="size-6 shrink-0 self-center stroke-[#000000]"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
                                    />
                                </svg>
                            </a>

                            <a
                                href="/"
                                class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
                            >
                                <div
                                    class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#10B981]/10 sm:size-16"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                        xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"
                                        class="size-5 sm:size-6"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <title>comments</title>
                                        <desc>Created with Sketch Beta.</desc>
                                        <defs></defs>
                                        <g
                                            id="Page-1"
                                            stroke="none"
                                            stroke-width="1"
                                            fill="none"
                                            fill-rule="evenodd"
                                            sketch:type="MSPage"
                                        >
                                            <g
                                                id="Icon-Set-Filled"
                                                sketch:type="MSLayerGroup"
                                                transform="translate(-259.000000, -257.000000)"
                                                fill="#000000"
                                            >
                                                <path
                                                    d="M265.5,267 C266.329,267 267,267.672 267,268.5 C267,269.329 266.329,270 265.5,270 C264.671,270 264,269.329 264,268.5 C264,267.672 264.671,267 265.5,267 L265.5,267 Z M271.5,267 C272.329,267 273,267.672 273,268.5 C273,269.329 272.329,270 271.5,270 C270.671,270 270,269.329 270,268.5 C270,267.672 270.671,267 271.5,267 L271.5,267 Z M277.5,267 C278.329,267 279,267.672 279,268.5 C279,269.329 278.329,270 277.5,270 C276.671,270 276,269.329 276,268.5 C276,267.672 276.671,267 277.5,267 L277.5,267 Z M268.637,279.736 C269.414,279.863 271.181,280 272,280 C279.18,280 284,274.657 284,268.375 C284,262.093 277.977,257 272,257 C264.811,257 259,262.093 259,268.375 C259,272.015 260.387,275.104 263,277.329 L263,283 L268.637,279.736 L268.637,279.736 Z M285.949,266.139 L286,267 C286.008,267.817 286,267.742 286,268.5 C286,276.475 279.716,282 271,282 L268,282 C270.38,284.328 273.149,285.75 277,285.75 C277.819,285.75 278.618,285.676 279.395,285.549 L285,289 L285,283.329 C288.04,281.246 290,278.015 290,274.375 C290,271.131 288.439,268.211 285.949,266.139 L285.949,266.139 Z"
                                                    id="comments"
                                                    sketch:type="MSShapeGroup"
                                                ></path>
                                            </g>
                                        </g>
                                    </svg>
                                </div>

                                <div class="pt-3 sm:pt-5">
                                    <h2
                                        class="text-xl font-semibold text-black dark:text-white"
                                    >
                                        Comments
                                    </h2>

                                    <p class="mt-4 text-sm/relaxed">
                                        See what others think about your
                                        favorite books on PageSsence. Engage
                                        with a community of readers through
                                        comments, share your thoughts, and
                                        explore diverse perspectives, making
                                        your reading experience even more
                                        enriching.
                                    </p>
                                </div>

                                <svg
                                    class="size-6 shrink-0 self-center stroke-[#000000]"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
                                    />
                                </svg>
                            </a>
                        </div>
                    </main>
                </div>
            </div>
        </DefaultLayout>
    </div>
</template>
