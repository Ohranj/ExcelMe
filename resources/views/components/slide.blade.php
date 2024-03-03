@props([
'showVar' => '',
'title' => '',
'subTitle' => ''
])

<div x-cloak>
    <div x-cloak x-show.transition.opacity.duration.500="{{ $showVar }}" class="fixed inset-0 bg-black bg-opacity-50"></div>
    <div class="fixed transition duration-500 rounded-md right-4 bottom-4 top-4 p-4 transform w-full xl:w-3/5 2xl:w-2/5 bg-primary-200 flex flex-col gap-8 border-l border-primary-300" :class="{'translate-x-full -right-4': !{{ $showVar }}}">
        <div class="flex items-center border-b-2 border-primary-400 p-4">
            <div class="grow font-medium">
                <h1 class="tracking-wide font-medium">{{ $title }}</h1>
                <small>{{ $subTitle }}</small>
            </div>
            <x-svg.cross stroke="#000000" stroke-width="1.5" class="w-8 h-8 cursor-pointer" fill="none" @click="{{ $showVar }} = false" />
        </div>
        <div class="px-4 flex flex-col gap-12">
            {{ $content }}
        </div>
    </div>
</div>