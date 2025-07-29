<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { CheckCircleIcon } from '@heroicons/vue/24/outline'
import { ref, computed } from 'vue';

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    company_name: '',
});

const isSubmitted = ref(false);

const getNextFriday = () => {
    const now = new Date();

    // Convert current time to EST
    const estTime = new Date(now.toLocaleString("en-US", {timeZone: "America/New_York"}));
    const dayOfWeek = estTime.getDay(); // 0 = Sunday, 1 = Monday, ..., 5 = Friday, 6 = Saturday
    const currentHour = estTime.getHours();

    const nextFriday = new Date(estTime);

    // If today is Friday
    if (dayOfWeek === 5) {
        // If it's after 3PM EST (15:00), show next Friday
        if (currentHour >= 15) {
            nextFriday.setDate(estTime.getDate() + 7);
        }
        // If it's before 3PM EST, show today (current Friday)
        // No date change needed
    } else {
        // Calculate days until next Friday
        const daysUntilFriday = (5 - dayOfWeek + 7) % 7;
        if (daysUntilFriday === 0) {
            nextFriday.setDate(estTime.getDate() + 7);
        } else {
            nextFriday.setDate(estTime.getDate() + daysUntilFriday);
        }
    }

    return nextFriday;
};

const nextFridayFormatted = computed(() => {
    const nextFriday = getNextFriday();
    const options: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    return nextFriday.toLocaleDateString('en-US', options);
});

const submit = () => {
    form.post(route('submission.store'), {
        onSuccess: () => {
            isSubmitted.value = true;
            form.reset('first_name','last_name','email','company_name');
        },
        onError: () => {
            // Errors are automatically handled by Inertia and displayed in form.errors
        },
    });
};
</script>

