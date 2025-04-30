import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Photo {
    id: number;
    babysitter_profile_id: User['id'];
    url: string;
}

export interface Profile {
    id: number;
    first_name: string;
    last_name: string;
    phone: string;
    address: string;
    bio?: string;
    birthdate: string;
    experience: string;
    certifications: string[]; // Assuming certifications are strings
    user_id: User['id'];
    photos: Photo[];
}

export interface Availability {
    id: number;
    user_id?: number;
    start_date: string;
    end_date: string;
    start_time: string;
    end_time: string;
    note: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
