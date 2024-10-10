<li class="col-span-1 flex rounded-md shadow-sm">
    <div class="flex w-16 flex-shrink-0 items-center justify-center rounded-l-md bg-pink-600 text-sm font-medium text-white">{{ $code }}</div>
    <div class="flex flex-1 items-center justify-between truncate rounded-r-md border-b border-r border-t border-gray-200 bg-white">
        <div class="flex-1 truncate px-4 py-2 text-sm">
            <a {{ $attributes->merge() }} class="font-medium text-gray-900 hover:text-gray-600">{{ $header }}</a>
            <p class="text-gray-500"></p>
        </div>
    </div>
</li>
