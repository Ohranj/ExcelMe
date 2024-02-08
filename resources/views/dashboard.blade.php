<x-app-layout>
    <div class="flex flex-col gap-8" x-data="dashboard">
        <button class="hover:bg-primary-900 text-white font-medium bg-primary-500 min-w-[125px] py-0.5 px-2 rounded-md ml-auto block" @click="slides.sheets.show = true">Upload</button>
        <h1>Recent Files</h1>

        <x-modal showVar="slides.sheets.show" title="Upload your new sheets">
            <x-slot:content>
                <input type="file" multiple class="absolute opacity-0 top-0 left-0" x-ref="file_upload_input" accept=".xlsx, .csv, .ode" @change="onFilesAdded($el)" />
                <div class="p-2 flex flex-col gap-8">
                    <div class="min-w-[275px]">
                        <div class="shadow-lg shadow-bg-primary-500 bg-primary-50 border border-dashed border-primary-400 rounded-md flex flex-col gap-3 justify-center items-center cursor-pointer aspect-video" @click="$refs.file_upload_input.click()">
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
                    <div x-cloak x-show="slides.sheets.toUpload.length">
                        <h1 class="font-medium mb-2">Selected Files</h1>
                        <div class="flex flex-col gap-2">
                            <template x-for="file of slides.sheets.toUpload">
                                <div class="border border-primary-500 rounded-md p-4 flex items-center">
                                    <div class="flex flex-col gap-1 grow">
                                        <small x-text="file.name"></small>
                                        <small x-text="file.size"></small>
                                    </div>
                                    <x-svg.cross stroke-width="1.5" stroke="#FFFFFF" class="w-8 h-8 bg-red-500 rounded-full cursor-pointer" fill="none" />
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </x-slot:content>
        </x-modal>
    </div>

    <script>
        const dashboard = () => ({
            slides: {
                sheets: {
                    show: false,
                    toUpload: []
                }
            },
            onFilesAdded(el) {
                for (const file of el.files) {
                    this.slides.sheets.toUpload.push(file);
                    console.log(file);
                }
            }
        })
    </script>
</x-app-layout>