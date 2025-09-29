<script setup lang="ts">
import Logo from '@/components/Logo.vue'
import Layout from '@/layouts/auth.vue'
import { Head, useForm } from '@inertiajs/vue3'

defineOptions({ layout: Layout })

const form = useForm({
    email: '',
})

const onSubmit = () => {
    form.clearErrors()
    form.post(route('password.email'), {
        onSuccess: () => {
            form.reset('email')
        },
        preserveState: true,
    })
}
</script>

<template>
    <div class="flex">
        <Head title="Forgot password" />

        <!-- START: Register Form Root Layout -->
        <div class="w-full max-w-sm space-y-6">
            <!--  START:  Header Section        -->
            <div class="flex flex-col text-center">
                <div class="mb-2">
                    <Logo class="inline-block size-8 shrink-0" />
                </div>

                <div class="text-xl font-semibold text-pretty text-highlighted">Forgot password</div>
                <div class="mt-1 text-base text-pretty text-muted">
                    Enter your email to receive a password reset link
                </div>
            </div>
            <!--   END: Header Section        -->

            <!--  START:  Form Section       -->
            <div class="flex flex-col gap-y-6">
                <UForm class="space-y-5" @submit.prevent="onSubmit">
                    <UFormField
                        label="Email"
                        name="email"
                        :error="form.errors.email"
                        :help="form.recentlySuccessful ? 'Password reset link sent.' : ''"
                        required
                    >
                        <UInput
                            class="w-full"
                            v-model="form.email"
                            type="email"
                            autocomplete="off"
                            placeholder="Enter your email"
                            autofocus
                        />
                    </UFormField>

                    <UButton
                        label="Email password reset link"
                        type="submit"
                        block
                        :disabled="form.processing"
                        :loading="form.processing"
                        class="w-full"
                    />
                </UForm>
            </div>

            <div class="mt-2 text-center text-sm text-muted">
                Or, return to
                <ULink :to="route('login', {}, false)" target="_self" class="font-medium text-primary">Login</ULink>.
            </div>
        </div>
    </div>
</template>

<style scoped></style>
