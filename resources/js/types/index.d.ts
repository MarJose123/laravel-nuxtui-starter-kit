import { User } from '@/types/user'

export interface Auth {
    user: User
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string
    auth: Auth
    appearance: 'light' | 'dark' | 'system'
}