<template>
    <Head>
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="relative isolate bg-gray-900">
        <div class="mx-auto grid max-w-7xl grid-cols-1 lg:grid-cols-2">
            <div class="relative px-6 pt-24 pb-20 sm:pt-32 lg:static lg:px-8 lg:py-48">
                <div class="mx-auto max-w-xl lg:mx-0 lg:max-w-lg">
                    <div class="absolute inset-y-0 left-0 -z-10 w-full overflow-hidden bg-gray-900 ring-1 ring-white/10 lg:w-1/2">
                        <svg class="absolute inset-0 size-full mask-[radial-gradient(100%_100%_at_top_right,white,transparent)] stroke-white/10" aria-hidden="true">
                            <defs>
                                <pattern id="83fd4e5a-9d52-42fc-97b6-718e5d7ee527" width="200" height="200" x="100%" y="-1" patternUnits="userSpaceOnUse">
                                    <path d="M130 200V.5M.5 .5H200" fill="none" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" stroke-width="0" class="fill-gray-900" />
                            <svg x="100%" y="-1" class="overflow-visible fill-gray-800/20">
                                <path d="M-470.5 0h201v201h-201Z" stroke-width="0" />
                            </svg>
                            <rect width="100%" height="100%" stroke-width="0" fill="url(#83fd4e5a-9d52-42fc-97b6-718e5d7ee527)" />
                        </svg>
                        <div class="absolute top-[calc(100%-13rem)] -left-56 block transform-gpu blur-3xl lg:top-[calc(50%-7rem)] lg:left-[max(-14rem,calc(100%-59rem))]" aria-hidden="true">
                            <div class="aspect-1155/678 w-288.75 bg-linear-to-br from-[#80caff] to-[#4f46e5] opacity-20" style="clip-path: polygon(74.1% 56.1%, 100% 38.6%, 97.5% 73.3%, 85.5% 100%, 80.7% 98.2%, 72.5% 67.7%, 60.2% 37.8%, 52.4% 32.2%, 47.5% 41.9%, 45.2% 65.8%, 27.5% 23.5%, 0.1% 35.4%, 17.9% 0.1%, 27.6% 23.5%, 76.1% 2.6%, 74.1% 56.1%)" />
                        </div>
                    </div>
                    <div class="flex gap-3 items-center mb-3">
                        <img src="rb-logo.png" class="w-44" alt="Ridgeback Cybersecurity & IT Overwatch">
                        <img src="armp-logo.png" class="w-44" alt="Automotive Risk Management Partners">
                    </div>
                    <h2 class="text-4xl font-semibold tracking-tight text-pretty text-white sm:text-5xl">Breach of Protocol</h2>
                    <h2 class="mt-6 text-xl font-semibold tracking-tight text-pretty text-white sm:text-2xl">{{ nextFridayFormatted }} 3pm  EST</h2>
                    <p class="mt-6 text-lg/8 text-white">Candid talk about the uncomfortable truths in cyber</p>
                    <img src="stream.png" alt="Stream">
                </div>
            </div>
            <div class="px-6 pt-20 pb-24 sm:pb-32 lg:px-8 lg:py-48">
                <!-- Thank you message -->
                <div v-if="isSubmitted" class="mx-auto max-w-xl lg:mr-0 lg:max-w-lg">
                    <div class="text-center">
                        <CheckCircleIcon class="mx-auto h-16 w-16 text-green-400" />
                        <h3 class="mt-6 text-2xl font-semibold text-white">Thank you!</h3>
                        <p class="mt-4 text-lg text-gray-300">
                            We've received your submission and will notify you of the upcoming livestream
                        </p>
                    </div>
                </div>

                <!-- Contact form -->
                <form v-else @submit.prevent="submit">
                    <div class="mx-auto max-w-xl lg:mr-0 lg:max-w-lg">
                    <p class="mb-5 text-lg/8 text-gray-400">Sign up to get notified of the next livestream.</p>
                        <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                            <div>
                                <label for="first_name" class="block text-sm/6 font-semibold text-white">First name*</label>
                                <div class="mt-2.5">
                                    <input type="text" name="first_name" id="first_name" v-model="form.first_name" autocomplete="given-name" :class="[
                                        'block w-full rounded-md bg-white/5 px-3.5 py-2 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2',
                                        form.errors.first_name ? 'outline-red-500 focus:outline-red-500' : 'focus:outline-blue-500'
                                    ]" />
                                </div>
                                <div v-if="form.errors.first_name" class="mt-2 text-sm text-red-400">
                                    {{ form.errors.first_name }}
                                </div>
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm/6 font-semibold text-white">Last name*</label>
                                <div class="mt-2.5">
                                    <input type="text" name="last_name" id="last_name" v-model="form.last_name" autocomplete="family-name" :class="[
                                        'block w-full rounded-md bg-white/5 px-3.5 py-2 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2',
                                        form.errors.last_name ? 'outline-red-500 focus:outline-red-500' : 'focus:outline-blue-500'
                                    ]" />
                                </div>
                                <div v-if="form.errors.last_name" class="mt-2 text-sm text-red-400">
                                    {{ form.errors.last_name }}
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-sm/6 font-semibold text-white">Email*</label>
                                <div class="mt-2.5">
                                    <input type="email" name="email" id="email" v-model="form.email" autocomplete="email" :class="[
                                        'block w-full rounded-md bg-white/5 px-3.5 py-2 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2',
                                        form.errors.email ? 'outline-red-500 focus:outline-red-500' : 'focus:outline-blue-500'
                                    ]" />
                                </div>
                                <div v-if="form.errors.email" class="mt-2 text-sm text-red-400">
                                    {{ form.errors.email }}
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="company_name" class="block text-sm/6 font-semibold text-white">Company name</label>
                                <div class="mt-2.5">
                                    <input type="text" name="company_name" id="company_name" v-model="form.company_name" autocomplete="organization" :class="[
                                        'block w-full rounded-md bg-white/5 px-3.5 py-2 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2',
                                        form.errors.company_name ? 'outline-red-500 focus:outline-red-500' : 'focus:outline-blue-500'
                                    ]" />
                                </div>
                                <div v-if="form.errors.company_name" class="mt-2 text-sm text-red-400">
                                    {{ form.errors.company_name }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="submit" :disabled="form.processing" class="rounded-md bg-blue-500 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-xs hover:bg-blue-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
