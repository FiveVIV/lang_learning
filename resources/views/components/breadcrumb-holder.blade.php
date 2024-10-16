<nav class="flex" aria-label="Breadcrumb">
    <ol role="list" class="flex space-x-4 rounded-md bg-white px-6 shadow">
        <li class="flex">
            <div class="flex items-center">
                <a href="{{ route("dashboard") }}" class="text-gray-400 hover:text-gray-500">
                    <svg class="size-6 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Home</span>
                </a>
            </div>
        </li>
        {{ $slot }}
    </ol>
</nav>
