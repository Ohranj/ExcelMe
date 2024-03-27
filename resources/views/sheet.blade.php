<x-app-layout>
    @routes(['dashboard'])
    <div x-data="sheet({ upload: '{{ $upload }}', totalRows: '{{ $sheet['totalRows'] }}' })">
        <div class="flex flex-col gap-4">
            <div>
                <h1 class="capitalize text-3xl tracking-wide"> {{ $sheet['name'] }}</h1>
                <div class="flex justify-between mt-4 items-end">
                    <div>
                        <small>Sheet Overview:</small>
                        <ul class="text-xs list-disc pl-8">
                            <li>Initially Created: {{ $sheet['created'] }}</li>
                            <li>Total Rows: {{ $sheet['totalRows'] }}</li>
                        </ul>
                    </div>
                    <div class="flex gap-2 items-center">
                        <button class="bg-secondary-600 text-white w-[105px] rounded-md py-0.5">Export</button>
                        <button class="bg-primary-600 text-white w-[105px] rounded-md py-0.5">Configure</button>
                    </div>
                </div>
            </div>
            <select class="rounded-md ml-auto py-0.5" @click="sheet.fetchRows.start = 2; sheet.fetchRows.end = Number($el.value); sheet.loading.initialRender = true; sheet.rows.length = 0; await retrieveUpload(); sheet.loading.initialRender = false">
                <option value="500">Show chunks of 500 results</option>
                <option value="1000">Show chunks of 1000 results</option>
                <option value="2500">Show chunks of 2500 results</option>
                <option value="5000">Show chunks of 5000 results</option>
                <option value="10000">Show chunks of 10000 results</option>
                <option :value="{{ $sheet['totalRows'] }}">Show All</option>
            </select>
            <div x-cloak x-show="!sheet.loading.initialRender">
                <div class="border rounded shadow">
                    <div class="grid gap-2 bg-primary-700 text-white p-1 rounded-t" :style="'grid-template-columns: repeat(' + sheet.columns.length + ', 1fr)'">
                        <template x-for="column in sheet.columns">
                            <small class="px-0.5 font-semibold uppercase tracking-wide" x-text="column"></small>
                        </template>
                    </div>
                    <template x-for="row in sheet.rows">
                        <div class="hover:bg-primary-200 odd:bg-primary-100 grid gap-2 cursor-pointer p-0.5" :style="'grid-template-columns: repeat(' + sheet.columns.length + ', 1fr)'">
                            <template x-for="i in sheet.columns.length">
                                <small class="overflow-hidden text-ellipsis whitespace-nowrap" contenteditable="true" x-text="row[i - 1]"></small>
                            </template>
                        </div>
                    </template>
                </div>
                <small class="opacity-0" x-ref="observer">Load More</small>
            </div>
            <div x-show="sheet.loading.processing" x-transition:leave.opacity.duration.750ms class="grid justify-center gap-4 fixed top-[50%] left-0 right-0">
                <x-svg.spinner class="mx-auto w-16 h-16 animate-spin text-amber-500" fill="none" />
            </div>
        </div>
    </div>

    <script>
        const sheet = (e) => ({
            urlParams: {},
            sheet: {
                rows: [],
                columns: [],
                loading: {
                    initialRender: true,
                    processing: false
                },
                fetchRows: {
                    start: 2,
                    end: 500
                }
            },
            async init() {
                this.urlParams = route().params;
                await this.retrieveUpload();
                this.sheet.loading.initialRender = false;
                this.observeTableBottom()
            },
            async retrieveUpload() {
                this.sheet.loading.processing = true;
                const response = await fetch(route('uploads.edit', {
                    upload: this.urlParams.upload,
                    startRow: this.sheet.fetchRows.start,
                    endRow: this.sheet.fetchRows.end
                }));
                const json = await response.json();
                this.sheet.columns = json.data.columns;
                this.sheet.rows = this.sheet.rows.concat(json.data.chunkedRows);
                this.sheet.loading.processing = false;
            },
            observeTableBottom() {
                const options = {
                    root: null,
                    rootMargin: '0px',
                    threshold: 0.25
                };

                const observer = new IntersectionObserver((entries, observer) => {
                    if (this.sheet.fetchRows.end >= e.totalRows) {
                        return
                    }

                    if (!entries.some((x) => x.isIntersecting)) {
                        return;
                    }

                    this.sheet.fetchRows.start = this.sheet.fetchRows.end + 1;
                    this.sheet.fetchRows.end *= 2;
                    this.retrieveUpload()
                }, options);
                observer.observe(this.$refs.observer);
            },
            ...e
        })
    </script>
</x-app-layout>