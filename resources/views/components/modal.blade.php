@props([
'title' => '',
'showVar' => false,
])

<div x-cloak x-show="{{ $showVar }}" class="fixed inset-0 overflow-y-auto z-40 flex flex-col justify-center items-center px-1">
    <div class="fixed inset-0">
        <div class="absolute inset-0 bg-primary-300 opacity-75"></div>
    </div>
    <div x-cloak x-show="{{ $showVar }}" x-transition.opacity class="shadow shadow-primary-700 bg-white rounded-md overflow-hidden transform transition-all w-full md:w-[750px] mx-auto">
        <div class="border-b p-4 flex justify-between">
            <h1 class="text-xl font-medium">{{ $title }}</h1>
            <x-svg.cross stroke-width="1.5" stroke="#FFFFFF" class="w-8 h-8 bg-red-500 rounded-full cursor-pointer" fill="none" @click="{{ $showVar }} = false" />
        </div>
        <div class="p-4">
            {{ $content }}
        </div>
    </div>
</div>