import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

// Re-export all types from other modules
export * from './product';
export * from './dashboard';
export * from './ui';
export * from './api';

// Core application types
export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

// Common pagination interface
export interface PaginationMeta {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
}

export interface PaginatedResponse<T> extends PaginationMeta {
    data: T[];
    links: PaginationLink[];
    first_page_url: string;
    last_page_url: string;
    next_page_url: string | null;
    prev_page_url: string | null;
    path: string;
}

// Utility types
export type Optional<T, K extends keyof T> = Omit<T, K> & Partial<Pick<T, K>>;
export type WithTimestamps<T> = T & {
    created_at: string;
    updated_at: string;
};
export type WithId<T> = T & { id: number };
export type CreateRequest<T> = Omit<T, 'id' | 'created_at' | 'updated_at'>;
export type UpdateRequest<T> = Partial<CreateRequest<T>> & { id: number };

// Form types
export interface FormState<T> {
    data: T;
    errors: Record<keyof T, string>;
    processing: boolean;
    isDirty: boolean;
    hasErrors: boolean;
}

// API types
export interface ApiMeta {
    current_page?: number;
    last_page?: number;
    per_page?: number;
    total?: number;
}

export interface ApiLinks {
    first?: string;
    last?: string;
    prev?: string;
    next?: string;
}
