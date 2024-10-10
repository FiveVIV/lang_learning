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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Languages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-language-list>
                        @foreach($languages as $language)
                            <x-language-list-element :href="route('language', $language)">
                                <x-slot:code>
                                    {{ $language->code }}
                                </x-slot:code>
                                <x-slot:header>
                                    {{ $language->name }}
                                </x-slot:header>
                            </x-language-list-element>
                        @endforeach
                    </x-language-list>
                </div>
            </div>
        </div>
    </div>
</div>
