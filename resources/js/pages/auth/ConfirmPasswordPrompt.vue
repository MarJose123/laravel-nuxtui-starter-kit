<script setup lang="ts">
import Logo from '@/components/Logo.vue'
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({
    password: '',
})

const onSubmit = () => {
    form.post(route('password.confirm.store'))
}
</script>

<template>
    <UApp>
        <UMain>
            <UContainer>
                <div class="flex flex-col items-center justify-center gap-4 p-4 pt-2 md:pt-16">
                    <div class="flex">
                        <Head title="Confirm password" />

                        <!-- START: Register Form Root Layout -->
                        <div class="w-full max-w-sm space-y-6">
                            <!--  START:  Header Section        -->
                            <div class="flex flex-col text-center">
                                <div class="mb-2">
                                    <Logo class="inline-block size-8 shrink-0" />
                                </div>

                                <div class="text-xl font-semibold text-pretty text-highlighted">
                                    Confirm your password
                                </div>
                                <div class="mt-1 text-base text-pretty text-muted">
                                    This is a secure area of the application. Please confirm your password before
                                    continuing.
                                </div>
                            </div>
                            <!--   END: Header Section        -->

                            <!--  START:  Form Section       -->
                            <div class="flex flex-col gap-y-6">
                                <UForm class="space-y-5" @submit.prevent="onSubmit">
                                    <USimplePasswordInput
                                        v-model="form.password"
                                        label="Password"
                                        :error="form.errors.password"
                                        placeholder="Enter your password"
                                        autofocus
                                        required
                                    />

                                    <UButton
                                        label="Confirm Password"
                                        type="submit"
                                        block
                                        :disabled="form.processing"
                                        :loading="form.processing"
                                        class="w-full"
                                    />
                                </UForm>
                            </div>
                        </div>
                    </div>
                </div>
            </UContainer>
        </UMain>
    </UApp>
</template>

<style scoped></style>
