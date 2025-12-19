<script setup lang="ts">
import Layout from '@/layouts/default.vue'
import { Head, router, useForm, usePage } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@nuxt/ui'
import { ref, watch } from 'vue'

defineOptions({ layout: Layout })

interface Props {
    mustVerifyEmail: boolean
}

defineProps<Props>()

const breadcrumbItems = ref<BreadcrumbItem[]>([
    {
        label: 'Settings',
    },
    {
        label: 'Account',
        to: route('settings.account.edit', {}, false),
        target: '_self',
    },
])

const page = usePage()
const userRec = ref(page.props.auth.user)

const form = useForm({
    name: userRec.value.name,
    email: userRec.value.email,
})

watch(
    () => page.props.auth.user,
    (user) => (userRec.value = user),
)

const onSubmit = () => {
    form.patch(route('settings.account.update'))
}
</script>

<template>
    <AppLayout :breadcrumbItems="breadcrumbItems">
        <Head title="Account settings" />

        <div class="flex w-full flex-col space-y-6">
            <!--  START:  Header Section        -->
            <HeadingSmall title="Account settings" description="Update your name and email address." />
            <span class="flex w-7/12 border-b border-b-slate-200" />
            <!--   END: Header Section        -->

            <!--  START:  Form Section       -->
            <UForm class="w-5/12 space-y-6" @submit.prevent="onSubmit">
                <UFormField label="Name" name="name" :error="form.errors.name" required>
                    <UInput class="w-full" v-model="form.name" placeholder="Enter your name" />
                </UFormField>

                <UFormField label="Email" name="email" :error="form.errors.email" required>
                    <template #help v-if="mustVerifyEmail && !userRec.email_verified_at">
                        Your email address is unverified.
                        <UButton
                            @click.prevent="router.post(route('verification.send'))"
                            color="neutral"
                            variant="link"
                            class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >Resend</UButton
                        >
                    </template>
                    <UInput class="w-full" v-model="form.email" placeholder="Enter your email" />
                </UFormField>

                <UButton
                    :label="form.recentlySuccessful ? 'Saved.' : 'Save'"
                    type="submit"
                    :disabled="form.processing"
                    :loading="form.processing"
                />
            </UForm>

            <DeleteUser class="w-5/12 pt-16" />
        </div>
    </AppLayout>
</template>

<style scoped></style>
