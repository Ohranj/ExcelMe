<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="guest({ csrfToken: '{{ csrf_token() }}' })" class="bg-primary-50 min-h-screen flex flex-col">
    <div class="w-5/6 mx-auto grow flex flex-col gap-12 py-6">
        @include('layouts.guest-nav')
        {{ $slot }}
    </div>

    <x-toast />

    <x-modal showVar="modals.signup.show" title="Create an Account">
        <x-slot:content>
            <div class="flex flex-col gap-4 sm:gap-6">
                <div class="grid sm:grid-cols-2 gap-4 sm:gap-6">
                    <input type="text" placeholder="forename..." class="rounded-md p-1 text-sm shadow-sm shadow-primary-500" x-model="register.builder.forename" />
                    <input type="text" placeholder="surname..." class="rounded-md p-1 text-sm shadow-sm shadow-primary-500" x-model="register.builder.surname" />
                </div>
                <input type="email" placeholder="email address..." class="rounded-md p-1 text-sm shadow-sm shadow-primary-500" x-model="register.builder.email" />
                <div class="grid sm:grid-cols-2 gap-4 sm:gap-6">
                    <input type="password" placeholder="password..." class="rounded-md p-1 text-sm shadow-sm shadow-primary-500" x-model="register.builder.password" />
                    <input type="password" x-model="register.builder.password_confirmation" placeholder="confirm password..." class="rounded-md p-1 text-sm shadow-sm shadow-primary-500" />
                </div>
                <div class="flex justify-center items-center gap-2 mt-6">
                    <div class="border-b border-primary-400 grow"></div>
                    <small class="font-medium">Add an organisation?</small>
                    <div class="border-b border-primary-400 grow"></div>
                </div>
                <div class="flex flex-col gap-2">
                    <small>If you would like to share access to your spreadsheets and data. You can create an organisation below. In doing so you have the option to invite and collaborate with your datasets.</small>
                    <small>Don't worry, you can create, manage and remove your organisation at any time.</small>
                </div>
                <div class="flex flex-col gap-4">
                    <div>
                        <input type="checkbox" x-model="register.builder.create_organisation" class="rounded-full cursor-pointer" />
                        <small>Create an organisation</small>
                    </div>
                    <input type="text" placeholder="organisation name..." x-model="register.builder.organisation" class="rounded-md p-1 text-sm shadow-sm shadow-primary-500" @input="register.builder.create_organisation = register.builder.organisation.length >= 1" />
                </div>
                <button class="bg-primary-500 hover:bg-primary-900 text-white py-0.5 rounded-md w-[150px] self-end my-6 font-medium" @click="registerBtnPressed">Create Account</button>
                <small @click="modals.signup.show = false; $refs.login_email_input.focus()" class="text-center hover:underline underline-offset-2 decoration-2 cursor-pointer">Already have an account?</small>
            </div>
        </x-slot:content>
    </x-modal>

    <script>
        const guest = (e) => ({
            nav: {
                showDropdown: false
            },
            modals: {
                signup: {
                    show: false
                },
            },
            login: {
                builder: {}
            },
            register: {
                builder: {}
            },
            async logInBtnPressed() {
                const response = await fetch(route('login.store'), {
                    method: 'post',
                    body: JSON.stringify({
                        ...this.login.builder
                    }),
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': e.csrfToken
                    }
                })
                const json = await response.json();
                if (!response.ok) {
                    Alpine.store('toast').toggle(false, json.message);
                    return;
                }
                console.log('User Authenticated')
                //Redirect to auth dashboard
            },
            async registerBtnPressed() {
                const response = await fetch(route('register.store'), {
                    method: 'post',
                    body: JSON.stringify({
                        ...this.register.builder
                    }),
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': e.csrfToken
                    }
                })
                const json = await response.json();
                if (!response.ok) {
                    Alpine.store('toast').toggle(false, json.message);
                    return;
                }
                console.log(json);
                //Redirect to auth dashboard
            },
            ...e
        })
    </script>
</body>

</html>