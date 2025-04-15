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

    @for($index = 0; $index < $questions->count(); $index++)
        <div class="card bg-base-100 shadow-sm ">
            <div class="p-5 text-lg font-bold">
                {{$index+1}}.
                {{ $questions[$index]->question }}
            </div>

            <div class="card-body">
                <ul>
                    <li>
                        A. {{ $questions[$index]->answer_0 }}

                    </li>
                    <li>
                        B. {{ $questions[$index]->answer_1 }}
                    </li>
                    @if($questions[$index]->answer_2)
                        <li>
                            C. {{ $questions[$index]->answer_2 }}
                        </li>
                    @endif
                    @if($questions[$index]->answer_3)
                        <li>
                            D. {{ $questions[$index]->answer_3 }}
                        </li>
                    @endif
                    @switch($questions[$index]->correct_answer)
                        @case("answer_0")
                            <li>
                                <span class="text-sm font-bold text-gray-500">Réponse correcte : {{ $questions[$index]->answer_0 }}</span>
                            </li>
                            @break
                        @case("answer_1")
                            <li>
                                <span class="text-sm font-bold text-gray-500">Réponse correcte : {{ $questions[$index]->answer_1 }}</span>
                            </li>
                            @break
                        @case("answer_2")
                            <li>
                                <span class="text-sm font-bold text-gray-500">Réponse correcte : {{ $questions[$index]->answer_2 }}</span>
                            </li>
                            @break
                        @case("answer_3")
                            <li>
                                <span class="text-sm font-bold text-gray-500">Réponse correcte : {{ $questions[$index]->answer_3 }}</span>
                            </li>
                            @break

                    @endswitch


                </ul>
            </div>
        </div>




    @endfor


    <div class="card bg-base-100 shadow-sm p-5 mt-5">
        <h1 class="text-lg font-bold">Résultats</h1>

        @foreach($user_bilans as $user_bilan)
            <div class="py-2 text-lg font-bold">

            {{$user_bilan->user->first_name}} {{$user_bilan->user->last_name}} : {{$user_bilan->score}} / {{$qcm->questions()->count()}} ({{round($user_bilan->score / $qcm->questions()->count() * 20, 2)}} / 20)
            <div>
        @endforeach

    </div>


</x-app-layout>
