<?php

use App\Models\Language;
use App\Models\Set;
use App\Models\Word;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {

    public Language $language;

    public Set $set;

    public Illuminate\Database\Eloquent\Collection $questions;

    public Collection $answerSheet;

    public ?bool $answeredCorrectly = null;

    public ?Word $correctAnswer = null;


    public function mount(Language $language, Set $set): void
    {
        $this->language = $language;
        $this->set = $set;
        $this->questions = $set->questions()->get();

        dd($this->questions);

        $this->questions = $this->answerSheet = collect();
        $this->newQuestion();
    }


    public function newQuestion(): void
    {
        $questions = $this->language->words()->take(10)->get()->random(4);

        $selectedCorrectAnswer = $questions->random();

        $answerSheet = $questions->map(function ($question) use ($selectedCorrectAnswer) {
            return [
                'question' => $question,
                'is_correct' => $question === $selectedCorrectAnswer
            ];
        });

        $answerSheet->put('given_answer', null);

        $this->questions->push($answerSheet);

        $this->setQuestion($answerSheet);

    }

    public function setQuestion(Collection $answerSheet): void
    {
        $this->answerSheet = $answerSheet;
        $this->correctAnswer = $answerSheet->firstWhere('is_correct', true)['question']; // Access the question
    }

    public function answerQuestion(Word $word): void
    {
        if ($word->id === $this->correctAnswer->id) {
            $this->answeredCorrectly = true;
        } else {
            $this->answeredCorrectly = false;
        }

        $this->questions[$this->questions->search($this->answerSheet)]["given_answer"] = $this->answerSheet["given_answer"] = $word;
    }


    public function previousQuestion(): void
    {
        $this->answeredCorrectly = null;

        if ($this->questions->first() != $this->answerSheet) {
            $this->setQuestion($this->questions[$this->questions->search($this->answerSheet) - 1]);
        }
    }

    public function nextQuestion(): void
    {

        $this->answeredCorrectly = null;

        if ($this->questions->last() == $this->answerSheet) {
            $this->newQuestion();
        } else {
            $currentIndex = $this->questions->search($this->answerSheet);
            $this->setQuestion($this->questions[$currentIndex + 1]);
        }
    }

    public function hasPrevious(): bool
    {
        return $this->questions->search($this->answerSheet) != 0;
    }

    public function correctAnswer(): Word
    {
        return $this->correctAnswer; // Return the correct answer stored in the property
    }

};

?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $language->name . __(' - Words') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-center items-center h-full flex-col">
                        @if ($answeredCorrectly === null)
                            <div class="w-64 space-y-4">
                                <div class="text-xl font-bold">
                                    <p>
                                        What is the translation of:
                                    </p>
                                    <p>
                                        {{ $this->correctAnswer->target_word }}
                                    </p>
                                </div>
                                <div class="flex flex-col space-y-3">
                                    @foreach($answerSheet->except('given_answer') as $answer)
                                        @if ($answerSheet["given_answer"])
                                            @if ($answer["question"] == $answerSheet["given_answer"])
                                                <button wire:click="answerQuestion({{ $answer['question'] }})"
                                                        type="button"
                                                        class="rounded-md bg-sky-400 px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300"
                                                        disabled>
                                                    {{ $answer['question']->english_word }}
                                                </button>
                                            @elseif ($answer["question"] == $correctAnswer)
                                                <button wire:click="answerQuestion({{ $answer['question'] }})"
                                                        type="button"
                                                        class="rounded-md bg-green-400 px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300"
                                                        disabled>
                                                    {{ $answer['question']->english_word }}
                                                </button>
                                            @else
                                                <button wire:click="answerQuestion({{ $answer['question'] }})"
                                                        type="button"
                                                        class="rounded-md bg-red-400 px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300"
                                                        disabled>
                                                    {{ $answer['question']->english_word }}
                                                </button>
                                            @endif
                                        @else
                                            <button wire:click="answerQuestion({{ $answer['question'] }})" type="button"
                                                    class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                                {{ $answer['question']->english_word }}
                                            </button>
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        @else
                            <div class="w-64 space-y-4 flex justify-center items-center flex-col text-center">
                                @if ($answeredCorrectly === true)
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-20 text-green-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>

                                    <h1 class="text-2xl font-bold">Correct! {{ $this->correctAnswer()->target_word }}
                                        means {{ $this->correctAnswer()->english_word }}</h1>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-20 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>

                                    <h1 class="text-2xl font-bold">Incorrect! {{ $this->correctAnswer()->target_word }}
                                        means {{ $this->correctAnswer()->english_word }}</h1>

                                @endif


                            </div>
                        @endif

                    </div>
                    <div class="flex justify-between my-12 relative">
                        @if ($this->hasPrevious())
                            <button wire:click="previousQuestion()" type="button"
                                    class="absolute left-0 inline-flex items-center gap-x-2 rounded-md bg-sky-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="-mr-0.5 h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                                </svg>
                                Previous question
                            </button>
                        @endif

                        {{-- Check if there are more questions to show --}}
                        <button wire:click="nextQuestion()" type="button"
                                class="absolute right-0 inline-flex items-center gap-x-2 rounded-md bg-sky-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600">
                            Next question
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="-mr-0.5 h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
