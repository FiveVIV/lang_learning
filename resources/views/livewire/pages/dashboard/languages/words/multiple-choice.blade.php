<?php

use App\Models\Language;
use App\Models\Question;
use App\Models\Set;
use App\Models\Word;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {

    public Language $language;

    public Set $set;

    public Collection $questions;

    public Question $currentQuestion;

    public Collection $answerSheet;

    public int $totalQuestionCount;

    public int $currentQuestionCount = 0;

    public ?bool $answeredCorrectly = null;


    public function mount(Language $language, Set $set): void
    {
        $this->language = $language;
        $this->set = $set;
        $this->questions = $set->questions()->with("correctAnswer")->get();
        $this->totalQuestionCount = $this->questions->count();

        $this->setCurrentQuestion($this->questions->first());
    }



    public function userAnsweredQuestion(Question $question)
    {
        return auth()->user()->answeredQuestions()->where('question_id', $question->id)->first();
    }

    public function totalAnsweredQuestions(): int
    {
        return auth()->user()->answeredQuestions()->wherePivotIn("question_id", $this->questions->pluck("id"))->get()->count();
    }

    public function totalCorrectQuestions(): int
    {
        return auth()->user()->answeredQuestions()->wherePivotIn("question_id", $this->questions->pluck("id"))->wherePivot("correctly_answered", true)->get()->count();
    }

    public function totalIncorrectQuestions(): int
    {
        return auth()->user()->answeredQuestions()->wherePivotIn("question_id", $this->questions->pluck("id"))->wherePivot("correctly_answered", false)->get()->count();
    }


    public function setCurrentQuestion(Question $question): void
    {
        $userAnsweredQuestion = $this->userAnsweredQuestion($question);

        if ($userAnsweredQuestion) {
            $this->answeredCorrectly = $userAnsweredQuestion->pivot->correctly_answered;
        }

        $this->currentQuestion = $question;
        $this->answerSheet = $this->language->words()->get()->random(3);
        $this->answerSheet->push($this->currentQuestion->correctAnswer);
        $this->answerSheet = $this->answerSheet->shuffle();

    }

    public function previousQuestion(): void
    {
        if ($this->currentQuestionCount > 0) {
            $this->answeredCorrectly = null;
            if (!$this->answeredCorrectly) {

                $this->currentQuestionCount--;

                $this->setCurrentQuestion($this->questions[$this->currentQuestionCount]);
            }
        }
    }

    public function nextQuestion(): void
    {
        if ($this->currentQuestionCount < $this->totalQuestionCount - 1) {
            $this->answeredCorrectly = null;

            $this->currentQuestionCount++;

            $this->setCurrentQuestion($this->questions[$this->currentQuestionCount]);
        }
    }

    public function answerQuestion(Word $word): void
    {
        $userAnsweredQuestion = $this->userAnsweredQuestion($this->currentQuestion);

        if ($word == $this->currentQuestion->correctAnswer) {

            $this->answeredCorrectly = true;
        } else {
            $this->answeredCorrectly = false;
        }
    }

    public function selectQuestion(Question $question): void
    {
        $this->setCurrentQuestion($question);
        $this->currentQuestionCount = $this->questions->search(fn($q) => $q->id === $question->id);
    }

    public function hasNoPrevious(): bool
    {
        return $this->questions->search($this->currentQuestion) === 0;
    }

    public function hasNoNext(): bool
    {
        return $this->questions->search($this->currentQuestion) === $this->totalQuestionCount - 1;
    }

    public function correctAnswer(): Word
    {
        return $this->correctAnswer; // Return the correct answer stored in the property
    }

};

?>

<div x-data="{ questionDrawer: false }">
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $language->name . __(' - Words - ') . $set->name }}
            </h2>
        </div>

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
                                        {{ $currentQuestion->correctAnswer->target_word }}
                                    </p>
                                </div>
                                <div class="flex flex-col space-y-3">
                                    @foreach($answerSheet as $answer)

