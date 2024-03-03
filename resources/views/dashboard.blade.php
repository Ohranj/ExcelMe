<x-app-layout>
    @routes(['dashboard'])
    <div class="flex flex-col gap-12" x-data="dashboard({ csrfToken: '{{ csrf_token() }}' })">
        <button class="hover:bg-primary-900 text-white font-medium bg-primary-500 min-w-[125px] py-0.5 px-2 rounded-md ml-auto block" @click="slides.sheets.show = true">Upload</button>
        <div>
            <div class="flex items-center gap-8">
                <small class="grow text-primary-600 flex items-center gap-1 relative">Filter: <span class="cursor-pointer text-primary-900" @click="sheets.params.filter.show = true">All Sheets</span>
                    <x-svg.chevron fill="none" stroke-width="2.5" stroke="currentColor" class="w-3 h-3" />
                    <div x-cloak x-show="sheets.params.filter.show" x-collapse class="bg-primary-700 rounded absolute top-6 min-w-[175px]" @click.away="sheets.params.filter.show = false">
                        <small class="px-5 text-primary-400 font-medium mt-4 block">Sort by</small>
                        <ul class="flex flex-col gap-1 text-primary-200 px-4 pb-4">
                            <li class="hover:bg-amber-600 px-1 py-0.5 rounded cursor-pointer flex items-center gap-1" @click="sheets.params.filter.org = false; retrieveUploadedSheets()">
                                <x-svg.tick stroke-width="2.0" stroke="#FFFFFF" class="w-3 h-3" ::class="{'opacity-0': sheets.params.filter.org}" fill="none" />My sheets
                            </li>
                            <li class="hover:bg-amber-600 px-1 py-0.5 rounded cursor-pointer flex items-center gap-1" @click="sheets.params.filter.org = true; retrieveUploadedSheets()">
                                <x-svg.tick stroke-width="2.0" stroke="#FFFFFF" class="w-3 h-3" ::class="{'opacity-0': !sheets.params.filter.org}" fill="none" />
                                My Organisation
                            </li>
                        </ul>
                    </div>
                </small>
                <small class="text-primary-600 flex items-center gap-1 relative">Sort: <span class="cursor-pointer text-primary-900" @click="sheets.params.sort.show = true">Date uploaded</span>
                    <x-svg.chevron fill="none" stroke-width="2.5" stroke="currentColor" class="w-3 h-3" />
                    <div x-cloak x-show="sheets.params.sort.show" x-collapse class="bg-primary-700 rounded absolute top-6 min-w-[175px]" @click.away="sheets.params.sort.show = false">
                        <small class="px-5 text-primary-400 font-medium mt-4 block">Sort by</small>
                        <ul class="flex flex-col gap-1 text-primary-200 px-4 pb-4">
                            <li class="hover:bg-amber-600 px-1 py-0.5 rounded cursor-pointer flex items-center gap-1" @click="sheets.params.sort.value = 'alphabetical'; retrieveUploadedSheets()">
                                <x-svg.tick stroke-width="2.0" stroke="#FFFFFF" class="w-3 h-3" ::class="{'opacity-0': sheets.params.sort.value != 'alphabetical'}" fill="none" />
                                Alphabetical
                            </li>
                            <li class="hover:bg-amber-600 px-1 py-0.5 rounded cursor-pointer flex items-center gap-1" @click="sheets.params.sort.value = 'uploaded'; retrieveUploadedSheets()">
                                <x-svg.tick stroke-width="2.0" stroke="#FFFFFF" class="w-3 h-3" ::class="{'opacity-0': sheets.params.sort.value != 'uploaded'}" fill="none" />
                                Date uploaded
                            </li>
                            <li class="hover:bg-amber-600 px-1 py-0.5 rounded cursor-pointer flex items-center gap-1" @click="sheets.params.sort.value = 'size'; retrieveUploadedSheets()">
                                <x-svg.tick stroke-width="2.0" stroke="#FFFFFF" class="w-3 h-3" ::class="{'opacity-0': sheets.params.sort.value != 'size'}" fill="none" />
                                File size
                            </li>
                        </ul>
                        <small class="px-5 text-primary-400 font-medium">Order</small>
                        <ul class="flex flex-col gap-1 text-primary-200 px-4 mb-4">
                            <li class="hover:bg-amber-600 px-1 py-0.5 rounded cursor-pointer flex items-center gap-1" @click="sheets.params.sort.asc = true; retrieveUploadedSheets()">
                                <x-svg.tick stroke-width="2.0" stroke="#FFFFFF" class="w-3 h-3" ::class="{'opacity-0': !sheets.params.sort.asc}" fill="none" />
                                Ascending
                            </li>
                            <li class="hover:bg-amber-600 px-1 py-0.5 rounded cursor-pointer flex items-center gap-1" @click="sheets.params.sort.asc = false; retrieveUploadedSheets()">
                                <x-svg.tick stroke-width="2.0" stroke="#FFFFFF" class="w-3 h-3" ::class="{'opacity-0': sheets.params.sort.asc}" fill="none" />
                                Descending
                            </li>
                        </ul>
                    </div>
                </small>
                <small class="flex items-center gap-1">
                    <x-svg.card-layout fill="none" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 cursor-pointer" />
                    <x-svg.card-layout fill="none" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 cursor-pointer" />
                </small>
            </div>
            <template x-if="sheets.list.length">
                <div class="mt-4">
                    <div class="shadow shadow-primary-700 rounded">
                        <div class="grid grid-cols-9 gap-2 bg-primary-700 text-white p-1 rounded-t items-center font-semibold">
                            <small class="col-span-2">File Name</small>
                            <small class="col-span-2">Uploaded By</small>
                            <small>Total Rows</small>
                            <small>Total Columns</small>
                            <small>Last Modified</small>
                            <small>Created</small>
                            <small class="text-center border-l">Actions</small>
                        </div>
                        <template x-for="sheet in sheets.list">
                            <div class="px-1 py-0.5 grid grid-cols-9 gap-2 items-center hover:bg-primary-300 rounded-b cursor-pointer">
                                <small class="col-span-2" x-text="sheet.client_name"></small>
                                <small class="col-span-2" x-text="sheet.user.forename + ' ' + sheet.user.surname"></small>
                                <small x-text="sheet.meta_data.totals.rows"></small>
                                <small x-text="sheet.meta_data.totals.cols"></small>
                                <small x-text="sheet.updated_at_human"></small>
                                <small x-text="sheet.created_at_human"></small>
                                <div class="flex justify-center gap-8">
                                    <div class="shadow shadow-primary-700 rounded-full p-0.5 bg-primary-200">
                                        <x-svg.info stroke-width="1.5" stroke="currentColor" class="w-6 h-6 cursor-pointer" fill="none" />
                                    </div>
                                    <div class="shadow shadow-primary-700 rounded-full p-0.5 bg-primary-200" @click="slides.delete.sheet = sheet; slides.delete.show = true">
                                        <x-svg.trash stroke-width="1.5" stroke="red" class="w-6 h-6 cursor-pointer" fill="none" />
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
            <template x-if="!sheets.list.length">
                <div class="flex flex-col items-center mt-20 gap-2">
                    <x-svg.search stroke-width="1.5" stroke="currentColor" class="w-6 h-6" fill="none" />
                    <small>You have no active sheets. Click the <q>upload</q> button to get started.</small>
                </div>
            </template>
        </div>

        <x-modal showVar="slides.sheets.show" title="Upload your new sheets">
            <x-slot:content>
                <input type="file" multiple class="absolute opacity-0 top-0 left-0" x-ref="file_upload_input" accept=".xlsx, .csv, .ode" @change="onFilesAdded($el)" />
                <div class="p-2 flex flex-col gap-8">
                    <div class="min-w-[275px]">
                        <div class="shadow-lg shadow-bg-primary-500 bg-primary-50 hover:bg-primary-100 border border-dashed border-primary-400 rounded-md flex flex-col gap-3 justify-center items-center cursor-pointer aspect-video" @click="$refs.file_upload_input.click()">
                            <x-svg.cloud stroke-width="1" stroke="#f59e0b" class="w-24 h-24 mx-auto" fill="none" />
                            <small>Drag and drop your sheets</small>
                            <div class="flex items-center gap-4 w-3/5 sm:w-2/5">
                                <div class="border-b-2 border-primary-900 grow"></div>
                                <small class="font-medium">Or</small>
                                <div class="border-b-2 border-primary-900 grow"></div>
                            </div>
                            <small>Click to browse</small>
                        </div>
                    </div>
                    <small class="text-red-500 font-medium text-center" x-cloak x-show="slides.sheets.error.show" x-text="slides.sheets.error.message"></small>
                    <div x-cloak x-show="slides.sheets.toUpload.length">
                        <h1 class="font-medium mb-2">Selected Files</h1>
                        <div class="flex flex-col gap-2">
                            <template x-for="(file, indx) in slides.sheets.toUpload" :key="indx">
                                <div class="border border-primary-200 shadow shadow-primary-500 rounded-md p-4 flex items-center bg-primary-50">
                                    <div class="flex flex-col gap-1 grow">
                                        <small class="font-medium" x-text="file.name"></small>
                                        <small x-text="file.humanSize"></small>
                                    </div>
                                    <x-svg.cross stroke-width="1.5" stroke="#FFFFFF" class="w-8 h-8 bg-red-500 rounded-full cursor-pointer" fill="none" @click="slides.sheets.toUpload.splice(indx, 1)" />
                                </div>
                            </template>
                        </div>
                    </div>
                    <button class="hover:bg-primary-900 text-white font-medium bg-primary-500 min-w-[125px] py-0.5 px-2 rounded-md ml-auto block" @click="confirmUploadsBtnPressed">Confirm</button>
                </div>
            </x-slot:content>
        </x-modal>

        <x-modal showVar="slides.delete.show" title="Are you sure?">
            <x-slot:content>
                <div class="p-2 flex flex-col gap-8">
                    <p>You are about to delete the following sheet. Please be aware that the action is not reversable:</p>
                    <ul class="p-0">
                        <li><span class="w-[125px] inline-block">Sheet: </span><span x-text="slides.delete.sheet.client_name"></span></li>
                        <li><span class="w-[125px] inline-block">Last Modified: </span><span x-text="slides.delete.sheet.updated_at_human"></span></li>
                    </ul>
                    <button class="hover:bg-primary-900 text-white font-medium bg-primary-500 min-w-[125px] py-0.5 px-2 rounded-md self-center" @click="confirmDeleteBtnPressed">Confirm</button>
                </div>
            </x-slot:content>
        </x-modal>
    </div>

    <script>
        const dashboard = (e) => ({
            sheets: {
                list: [],
                params: {
                    filter: {
                        show: false,
                        org: false
                    },
                    sort: {
                        show: false,
                        value: 'uploaded',
                        asc: true
                    }
                }
            },
            slides: {
                sheets: {
                    show: false,
                    toUpload: [],
                    error: {
                        show: false,
                        message: ''
                    }
                },
                delete: {
                    show: false,
                    sheet: {}
                }
            },
            init() {
                Promise.all([this.retrieveUploadedSheets()]);
            },
            async retrieveUploadedSheets() {
                const {
                    filter,
                    sort
                } = this.sheets.params
                const response = await fetch(route('uploads.index', {
                    filter: filter.org,
                    sort: sort.value,
                    sortAsc: sort.asc
                }));
                const json = await response.json();
                this.sheets.list = json.data;
            },
            onFilesAdded(el) {
                for (const file of el.files) {
                    const indx = this.slides.sheets.toUpload.find((x) => x.name == file.name);
                    if (indx) {
                        Alpine.store('toast').toggle(false, 'File name already exists. If required, please rename this file and try again.');
                        continue;
                    }
                    file.humanSize = `${(file.size / 1000).toFixed(1)} kB`
                    this.slides.sheets.toUpload.push(file);
                }
            },
            async confirmUploadsBtnPressed() {
                this.slides.sheets.error.show = false;
                const formData = new FormData();
                for (const upload of this.slides.sheets.toUpload) {
                    formData.append('uploads[]', upload);
                }
                const response = await fetch(route('uploads.store'), {
                    method: 'post',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': e.csrfToken
                    }
                });
                const json = await response.json();
                if (!response.ok) {
                    Alpine.store('toast').toggle(false, json.message);
                    return;
                }

                for (let i = this.slides.sheets.toUpload.length - 1; i >= 0; i--) {
                    const file = this.slides.sheets.toUpload[i];
                    const item = json.errors.files.findIndex((x) => x == file.name);
                    if (item < 0) {
                        this.slides.sheets.toUpload.splice(i, 1);
                    }
                }

                if (json.errors.files.length) {
                    this.slides.sheets.error.message = json.errors.message;
                    this.slides.sheets.error.show = true;
                }
                await this.retrieveUploadedSheets();
                Alpine.store('toast').toggle(true, json.message);
            },
            async confirmDeleteBtnPressed() {
                const response = await fetch(route('uploads.destroy', {
                    upload: this.slides.delete.sheet
                }), {
                    method: 'delete',
                    body: JSON.stringify(),
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': e.csrfToken
                    }
                });
                const json = await response.json();
                if (!response.ok) {
                    Alpine.store('toast').toggle(false, json.message);
                    return;
                }
                await this.retrieveUploadedSheets();
                this.slides.delete.show = false;
                this.slides.delete.sheet = {};
                Alpine.store('toast').toggle(true, json.message);
            },
            ...e
        })
    </script>
</x-app-layout>