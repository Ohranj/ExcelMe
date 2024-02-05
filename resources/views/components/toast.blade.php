<template x-teleport="body">
    <template x-for="toast in $store.toast.toasts">
        <div class="z-50 w-[315px] shadow shadow-primary-500 min-h-[75px] rounded-md mt-2 relative bottom-4 left-4">
            <div class="rounded-t-md text-white font-medium text-lg" :class="toast.state ? 'bg-green-500' : 'bg-red-500'">
                <h1 class="p-2" x-text="toast.state ? 'Success' : 'Error'"></h1>
            </div>
            <div class="p-2 rounded-b-md">
                <small x-text="toast.message"></small>
            </div>
        </div>
    </template>
</template>