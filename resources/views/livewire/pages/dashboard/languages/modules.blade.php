<?php

use App\Models\Language;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;


new #[Layout('layouts.app')] class extends Component {

    public Language $language;

    public function mount(Language $language): void
    {
        $this->language = $language;
    }

}; ?>

<div>
    <x-slot name="header">
        <x-breadcrumb-holder>
            <x-breadcrumb :href="route('languages-selection')">
                Languages
            </x-breadcrumb>
            <x-breadcrumb :href="route('language', $language)">
                {{ $language->name }}
            </x-breadcrumb>
        </x-breadcrumb-holder>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-950">
                    <div role="list" class="mt-3 grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
                        <a href="{{ route("words.sets", $language) }}" wire:navigate class="col-span-1 grid place-items-center rounded-md shadow-md h-16 text-2xl hover:scale-105 hover:bg-gray-100 duration-500">
                            Words multiple choice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

