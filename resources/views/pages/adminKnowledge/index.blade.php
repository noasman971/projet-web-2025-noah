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
            {{$index+1}}.
            {{ $questions[$index]->question }}
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


                </ul>

            </div>



    @endfor



</x-app-layout>
