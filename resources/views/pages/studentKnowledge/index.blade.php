<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center text-sm font-normal">
            <a href="{{route('knowledge.index')}}">
                <i class="ki-filled ki-left-square text-3xl mr-5"></i>
            </a>
            <span class="text-gray-700">
                {{ __('Bilan de ') . $qcm->name }}
            </span>
        </h1>


    </x-slot>
    <div class="flex gap-5">
        <div>
            <span class="countdown font-mono text-4xl">
              <span id="hours" style="--value:10;" aria-live="polite" aria-label="10">10</span>
            </span>
            hours
        </div>
        <div>
            <span class="countdown font-mono text-4xl">
              <span id="minutes" style="--value:24;" aria-live="polite" aria-label="24">24</span>
            </span>
            min
        </div>
        <div>
            <span class="countdown font-mono text-4xl">
              <span id="secondes" style="--value:59;" aria-live="polite" aria-label="59">59</span>
            </span>
            sec
        </div>
    </div>

    <script>
        const hours = document.getElementById('hours');
        const minutes = document.getElementById('minutes');
        const seconds = document.getElementById('secondes');
        let countdown = hours.innerText * 3600 + minutes.innerText * 60 + seconds.innerText;
    </script>



<form method="GET"  action="{{route('studentKnowledge.index', Crypt::encrypt($qcm->id))}}">
    <div class="card bg-base-100 shadow-sm ">
        <div class="p-5 text-lg font-bold">
            {{$i+1}}.
            {{ $questions[$i]->question }}
        </div>

        <input type="hidden" name="questionIndex" value="{{$i}}">
        <input type="hidden" name="qcmnote" value="{{0}}">
        <div class="card-body">
            <ul>
                <li>
                    <x-forms.primary-button> A. {{ $questions[$i]->answer_0 }}</x-forms.primary-button>

                </li>
                <li>
                    <x-forms.primary-button> B. {{ $questions[$i]->answer_1 }}</x-forms.primary-button>
                </li>
                @if($questions[$i]->answer_2)
                    <li>
                        <x-forms.primary-button>C. {{ $questions[$i]->answer_2 }}</x-forms.primary-button>
                    </li>
                @endif
                @if($questions[$i]->answer_3)
                    <li>
                        <x-forms.primary-button>D. {{ $questions[$i]->answer_3 }}</x-forms.primary-button>
                    </li>

                @endif
                <li>
                    <span class="text-sm font-bold text-gray-500">Réponse correcte : {{ $questions[$i]->correct_answer }}</span>
                </li>

            </ul>

        </div>
    </div>
</form>







{{--@for($index = 0; $index < $questions->count(); $index++)
    <div class="card bg-base-100 shadow-sm ">
        <div class="p-5 text-lg font-bold">
            {{$index+1}}.
            {{ $questions[$index]->question }}
        </div>

        <div class="card-body">
            <ul>
                <li>
                    <x-forms.primary-button> A. {{ $questions[$index]->answer_0 }}</x-forms.primary-button>

                </li>
                <li>
                    <x-forms.primary-button> B. {{ $questions[$index]->answer_1 }}</x-forms.primary-button>
                </li>
                @if($questions[$index]->answer_2)
                    <li>
                        <x-forms.primary-button>C. {{ $questions[$index]->answer_2 }}</x-forms.primary-button>
                    </li>
                @endif
                @if($questions[$index]->answer_3)
                    <li>
                        <x-forms.primary-button>D. {{ $questions[$index]->answer_3 }}</x-forms.primary-button>
                    </li>
                    <li>
                        <span class="text-sm font-bold text-gray-500">Réponse correcte : {{ $questions[$index]->correct_answer }}</span>
                    </li>
                @endif


            </ul>

        </div>



@endfor--}}



</x-app-layout>
