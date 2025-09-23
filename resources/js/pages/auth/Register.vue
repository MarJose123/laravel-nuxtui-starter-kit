<script setup lang="ts">
import Logo from '@/components/Logo.vue'
import UPasswordInput from '@/components/ui/UPasswordInput.vue'
import Layout from '@/layouts/auth.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({ layout: Layout })

const show = ref(false)

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const onSubmit = () => {
    console.log('Submitted', form)

    form.clearErrors()

    form.post(route('register.store'), {
        onSuccess: () => {
            router.visit(route('login'))
        },
        onError: () => {
            form.reset('password_confirmation')
        },
    })
}
</script>

<template>
    <div class="flex flex-col items-center justify-center gap-4 p-4 pt-2 md:pt-18">
        <Head title="Register" />

        <!-- START: Register Form Root Layout -->
        <div class="w-full max-w-sm space-y-6">
            <!--  START:  Header Section        -->
            <div class="flex flex-col text-center">
                <div class="mb-2">
                    <Logo class="inline-block size-8 shrink-0" />
                </div>

                <div class="text-xl font-semibold text-pretty text-highlighted">Create an account</div>
                <div class="mt-1 text-base text-pretty text-muted">Enter your details below to create your account</div>
            </div>
            <!--   END: Header Section        -->

            <!--  START:  Form Section       -->
            <div class="flex flex-col gap-y-6">
                <UForm class="space-y-5" @submit.prevent="onSubmit">
                    <UFormField label="Name" name="name" :error="form.errors.name" required>
                        <UInput class="w-full" v-model="form.name" placeholder="Enter your name" />
                    </UFormField>

                    <UFormField label="Email" name="email" :error="form.errors.email" required>
                        <UInput class="w-full" v-model="form.email" placeholder="Enter your email" />
                    </UFormField>

                    <UPasswordInput v-model="form.password" label="Password" :error="form.errors.password" required />

                    <UFormField
                        label="Confirm Password"
                        name="password_confirmation"
                        :error="form.errors.password_confirmation"
                        required
                    >
                        <UInput
                            v-model="form.password_confirmation"
                            placeholder="Confirm your password"
                            :type="show ? 'text' : 'password'"
                            :ui="{ trailing: 'pe-1' }"
                            class="w-full"
                        >
                            <template #trailing>
                                <UButton
                                    color="neutral"
                                    variant="link"
                                    size="sm"
                                    :icon="show ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                                    :aria-label="show ? 'Hide password' : 'Show password'"
                                    :aria-pressed="show"
                                    aria-controls="password"
                                    @click="show = !show"
                                />
                            </template>
                        </UInput>
                    </UFormField>

                    <UButton
                        label="Register"
                        type="submit"
                        block
                        :disabled="form.processing"
                        :loading="form.processing"
                        class="w-full"
                    />
                </UForm>
            </div>

            <div class="mt-2 text-center text-sm text-muted">
                Already have an account?
                <ULink :to="route('login')" target="_self" class="font-medium text-primary">Login</ULink>.
            </div>
        </div>
    </div>
</template>

<style scoped>
::-ms-reveal {
    display: none;
}
</style>
