<template>
    <div class="min-h-screen bg-gradient-to-br from-white to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <!-- Navigation Header -->
        <UHeader class="border-b border-gray-200 bg-white/80 backdrop-blur-sm dark:border-gray-700 dark:bg-gray-900/80">
            <template #title>
                <div class="flex items-center gap-2">
                    <UIcon name="i-heroicons-rocket-launch" class="h-8 w-8" />
                </div>
            </template>

            <template #right>
                <div class="flex items-center gap-3">
                    <UButton
                        variant="ghost"
                        size="sm"
                        class="hidden sm:inline-flex"
                        @click="router.visit(route('login'))"
                    >
                        Login
                    </UButton>
                    <UButton
                        variant="ghost"
                        size="sm"
                        class="hidden sm:inline-flex"
                        @click="router.visit(route('register'))"
                    >
                        Register
                    </UButton>
                </div>
            </template>
        </UHeader>

        <!-- Hero Section -->
        <section class="relative overflow-hidden">
            <div
                class="absolute inset-0 bg-gradient-to-r from-primary-50 to-blue-50 opacity-30 dark:from-primary-950 dark:to-blue-950"
            ></div>
            <UContainer class="relative py-20 sm:py-32">
                <div class="text-center">
                    <UBadge variant="subtle" class="mb-6">
                        <UIcon name="i-heroicons-star" class="mr-1 h-4 w-4" />
                        Latest Version Available
                    </UBadge>

                    <h1 class="mb-6 text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl dark:text-white">
                        Thanks for using Starter Kit!
                    </h1>

                    <p class="mx-auto mb-8 max-w-3xl text-xl leading-relaxed text-gray-600 dark:text-gray-300">
                        If you find this starter kit helpful, please consider giving it a star on GitHub. Your support
                        helps us maintain and improve this project for the community.
                    </p>

                    <!-- Quick Stats -->
                    <div class="flex flex-wrap justify-center gap-8 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center gap-2">
                            <UIcon name="i-heroicons-check-circle" class="h-4 w-4 text-green-500" />
                            TypeScript Ready
                        </div>
                        <div class="flex items-center gap-2">
                            <UIcon name="i-heroicons-check-circle" class="h-4 w-4 text-green-500" />
                            Modern Stack
                        </div>
                        <div class="flex items-center gap-2">
                            <UIcon name="i-heroicons-check-circle" class="h-4 w-4 text-green-500" />
                            Production Ready
                        </div>
                    </div>
                </div>
            </UContainer>
        </section>

        <!-- Features Section -->
        <section class="bg-white py-20 dark:bg-gray-900">
            <UContainer>
                <div class="mb-16 text-center">
                    <h2 class="mb-4 text-3xl font-bold text-gray-900 sm:text-4xl dark:text-white">
                        Everything You Need
                    </h2>
                    <p class="mx-auto max-w-2xl text-xl text-gray-600 dark:text-gray-300">
                        Built with modern technologies and best practices to help you ship faster
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <UCard class="group transition-shadow hover:shadow-lg">
                        <div class="p-6 text-center">
                            <div
                                class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-100 transition-transform group-hover:scale-110 dark:bg-yellow-900"
                            >
                                <UIcon name="i-heroicons-bolt" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" />
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">Lightning Fast</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Optimized build process with Vite for instant hot reload and fast production builds
                            </p>
                        </div>
                    </UCard>

                    <UCard class="group transition-shadow hover:shadow-lg">
                        <div class="p-6 text-center">
                            <div
                                class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 transition-transform group-hover:scale-110 dark:bg-blue-900"
                            >
                                <UIcon
                                    name="i-heroicons-cube-transparent"
                                    class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                />
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">Component Library</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Beautiful NuxtUI components with dark mode support and full customization
                            </p>
                        </div>
                    </UCard>

                    <UCard class="group transition-shadow hover:shadow-lg">
                        <div class="p-6 text-center">
                            <div
                                class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 transition-transform group-hover:scale-110 dark:bg-green-900"
                            >
                                <UIcon
                                    name="i-heroicons-shield-check"
                                    class="h-6 w-6 text-green-600 dark:text-green-400"
                                />
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">Type Safe</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Full TypeScript support with auto-completion and error detection
                            </p>
                        </div>
                    </UCard>

                    <UCard class="group transition-shadow hover:shadow-lg">
                        <div class="p-6 text-center">
                            <div
                                class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 transition-transform group-hover:scale-110 dark:bg-purple-900"
                            >
                                <UIcon
                                    name="i-heroicons-cog-6-tooth"
                                    class="h-6 w-6 text-purple-600 dark:text-purple-400"
                                />
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">
                                Highly Configurable
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Easily customize themes, components, and build configuration to match your needs
                            </p>
                        </div>
                    </UCard>

                    <UCard class="group transition-shadow hover:shadow-lg">
                        <div class="p-6 text-center">
                            <div
                                class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 transition-transform group-hover:scale-110 dark:bg-orange-900"
                            >
                                <UIcon
                                    name="i-heroicons-device-phone-mobile"
                                    class="h-6 w-6 text-orange-600 dark:text-orange-400"
                                />
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">Mobile First</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Responsive design that works perfectly on all devices and screen sizes
                            </p>
                        </div>
                    </UCard>

                    <UCard class="group transition-shadow hover:shadow-lg">
                        <div class="p-6 text-center">
                            <div
                                class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 transition-transform group-hover:scale-110 dark:bg-red-900"
                            >
                                <UIcon name="i-heroicons-heart" class="h-6 w-6 text-red-600 dark:text-red-400" />
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">
                                Developer Experience
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Amazing DX with hot reload, code splitting, and modern development tools
                            </p>
                        </div>
                    </UCard>
                </div>
            </UContainer>
        </section>

        <!-- Technology Stack Section -->
        <section class="bg-gray-50 py-20 dark:bg-gray-800">
            <UContainer>
                <div class="mb-16 text-center">
                    <h2 class="mb-4 text-3xl font-bold text-gray-900 sm:text-4xl dark:text-white">
                        Modern Technology Stack
                    </h2>
                    <p class="mx-auto max-w-2xl text-xl text-gray-600 dark:text-gray-300">
                        Built with the latest and most powerful technologies
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-8 md:grid-cols-4 lg:grid-cols-6">
                    <a
                        href="https://vitejs.dev/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group cursor-pointer text-center"
                    >
                        <div
                            class="rounded-xl bg-white p-6 shadow-sm transition-shadow group-hover:shadow-md dark:bg-gray-900"
                        >
                            <div class="mb-2 text-3xl">⚡</div>
                            <h3 class="mb-1 font-semibold text-gray-900 dark:text-white">Vite</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Build Tool</p>
                        </div>
                    </a>

                    <a
                        href="https://vuejs.org/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group cursor-pointer text-center"
                    >
                        <div
                            class="rounded-xl bg-white p-6 shadow-sm transition-shadow group-hover:shadow-md dark:bg-gray-900"
                        >
                            <div class="mb-2 text-3xl">🎨</div>
                            <h3 class="mb-1 font-semibold text-gray-900 dark:text-white">Vue 3</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Frontend</p>
                        </div>
                    </a>

                    <a
                        href="https://inertiajs.com/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group cursor-pointer text-center"
                    >
                        <div
                            class="rounded-xl bg-white p-6 shadow-sm transition-shadow group-hover:shadow-md dark:bg-gray-900"
                        >
                            <div class="mb-2 text-3xl">🔗</div>
                            <h3 class="mb-1 font-semibold text-gray-900 dark:text-white">Inertia.js</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">SPA Adapter</p>
                        </div>
                    </a>

                    <a
                        href="https://laravel.com/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group cursor-pointer text-center"
                    >
                        <div
                            class="rounded-xl bg-white p-6 shadow-sm transition-shadow group-hover:shadow-md dark:bg-gray-900"
                        >
                            <div class="mb-2 text-3xl">🐘</div>
                            <h3 class="mb-1 font-semibold text-gray-900 dark:text-white">Laravel</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Backend</p>
                        </div>
                    </a>

                    <a
                        href="https://www.typescriptlang.org/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group cursor-pointer text-center"
                    >
                        <div
                            class="rounded-xl bg-white p-6 shadow-sm transition-shadow group-hover:shadow-md dark:bg-gray-900"
                        >
                            <div class="mb-2 text-3xl">🎯</div>
                            <h3 class="mb-1 font-semibold text-gray-900 dark:text-white">TypeScript</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Type Safety</p>
                        </div>
                    </a>

                    <a
                        href="https://tailwindcss.com/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group cursor-pointer text-center"
                    >
                        <div
                            class="rounded-xl bg-white p-6 shadow-sm transition-shadow group-hover:shadow-md dark:bg-gray-900"
                        >
                            <div class="mb-2 text-3xl">💨</div>
                            <h3 class="mb-1 font-semibold text-gray-900 dark:text-white">Tailwind</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Styling</p>
                        </div>
                    </a>
                </div>
            </UContainer>
        </section>

        <!-- Footer -->
        <UFooter>
            <template #left>
                <p class="text-sm text-muted">© {{ new Date().getFullYear() }} • Starter Kit</p>
            </template>

            <template #right>
                <UButton
                    to="https://github.com/marjose123/starter-kit"
                    target="_blank"
                    icon="simple-icons:github"
                    aria-label="GitHub"
                    color="neutral"
                    variant="ghost"
                />
            </template>
        </UFooter>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
</script>

<style scoped>
/* Additional custom styles if needed */
</style>
