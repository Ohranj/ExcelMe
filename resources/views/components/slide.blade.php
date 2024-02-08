@props([
'showVar' => '',
'title' => '',
'subTitle' => ''
])

<section x-cloak>
    <div x-cloak x-show.transition.opacity.duration.500="{{ $showVar }}" class="fixed inset-0 bg-black bg-opacity-50"></div>
    <div class="fixed transition duration-500 right-0 top-0 p-4 transform w-full xl:w-3/5 2xl:w-2/5 h-screen bg-primary-200 flex flex-col gap-8 border-l border-primary-300" :class="{'translate-x-full': !{{ $showVar }}}">
        <div class="flex items-center border-b-2 border-primary-400 p-4">
            <div class="grow font-medium">
                <h1 class="tracking-wide font-medium">{{ $title }}</h1>
                <small>{{ $subTitle }}</small>
            </div>
            <x-svg.cross stroke="#FFFFFF" stroke-width="1.5" class="w-8 h-8 bg-red-500 cursor-pointer rounded-full" fill="none" @click="{{ $showVar }} = false" />
        </div>
        <div class="px-4 flex flex-col gap-4">
            {{ $content }}
        </div>
    </div>
</section>