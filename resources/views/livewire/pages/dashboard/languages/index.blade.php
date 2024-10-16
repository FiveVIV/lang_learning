<?php

use App\Models\Language;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;


new #[Layout('layouts.app')] class extends Component {

    public $languages;

    public function mount(): void
    {
        $this->languages = Language::get();
    }


}; ?>

<div>
    <x-slot name="header">
        <x-breadcrumb-holder>
            <x-breadcrumb :href="route('languages-selection')">
                Languages
            </x-breadcrumb>
        </x-breadcrumb-holder>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-teal-600 mr-2">
                            <circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/>
                        </svg>
                        <h1 class="text-3xl font-bold text-teal-800">Choose Your Language</h1>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($languages as $language)
                            <a href="{{ route('language', $language) }}" class="rounded-md ring-1 ring-gray-400 shadow-sm h-auto py-4 px-6 flex flex-col items-center justify-center transition-all duration-300 hover:bg-teal-50">
                                <span class="text-lg font-semibold">{{ $language->name }}</span>
                                <span class="text-sm text-muted-foreground mt-1">{{ $language->native_name }}</span>
                            </a>
                        @endforeach
                    </div>


{{--                    <x-language-list>
                        @foreach($languages as $language)
                            <x-language-list-element :>
                                <x-slot:code>
                                    {{ $language->code }}
                                </x-slot:code>
                                <x-slot:header>
                                    {{ $language->name }}
                                </x-slot:header>
                            </x-language-list-element>
                        @endforeach
                    </x-language-list>--}}
                </div>
            </div>
        </div>
    </div>
</div>
