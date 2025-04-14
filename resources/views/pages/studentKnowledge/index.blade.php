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
    <div>
        {{$note}}
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
            <input type="hidden" name="qcmnote" value="{{$note}}">
            <input type="hidden" name="answer" value="">
            <div class="card-body">
                <ul>
                    <li>
                        <x-forms.primary-button id="answer_0" onclick="const answer = this.id; setAnswer(answer);"> A. {{ $questions[$i]->answer_0 }}</x-forms.primary-button>

                    </li>
                    <li>
                        <x-forms.primary-button id="answer_1"> B. {{ $questions[$i]->answer_1 }}</x-forms.primary-button>
                    </li>
                    @if($questions[$i]->answer_2)
                        <li>
                            <x-forms.primary-button id="answer_2">C. {{ $questions[$i]->answer_2 }}</x-forms.primary-button>
                        </li>
                    @endif
                    @if($questions[$i]->answer_3)
                        <li>
                            <x-forms.primary-button id="answer_3" onclick="const answer = this.id; setAnswer(answer);">
                            D. {{ $questions[$i]->answer_3 }}
                            </x-forms.primary-button>
                        </li>

                    @endif
                    <li>
                        <span class="text-sm font-bold text-gray-500">Réponse correcte : {{ $questions[$i]->correct_answer }}</span>
                    </li>

                </ul>

            </div>
        </div>
    </form>
    <script>
        function setAnswer(answer) {
            const hiddenInput = document.querySelector('input[name="answer"]');
            hiddenInput.value = answer;
        }

    </script>







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
