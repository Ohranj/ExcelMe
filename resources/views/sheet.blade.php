<x-app-layout>
    @routes(['dashboard'])
    <div x-data="sheet({ upload: '{{ $upload }}' })">
        <div class="border">
            <p class="capitalize"> {{ $sheet['name'] }}</p>
            <div class="grid gap-1" :style="'grid-template-columns: repeat(' + sheet.columns.length + ', 1fr)'">
                <template x-for="column in sheet.columns">
                    <small class="font-semibold border-r" x-text="column"></small>
                </template>
            </div>
            <p x-show="sheet.loading">LOADING</p>
            <template x-for="row in sheet.rows">
                <div class="grid gap-1" :style="'grid-template-columns: repeat(' + sheet.columns.length + ', 1fr)'">
                    <template x-for="i in sheet.columns.length">
                        <small contenteditable="true" x-text="row[i - 1]"></small>
                    </template>
                </div>
            </template>
        </div>
    </div>

    <script>
        const sheet = () => ({
            urlParams: {},
            sheet: {
                rows: [],
                columns: [],
                loading: true
            },
            init() {
                this.urlParams = route().params;
                this.retrieveUpload();
            },
            async retrieveUpload() {
                this.sheet.loading = true;
                const response = await fetch(route('uploads.edit', {
                    upload: this.urlParams.upload
                }));
                const json = await response.json();
                this.sheet.columns = json.data.columns;
                this.sheet.rows = json.data.chunkedRows;
                console.log(json.data);
                this.sheet.loading = false;
            }
        })
    </script>
</x-app-layout>