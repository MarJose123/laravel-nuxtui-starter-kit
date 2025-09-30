<script setup lang="ts">
import USimplePasswordInput from '@/components/ui/USimplePasswordInput.vue'
import Layout from '@/layouts/default.vue'
import { Notification } from '@/types/notification'
import { Head, useForm } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@nuxt/ui'
import { onMounted, ref, watch } from 'vue'

defineOptions({ layout: Layout })

const breadcrumbItems = ref<BreadcrumbItem[]>([
    {
        label: 'Settings',
    },
    {
        label: 'Authentication',
        to: route('settings.password.edit', {}, false),
        target: '_self',
    },
])

interface Props {
    notification: Notification | null
}

const props = defineProps<Props>()
const toast = useToast()

const formPassword = useForm({
    password: '',
    password_confirmation: '',
    current_password: '',
})

onMounted(() => {
    if (props.notification) {
        toast.add({
            title: props.notification?.title
                ? props.notification?.title
                : props.notification?.type === 'success'
                  ? 'Success'
                  : 'Opps! Something went wrong',
            description: props.notification.message,
            color: props.notification?.type === 'success' ? 'success' : 'error',
            icon: props.notification?.type === 'success' ? 'i-heroicons-check-circle' : 'i-heroicons-x-circle',
            duration: 5000,
        })
    }
})

watch(
    () => props.notification,
    (notification) => {
        if (notification) {
            toast.add({
                title: notification.title
                    ? notification.title
                    : notification.type === 'success'
                      ? 'Success'
                      : 'Opps! Something went wrong',
                description: notification.message,
                color: notification.type === 'success' ? 'success' : 'error',
                icon: notification.type === 'success' ? 'i-heroicons-check-circle' : 'i-heroicons-x-circle',
            })
        }
    },
)

const onSubmit = () => {
    formPassword.put(route('settings.password.update'))
}
</script>

<template>
    <AppLayout :breadcrumbItems="breadcrumbItems">
        <Head title="Password and Authentication settings" />

        <div class="flex w-full flex-col space-y-6">
            <!--  START:  Header Section        -->
            <HeadingSmall
                title="Update password"
                description="Ensure your account is using a long, random password to stay secure"
            />
            <span class="flex w-7/12 border-b border-b-slate-200" />
            <!--   END: Header Section        -->

            <!--  START:  Form Section       -->
            <UForm class="w-5/12 space-y-6" @submit.prevent="onSubmit">
                <USimplePasswordInput
                    v-model="formPassword.current_password"
                    label="Current password"
                    name="current_password"
                    :error="formPassword.errors.current_password"
                    placeholder="Current password"
                    required
                />
                <UPasswordInput
                    v-model="formPassword.password"
                    label="New password"
                    name="password"
                    :error="formPassword.errors.password"
                    placeholder="New password"
                    required
                />
                <USimplePasswordInput
                    v-model="formPassword.password_confirmation"
                    label="Confirm password"
                    name="password_confirmation"
                    :error="formPassword.errors.password_confirmation"
                    placeholder="Confirm password"
                    required
                />

                <UButton
                    :label="formPassword.recentlySuccessful ? 'Saved.' : 'Save'"
                    type="submit"
                    :disabled="formPassword.processing"
                    :loading="formPassword.processing"
                />
            </UForm>
        </div>
    </AppLayout>
</template>

<style scoped></style>
