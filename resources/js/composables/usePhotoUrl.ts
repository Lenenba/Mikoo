// src/composables/usePhotoUrl.ts

export const usePhotoUrl = () => {
    const defaultAvatar = '/images/default-avatar.png';

    const getPhotoUrl = (url: string | null | undefined): string => {
        if (!url) {
            return defaultAvatar;
        }

        if (url.startsWith('http') || url.startsWith('data:image')) {
            return url;
        }

        const relativePath = url.startsWith('/') ? url.slice(1) : url;
        return `/storage/${relativePath}`;
    };

    return {
        getPhotoUrl,
    };
};
