<div class="h-[50px] font-medium relative">
    <div class="flex items-center justify-between xl:hidden">
        <img src="/assets/veggie-penguin.svg" class="w-auto h-[50px]" />
        <x-svg.menu fill="none" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 cursor-pointer" @click="nav.showDropdown = true" />
    </div>
    <div class="hidden xl:grid grid-cols-7 items-center">
        <img src="/assets/veggie-penguin.svg" class="w-auto h-[50px] col-span-3" />
        <button class="hover:underline underline-offset-4 decoration-2">
            My Account
            <x-svg.spanner fill="none" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline-block" />
        </button>
        <div class="col-span-3 ml-auto">
            <form method="post" :action="route('logout')">
                @csrf
                <button class="hover:underline underline-offset-4 decoration-2">
                    <x-svg.logout fill="none" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline-block" />Log out</button>
            </form>
        </div>
    </div>
    <div x-cloak x-show="nav.showDropdown" x-collapse class="xl:hidden relative bg-primary-300 top-2 w-full rounded-md" @click.away="nav.showDropdown = false">
        <ul class="p-4">
            <li class="p-1 mb-2">My Account</li>
            <li class="p-1">Log out</li>
        </ul>
    </div>
</div>