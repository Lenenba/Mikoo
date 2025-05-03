<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogoIcon2 from '@/components/AppLogoIcon2.vue';
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden w-full max-w-[335px] text-sm lg:max-w-7xl items-center">
            <!-- Logo on the left -->
            <nav class="flex items-center justify-between gap-4">
                <div class="flex items-center w-32">
                    <AppLogoIcon2 />
                </div>
                <div>
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                    Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')"
                            class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]">
                        Log in
                        </Link>
                        <Link :href="route('register')"
                            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                        Register
                        </Link>
                    </template>
                </div>
            </nav>
        </header>
        <div
            class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main
                class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-7xl lg:flex-row">
                <div class="flex w-full flex-col mt-8">
                    <div
                        class="relative min-h-[40rem] lg:min-h-0 bg-surface-0 dark:bg-surface-900 flex lg:flex-row flex-col">
                        <div class="flex lg:flex lg:flex-row flex-col justify-center md:justify-normal h-full flex-1">
                            <div class="relative flex-1 z-20 flex items-center justify-center">
                                <div class="flex items-center justify-center h-full">
                                    <div class="w-full max-w-2xl px-6 py-12 lg:p-12 xl:p-16 text-center lg:text-left">
                                        <h1
                                            class="text-4xl xl:text-5xl font-bold text-surface-0 lg:text-surface-900 dark:text-surface-0 mb-6 !leading-tight">
                                            <span class="block">Trouvez la nounou parfaite </span>
                                            <span class="block text-primary">en toute confiance</span>
                                        </h1>

                                        <p
                                            class="text-surface-0/90 lg:text-surface-700 dark:text-surface-200 text-xl leading-normal mb-8 max-w-xl lg:max-w-none">
                                            Des centaines de familles font confiance à Mikoo pour leur tranquillité.
                                        </p>

                                        <div class="flex items-center gap-4 justify-center lg:justify-start">
                                            <Button label="Learn More" type="button" />
                                            <Button label="Live Demo" type="button" outlined />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute lg:relative inset-0 lg:inset-auto flex-1">
                                <div class="absolute lg:hidden inset-0 bg-surface-900/60 dark:bg-surface-900/80 z-10" />
                                <img src="https://images.unsplash.com/photo-1524503033411-c9566986fc8f?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                    alt="hero-1"
                                    class="h-full w-full object-cover xl:[clip-path:polygon(12%_0,100%_0%,100%_100%,0_100%)]" />
                            </div>
                        </div>
                    </div>

                    <!-- prize -->

                    <div class="bg-surface-0 dark:bg-surface-950 p-6 md:p-12 lg:p-20">
                        <div class="flex flex-col gap-12 items-center justify-center">
                            <div class="flex flex-col gap-6 items-center justify-center">
                                <div class="flex flex-col gap-4 items-center justify-center">
                                    <div
                                        class="text-primary-500 dark:text-primary-400 text-xl lg:text-2xl text-center leading-tight">
                                        Save up to 25% today!</div>
                                    <div
                                        class="text-surface-900 dark:text-surface-0 font-bold text-4xl lg:text-5xl text-center leading-tight">
                                        Pricing Plans</div>
                                </div>

                                <div class="flex gap-4 items-center justify-center">
                                    <span
                                        :class="[{ 'text-surface-500 dark:text-surface-400': checked }, { 'font-medium text-surface-900 dark:text-surface-0': !checked }]">Monthly</span>
                                    <ToggleSwitch v-model="checked" />
                                    <span
                                        :class="[{ 'text-surface-500 dark:text-surface-400': !checked }, { 'font-medium text-surface-900 dark:text-surface-0': checked }]">Yearly</span>
                                </div>
                            </div>

                            <div class="mt-10 flex flex-col lg:flex-row justify-center gap-10 mx-auto">
                                <div
                                    class="flex-1 w-full max-w-[26rem] bg-surface-50 dark:bg-surface-900 rounded-2xl border border-surface-200 dark:border-surface-800">
                                    <div
                                        class="px-6 pt-8 pb-4 border-b border-surface-200 dark:border-surface-700 text-center">
                                        <div
                                            class="text-xl font-medium text-surface-900 dark:text-surface-0 leading-tight mb-2">
                                            Basic</div>
                                        <div class="flex items-center justify-center gap-2">
                                            <span
                                                class="text-4xl font-semibold text-surface-900 dark:text-surface-0 leading-tight">{{
                                                    !checked ? '$10' : '$100' }}</span>
                                            <span class="text-xl text-surface-500 dark:text-surface-400">/{{ checked ?
                                                'year' : 'month' }}</span>
                                        </div>
                                    </div>
                                    <div class="p-6 flex flex-col gap-6">
                                        <span
                                            class="text-lg text-surface-600 dark:text-surface-400 leading-normal">Subtitle
                                            of the plan maybe two lines even though lines of things.</span>
                                        <ul class="list-none p-0 m-0 flex flex-col gap-2">
                                            <li v-for="feature in features" :key="feature"
                                                class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-surface-500 dark:bg-surface-400" />
                                                <span class="text-surface-700 dark:text-surface-200">{{ feature
                                                }}</span>
                                            </li>
                                        </ul>
                                        <Button class="w-full" label="Buy Now" />
                                    </div>
                                </div>

                                <div
                                    class="flex-1 w-full max-w-[26rem] bg-surface-900 dark:bg-surface-800 rounded-2xl relative">
                                    <div
                                        class="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary text-primary-contrast text-sm font-semibold px-2 py-1 rounded-3xl">
                                        MOST POPULAR</div>
                                    <div class="px-6 pt-8 pb-4 border-b border-surface-700 text-center">
                                        <div class="text-xl font-medium text-surface-0 leading-tight mb-2">Premium</div>
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-4xl font-semibold text-surface-0 leading-tight">{{
                                                !checked ?
                                                    '$20' : '$200' }}</span>
                                            <span class="text-xl text-surface-400">/{{ checked ? 'year' : 'month'
                                            }}</span>
                                        </div>
                                    </div>
                                    <div class="p-6 flex flex-col gap-6">
                                        <span class="text-lg text-surface-400 leading-normal">Subtitle of the plan maybe
                                            two
                                            lines even though lines of things.</span>
                                        <ul class="list-none p-0 m-0 flex flex-col gap-2">
                                            <li v-for="feature in features" :key="feature"
                                                class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-surface-400" />
                                                <span class="text-surface-0">{{ feature }}</span>
                                            </li>
                                        </ul>
                                        <Button class="w-full" label="Buy Now" />
                                    </div>
                                </div>

                                <div class="flex-1 w-full max-w-[26rem] bg-primary-800 dark:bg-primary-700 rounded-2xl">
                                    <div
                                        class="px-6 pt-8 pb-4 border-b border-primary-700/30 dark:border-primary-600 text-center">
                                        <div class="text-xl font-medium text-surface-0 leading-tight mb-2">Enterprise
                                        </div>
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-4xl font-semibold text-surface-0 leading-tight">{{
                                                !checked ?
                                                    '$900' : '$9000' }}</span>
                                            <span class="text-xl text-primary-200">/{{ checked ? 'year' : 'month'
                                            }}</span>
                                        </div>
                                    </div>
                                    <div class="p-6 flex flex-col gap-6">
                                        <span class="text-lg text-primary-200 leading-normal">Subtitle of the plan maybe
                                            two
                                            lines even though lines of things.</span>
                                        <ul class="list-none p-0 m-0 flex flex-col gap-2">
                                            <li v-for="feature in features" :key="feature"
                                                class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-primary-200" />
                                                <span class="text-surface-0">{{ feature }}</span>
                                            </li>
                                        </ul>
                                        <Button class="w-full" label="Contact Us" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- footer -->
                    <div class="bg-surface-0 dark:bg-surface-950 px-6 md:px-12 lg:px-20">
                        <div class="py-12 flex flex-col sm:flex-row sm:items-center justify-between">
                            <div>
                                <svg class="fill-surface-600 dark:fill-surface-300" width="140" height="36"
                                    viewBox="0 0 140 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M30.729 5.27316C28.2118 2.75554 25.0045 1.04091 21.5128 0.346113C18.021 -0.348687 14.4017 0.00758329 11.1124 1.36986C7.82319 2.73214 5.01178 5.03929 3.03377 7.99939C1.05576 10.9596 0 14.4398 0 18C0 21.5602 1.05576 25.0404 3.03377 28.0006C5.01178 30.9607 7.82319 33.2679 11.1124 34.6301C14.4017 35.9924 18.021 36.3487 21.5128 35.6539C25.0045 34.9591 28.2118 33.2445 30.729 30.7268C34.104 27.3513 36 22.7733 36 18C36 13.2267 34.104 8.64874 30.729 5.27316ZM4.69446 11.9086C5.60147 9.89685 6.94543 8.11247 8.62869 6.68533C10.3119 5.25818 12.292 4.22408 14.425 3.6583C16.5581 3.09253 18.7904 3.00928 20.9596 3.4146C23.1289 3.81992 25.1805 4.70359 26.9654 6.00139C22.5472 5.58411 17.0163 7.84228 12.5327 12.3177C10.8405 14.0071 9.40686 15.9371 8.27814 18.045C6.74293 16.2185 5.53095 14.1432 4.69446 11.9086ZM14.619 32.7886C11.2026 31.8732 8.22452 29.7698 6.21958 26.8561C4.21464 23.9424 3.31419 20.4093 3.67997 16.8913C5.53719 20.925 10.2254 24.5904 16.3781 26.2268C18.6751 26.8499 21.0523 27.1257 23.4309 27.045C22.6225 29.2811 21.4412 31.3642 19.9372 33.2059C18.1529 33.3864 16.3506 33.2394 14.619 32.7723V32.7886ZM25.82 15.6804C25.2134 13.3732 24.2706 11.1677 23.0217 9.135C25.3611 8.71856 27.7541 8.70197 30.099 9.08588C31.1486 10.5389 31.9224 12.1724 32.3817 13.905C33.2986 17.3219 32.967 20.9538 31.4463 24.1481C29.9256 27.3424 27.3157 29.8896 24.0854 31.3323C26.6381 27.7241 27.4563 21.8086 25.82 15.6804Z" />
                                    <path
                                        d="M63.2903 15.6134C63.2903 16.2587 63.066 16.815 62.6173 17.2823C62.3482 17.5494 62.2136 17.8275 62.2136 18.1168C62.2136 18.3839 62.3257 18.6287 62.55 18.8512L62.6173 18.9179C63.066 19.363 63.2903 19.9193 63.2903 20.5869V26.5619C63.2903 27.2072 63.066 27.7636 62.6173 28.2309L61.5407 29.299C61.092 29.7441 60.5425 29.9666 59.892 29.9666H51.3458C50.4486 29.9666 50 29.5216 50 28.6314V7.33519C50 6.44506 50.4486 6 51.3458 6H59.892C60.5425 6 61.092 6.23366 61.5407 6.70097L62.6173 7.76912C63.066 8.21419 63.2903 8.75939 63.2903 9.40473V15.6134ZM54.4077 9.03755C53.5104 9.03755 53.0618 9.48262 53.0618 10.3727V15.2462C53.0618 16.1363 53.5104 16.5814 54.4077 16.5814H58.8826C59.7798 16.5814 60.2285 16.1363 60.2285 15.2462L60.2621 10.3727C60.2621 9.48262 59.8135 9.03755 58.9163 9.03755H54.4077ZM60.2621 20.9541C60.2621 20.064 59.8135 19.6189 58.9163 19.6189H54.4077C53.5104 19.6189 53.0618 20.064 53.0618 20.9541V25.5939C53.0618 26.484 53.5104 26.9291 54.4077 26.9291H58.9163C59.8135 26.9291 60.2621 26.484 60.2621 25.5939V20.9541Z" />
                                    <path
                                        d="M70.0907 29.9332C69.4403 29.9332 68.8907 29.7107 68.4421 29.2656L67.3318 28.1641C66.8831 27.7191 66.6588 27.1739 66.6588 26.5285V22.6565C66.6588 22.3227 66.7149 22.0334 66.8271 21.7886C66.9392 21.5216 67.1187 21.2656 67.3654 21.0209L68.4421 19.9527C68.8907 19.5077 69.4403 19.2851 70.0907 19.2851H73.2871C74.1844 19.2851 74.633 18.8401 74.633 17.9499V17.8498C74.633 16.9597 74.1844 16.5146 73.2871 16.5146H68.1056C67.2084 16.5146 66.7598 16.0695 66.7598 15.1794V14.8122C66.7598 13.9221 67.2084 13.4771 68.1056 13.4771H74.2629C74.9134 13.4771 75.4629 13.6996 75.9115 14.1446L77.0219 15.2462C77.4705 15.6912 77.6948 16.2364 77.6948 16.8818V28.5981C77.6948 29.4882 77.2462 29.9332 76.3489 29.9332H70.0907ZM74.633 23.3574C74.633 22.4673 74.1844 22.0223 73.2871 22.0223H71.0665C70.1693 22.0223 69.7206 22.4673 69.7206 23.3574V25.8609C69.7206 26.751 70.1693 27.1961 71.0665 27.1961H73.2871C74.1844 27.1961 74.633 26.751 74.633 25.8609V23.3574Z" />
                                    <path
                                        d="M90.6646 18.2503C90.2384 18.2503 89.902 18.1725 89.6552 18.0167C89.4309 17.8609 89.3188 17.6384 89.3188 17.3491C89.3188 17.0821 89.1954 16.8707 88.9487 16.7149C88.7244 16.5591 88.3991 16.4812 87.9729 16.4812H85.8196C84.9223 16.4812 84.4737 16.9263 84.4737 17.8164V19.185C84.4737 20.0751 84.9223 20.5202 85.8196 20.5202H88.9487C89.5992 20.5202 90.1487 20.7427 90.5973 21.1878L91.7077 22.2893C92.1563 22.7344 92.3806 23.2796 92.3806 23.9249V26.5619C92.3806 27.2072 92.1563 27.7524 91.7077 28.1975L90.5973 29.299C90.1487 29.7441 89.5992 29.9666 88.9487 29.9666H84.8438C84.1933 29.9666 83.6438 29.7441 83.1952 29.299L82.0848 28.1975C81.6362 27.7524 81.4119 27.2072 81.4119 26.5619V26.5285C81.4119 25.6384 81.8605 25.1933 82.7578 25.1933H83.1279C83.5541 25.1933 83.8793 25.2712 84.1036 25.427C84.3504 25.5828 84.4737 25.7942 84.4737 26.0612C84.4737 26.3282 84.5859 26.5396 84.8102 26.6954C85.0569 26.8512 85.3934 26.9291 85.8196 26.9291H87.9729C88.8702 26.9291 89.3188 26.484 89.3188 25.5939V24.5925C89.3188 23.7024 88.8702 23.2573 87.9729 23.2573H84.8102C84.4737 23.2573 84.1821 23.2128 83.9354 23.1238C83.6886 23.0125 83.4307 22.8456 83.1615 22.6231L82.1521 21.7552C81.883 21.5327 81.6923 21.299 81.5801 21.0542C81.468 20.7872 81.4119 20.4757 81.4119 20.1196V16.8484C81.4119 16.5146 81.468 16.2253 81.5801 15.9805C81.6923 15.7135 81.8717 15.4576 82.1185 15.2128L83.1952 14.1446C83.6438 13.6996 84.1933 13.4771 84.8438 13.4771H88.9487C89.6216 13.4771 90.1824 13.6996 90.631 14.1446L91.7077 15.2128C92.1563 15.6579 92.3806 16.2031 92.3806 16.8484V16.9152C92.3806 17.8053 91.932 18.2503 91.0347 18.2503H90.6646Z" />
                                    <path
                                        d="M103.476 26.9291C104.374 26.9291 104.822 27.3741 104.822 28.2643V28.6314C104.822 29.5216 104.374 29.9666 103.476 29.9666H101.155C100.504 29.9666 99.9548 29.7441 99.5062 29.299L98.3959 28.1975C97.9473 27.7524 97.723 27.2072 97.723 26.5619V17.8164C97.723 17.3936 97.6108 17.0709 97.3865 16.8484C97.1622 16.6036 96.837 16.4812 96.4108 16.4812C95.5135 16.4812 95.0649 16.0362 95.0649 15.146V14.8122C95.0649 14.3894 95.1771 14.0668 95.4014 13.8442C95.6481 13.5994 95.9846 13.4771 96.4108 13.4771C96.837 13.4771 97.1622 13.3658 97.3865 13.1433C97.6108 12.8985 97.723 12.5647 97.723 12.1419V10.9402C97.723 10.0501 98.1716 9.60501 99.0688 9.60501H99.4389C100.336 9.60501 100.785 10.0501 100.785 10.9402V12.1419C100.785 13.032 101.233 13.4771 102.131 13.4771H103.476C104.374 13.4771 104.822 13.9221 104.822 14.8122V15.146C104.822 16.0362 104.374 16.4812 103.476 16.4812H102.131C101.233 16.4812 100.785 16.9263 100.785 17.8164V25.5939C100.785 26.484 101.233 26.9291 102.131 26.9291H103.476Z" />
                                    <path
                                        d="M108.013 9.07093C108.35 8.73714 108.698 8.57024 109.057 8.57024C109.415 8.57024 109.763 8.73714 110.1 9.07093C110.436 9.40473 110.604 9.74965 110.604 10.1057C110.604 10.4618 110.436 10.8067 110.1 11.1405C109.763 11.4743 109.415 11.6412 109.057 11.6412C108.698 11.6412 108.35 11.4743 108.013 11.1405C107.677 10.8067 107.509 10.4618 107.509 10.1057C107.509 9.74965 107.677 9.40473 108.013 9.07093ZM107.542 14.8122C107.542 13.9221 107.991 13.4771 108.888 13.4771H109.225C110.122 13.4771 110.571 13.9221 110.571 14.8122V28.6314C110.571 29.5216 110.122 29.9666 109.225 29.9666H108.888C107.991 29.9666 107.542 29.5216 107.542 28.6314V14.8122Z" />
                                    <path
                                        d="M124.632 15.2128C125.08 15.6801 125.305 16.2364 125.305 16.8818V26.5619C125.305 27.2072 125.08 27.7636 124.632 28.2309L123.555 29.299C123.107 29.7441 122.557 29.9666 121.906 29.9666H117.734C117.084 29.9666 116.534 29.7441 116.086 29.299L114.975 28.1975C114.527 27.7524 114.302 27.2072 114.302 26.5619V16.8818C114.302 16.2364 114.527 15.6912 114.975 15.2462L116.086 14.1446C116.534 13.6996 117.084 13.4771 117.734 13.4771H121.906C122.557 13.4771 123.107 13.6996 123.555 14.1446L124.632 15.2128ZM122.243 17.8498C122.243 16.9597 121.794 16.5146 120.897 16.5146H118.71C117.813 16.5146 117.364 16.9597 117.364 17.8498V25.5939C117.364 26.484 117.813 26.9291 118.71 26.9291H120.897C121.794 26.9291 122.243 26.484 122.243 25.5939V17.8498Z" />
                                    <path
                                        d="M132.059 28.6648C132.059 29.5549 131.611 30 130.714 30H130.344C129.446 30 128.998 29.5549 128.998 28.6648V16.8818C128.998 16.2364 129.222 15.6912 129.671 15.2462L130.781 14.1446C131.23 13.6996 131.779 13.4771 132.43 13.4771H136.602C137.252 13.4771 137.802 13.7107 138.25 14.178L139.327 15.2462C139.776 15.6912 140 16.2364 140 16.8818V28.6648C140 29.5549 139.551 30 138.654 30H138.318C137.42 30 136.972 29.5549 136.972 28.6648V17.8498C136.972 16.9597 136.523 16.5146 135.626 16.5146H133.405C132.508 16.5146 132.059 16.9597 132.059 17.8498V28.6648Z" />
                                </svg>
                                <div class="mt-2 leading-normal">&copy; 202X Bastion, Inc. All rights reserved</div>
                            </div>
                            <div class="mt-4 sm:mt-0">
                                <a
                                    class="cursor-pointer text-surface-500 dark:text-surface-300 transition-colors duration-150 hover:text-surface-700 dark:hover:text-surface-100">
                                    <i class="pi pi-twitter text-xl" />
                                </a>
                                <a
                                    class="cursor-pointer text-surface-500 dark:text-surface-300 ml-4 transition-colors duration-150 hover:text-surface-700 dark:hover:text-surface-100">
                                    <i class="pi pi-facebook text-xl" />
                                </a>
                                <a
                                    class="cursor-pointer text-surface-500 dark:text-surface-300 ml-4 transition-colors duration-150 hover:text-surface-700 dark:hover:text-surface-100">
                                    <i class="pi pi-github text-xl" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>