{{--                                                <button wire:click="answerQuestion({{ $answer['question'] }})"
                                                        type="button"
                                                        class="rounded-md bg-green-400 px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300"
                                                        disabled>
                                                    {{ $answer['question']->english_word }}
                                                </button>--}}

                                            <button wire:click="answerQuestion({{ $answer }})" type="button"
                                                    class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                                {{ $answer->english_word }}
                                            </button>
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

                                    <h1 class="text-2xl font-bold">Correct! {{ $currentQuestion->correctAnswer->target_word }}
                                        means {{ $currentQuestion->correctAnswer->english_word }}</h1>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-20 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>

                                    <h1 class="text-2xl font-bold">Incorrect! {{ $currentQuestion->correctAnswer->target_word }}
                                        means {{ $currentQuestion->correctAnswer->english_word }}</h1>

                                @endif


                            </div>
                        @endif

                    </div>
                    <div class="flex justify-center my-12 relative">
                        <span class="isolate inline-flex rounded-md shadow-sm">
                            <button @disabled($this->hasNoPrevious()) type="button" wire:click="previousQuestion()" class="relative inline-flex items-center rounded-l-md bg-white px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 focus:z-10 {{ $this->hasNoPrevious() ? "cursor-no-drop" : "hover:bg-gray-50" }}">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                  <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <button @click="questionDrawer = !questionDrawer" type="button" class="relative -ml-px inline-flex items-center bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10">
                                Question {{ $currentQuestionCount + 1 . __("/") . $totalQuestionCount }}
                            </button>

                            <button @disabled($this->hasNoNext())  type="button" wire:click="nextQuestion()" class="relative -ml-px inline-flex items-center rounded-r-md bg-white px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 focus:z-10 {{ $this->hasNoNext() ? "cursor-no-drop" : "hover:bg-gray-50" }}">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                  <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </span>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="relative z-10" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <!-- Background backdrop, show/hide based on slide-over state. -->
        <div
            x-show="questionDrawer"
            x-transition.opacity.duration.300ms
            class="fixed inset-0 bg-gray-400/25"
        ></div>

        <div x-show="questionDrawer" class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <!-- Slide-over panel, show/hide based on slide-over state. -->
                    <div
                        @click.outside="questionDrawer = false"
                        x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                        x-transition:enter-start="translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="translate-x-full"
                        x-show="questionDrawer"
                        class="pointer-events-auto w-screen max-w-md"
                    >
                        <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
                            <div class="px-4 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-base font-semibold leading-6 text-gray-900" id="slide-over-title">Questions</h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button
                                            @click="questionDrawer = false"
                                            type="button"
                                            class="relative rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            <span class="absolute -inset-2.5"></span>
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="relative mt-6 flex-1 px-4 sm:px-6 space-y-3">
                                <div class="p-4 bg-gray-100 border-b">
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div>Total Questions:</div>
                                        <div class="text-right font-semibold">{{ $questions->count() }}</div>
                                        <div>Answered:</div>
                                        <div class="text-right font-semibold">{{ $this->totalAnsweredQuestions() }}</div>
                                        <div>Correct:</div>
                                        <div class="text-right font-semibold text-green-600">{{ $this->totalCorrectQuestions() }}</div>
                                        <div>Incorrect:</div>
                                        <div class="text-right font-semibold text-red-600">{{ $this->totalIncorrectQuestions() }}</div>
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-3">
                                    @foreach($questions as $question)
                                        @php
                                            $isSelected = $question->id == $currentQuestion->id;
                                            $buttonClass = $isSelected ? "bg-sky-500" : "";
                                        @endphp

                                        @if ($userAnswered = $this->userAnsweredQuestion($question))
                                            @if ($userAnswered->pivot->correctly_answered === 1)
                                                <button wire:click="selectQuestion({{ $question }})" type="button" class="{{ $buttonClass ?: 'bg-green-600 hover:bg-green-700' }} ring-gray-300 ring-1 inline-flex items-center justify-between rounded-md px-3.5 py-2.5 text-sm font-semibold shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                                                    Question {{ $loop->iteration }}
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="-mr-0.5 h-5 w-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                    </svg>
                                                </button>
                                            @else
                                                <button wire:click="selectQuestion({{ $question }})" type="button" class="{{ $buttonClass ?: 'bg-red-500 hover:bg-red-600' }} ring-gray-300 ring-1 inline-flex items-center justify-between rounded-md px-3.5 py-2.5 text-sm font-semibold shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                                                    Question {{ $loop->iteration }}
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="-mr-0.5 h-5 w-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        @else
                                            <button wire:click="selectQuestion({{ $question }})" type="button" class="{{ $buttonClass ?: 'hover:bg-gray-50' }} ring-gray-300 ring-1 inline-flex items-center justify-between rounded-md px-3.5 py-2.5 text-sm font-semibold shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                                                Question {{ $loop->iteration }}
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="-mr-0.5 h-5 w-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
