@props([
'metaTitle' => '',
'index' => '',
'umami' => true,
'siteTitle',
'active' => '',
])

<x-layouts.base :metaTitle="$metaTitle" :index="$index" :umami="$umami">

    <div class="min-h-full">
        <div class="py-10">
{{--            <header>--}}
{{--                <div class="mx-auto px-4 sm:px-6 lg:px-8">--}}
{{--                    <h1 class="text-3xl font-bold leading-tight text-gray-900">--}}
{{--                        {{ $siteTitle ?? $metaTitle }}--}}
{{--                    </h1>--}}
{{--                </div>--}}
{{--            </header>--}}
            <main>
                <div class="mx-auto sm:px-6 lg:px-8">
                    <div class="px-4 sm:px-0">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>

</x-layouts.base>
