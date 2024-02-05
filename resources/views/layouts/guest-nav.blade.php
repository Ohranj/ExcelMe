<div class="h-[50px] font-medium relative">
    <div class="flex items-center justify-between xl:hidden">
        <img src="/assets/veggie-penguin.svg" class="w-auto h-[50px]" />
        <x-svg.menu fill="none" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 cursor-pointer" @click="nav.showDropdown = true" />
    </div>
    <div class="hidden xl:grid grid-cols-7 items-center">
        <img src="/assets/veggie-penguin.svg" class="w-auto h-[50px] col-span-3" />
        <div class="flex gap-6 justify-center">
            <button class="hover:underline underline-offset-4 decoration-2">About</button>
            <button class="hover:underline underline-offset-4 decoration-2" @click="modals.signup.show = true">Sign Up</button>
        </div>
        <div class="col-span-3 ml-auto">
            <div class="flex items-center gap-1 relative">
                <input type="email" class="w-[225px] rounded-md p-1 text-sm shadow-sm shadow-primary-500" placeholder="email address..." x-ref="login_email_input" x-model="login.builder.email" />
                <input type="password" class="w-[135px] rounded-md p-1 text-sm shadow-sm shadow-primary-500" placeholder="password..." x-model="login.builder.password" />
                <button class="hover:underline underline-offset-4 decoration-2" @click="logInBtnPressed">Log in</button>
                <div class="absolute top-8">
                    <input type="checkbox" class="rounded-full" x-model="login.builder.remember_me" />
                    <small>Remember Me</small>
                </div>
            </div>
        </div>
    </div>
    <div x-cloak x-show="nav.showDropdown" x-collapse class="relative bg-primary-300 top-2 w-full rounded-md" @click.away="nav.showDropdown = false">
        <ul class="p-4">
            <li class="mb-2">Sign Up</li>
            <li>About</li>
        </ul>
    </div>
</div>