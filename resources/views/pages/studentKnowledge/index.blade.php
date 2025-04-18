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





    <form method="GET" id="create" action="{{route('studentKnowledge.index', Crypt::encrypt($qcm->id))}}">
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
                        <x-forms.primary-button id="answer_1" onclick="const answer = this.id; setAnswer(answer);"> B. {{ $questions[$i]->answer_1 }}</x-forms.primary-button>
                    </li>
                    @if($questions[$i]->answer_2)
                        <li>
                            <x-forms.primary-button id="answer_2" onclick="const answer = this.id; setAnswer(answer);">C. {{ $questions[$i]->answer_2 }}</x-forms.primary-button>
                        </li>
                    @endif
                    @if($questions[$i]->answer_3)
                        <li>
                            <x-forms.primary-button id="answer_3" onclick="const answer = this.id; setAnswer(answer);">
                            D. {{ $questions[$i]->answer_3 }}
                            </x-forms.primary-button>
                        </li>

                    @endif




                </ul>

            </div>
        </div>
        <span class="loading loading-dots loading-xl mx-auto hidden"></span>

    </form>



</x-app-layout>
