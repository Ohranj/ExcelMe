<div class="min-h-[50px] font-medium relative flex flex-col gap-8">
    <div class="flex items-center justify-between xl:hidden">
        <img src="/assets/veggie-penguin.svg" class="w-auto h-[50px]" />
        <button class="hover:underline underline-offset-4 decoration-2" @click="modals.signup.show = true">
            <x-svg.avatar-plus fill="none" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline-block" />
            Sign Up
        </button>
    </div>
    <div class="hidden xl:grid grid-cols-7 items-center">
        <img src="/assets/veggie-penguin.svg" class="w-auto h-[50px] col-span-3" />
        <div class="flex gap-8 justify-center">
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
    <div class="grid sm:grid-cols-5 md:grid-cols-7 xl:hidden items-center gap-2 relative">
        <input type="email" class="sm:col-span-2 md:col-span-4 rounded-md p-1 text-sm shadow-sm shadow-primary-500" placeholder="email address..." x-ref="login_email_input" x-model="login.builder.email" />
        <input type="password" class="sm:col-span-2 md:col-span-2 rounded-md p-1 text-sm shadow-sm shadow-primary-500" placeholder="password..." x-model="login.builder.password" />
        <button class="hover:bg-primary-900 text-white bg-primary-500 py-0.5 px-2 rounded-md mt-10 sm:mt-0" @click="logInBtnPressed">Log in</button>
        <div class="absolute top-20 sm:top-9">
            <input type="checkbox" class="rounded-full" x-model="login.builder.remember_me" />
            <small>Remember Me</small>
        </div>
    </div>


</div>