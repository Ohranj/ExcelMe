<x-app-layout>
    <div class="flex flex-col gap-12" x-data="dashboard({ csrfToken: '{{ csrf_token() }}' })">
        <button class="hover:bg-primary-900 text-white font-medium bg-primary-500 min-w-[125px] py-0.5 px-2 rounded-md ml-auto block" @click="slides.sheets.show = true">Upload</button>

        <div>
            <div class="flex items-center gap-8">
                <small class="grow text-primary-600 flex items-center gap-1 relative">Filter: <span class="cursor-pointer text-primary-900" @click="files.params.filter.show = true">All Sheets</span>
                    <x-svg.chevron fill="none" stroke-width="2.5" stroke="currentColor" class="w-3 h-3" />
                    <div x-cloak x-show="files.params.filter.show" x-collapse class="bg-primary-700 rounded absolute top-6 min-w-[150px]" @click.away="files.params.filter.show = false">
                        <ul class="flex flex-col gap-1 text-primary-200 p-4">
                            <li>My sheets</li>
                            <li>My Organisation</li>
                            <li class="mt-2">All sheets</li>
                        </ul>
                    </div>
                </small>
                <small class="text-primary-600 flex items-center gap-1 relative">Sort: <span class="cursor-pointer text-primary-900" @click="files.params.sort.show = true">Last Modified</span>
                    <x-svg.chevron fill="none" stroke-width="2.5" stroke="currentColor" class="w-3 h-3" />
                    <div x-cloak x-show="files.params.sort.show" x-collapse class="bg-primary-700 rounded absolute top-6 min-w-[150px]" @click.away="files.params.sort.show = false">
                        <ul class="flex flex-col gap-1 text-primary-200 p-4">
                            <li>Alphabetical</li>
                            <li>Date created</li>
                            <li class="mt-2">Last modified</li>
                        </ul>
                    </div>
                </small>
                <small class="flex items-center gap-1">
                    <x-svg.card-layout fill="none" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 cursor-pointer" />
                    <x-svg.card-layout fill="none" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 cursor-pointer" />
                </small>
            </div>
            <div class="mt-4">
                here
            </div>
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
    </div>

    <script>
        const dashboard = (e) => ({
            files: {
                list: [],
                params: {
                    filter: {
                        show: false,
                        value: 'all'
                    },
                    sort: {
                        show: false,
                        value: 'modified',
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
                }
            },
            init() {
                this.retrieveUploadedFiles();
            },
            async retrieveUploadedFiles() {
                const response = await fetch(route('uploads.index'));
                const json = await response.json();
                this.files.list = json.data;
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
                Alpine.store('toast').toggle(true, json.message);
            },
            ...e
        })
    </script>
</x-app-layout>