export type NotificationType = 'success' | 'error'

export interface Notification {
    type: NotificationType
    title: string | null
    message: string
}
