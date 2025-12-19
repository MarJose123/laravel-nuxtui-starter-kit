export type NotificationType = 'success' | 'error' | 'warning' | 'info' | (string & {})

export interface Notification {
    type: NotificationType
    icon: string
    color: Toast['variants']['color']
    title?: string
    message: string
}
