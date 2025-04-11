<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Bilans de connaissances') }}
            </span>
        </h1>
    </x-slot>



    <form method="POST" action="{{route('knowledge.qcm')}}" class="card-body flex flex-col gap-5 p-10">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger text-red-400">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <x-forms.input label="{{ __('Nom du bilan') }}" name="langage"
                       type="text" placeholder="Bilan de connaissance"
        />
        <x-forms.input label="{{ __('Nombre de questions') }}" name="number" type="number" min="1" max="30" />

        <x-forms.primary-button>Valider</x-forms.primary-button>
    </form>



    <div class="flex flex-wrap gap-5">
        @foreach($qcm as $qcms)
        <div class="card bg-base-100 w-96 shadow-sm ">
            <figure>
                <img
                    src="{{$qcms->link}}"
                   alt="{{$qcms->name}}" />
            </figure>
            <div class="card-body">
                <h2 class="card-title">{{$qcms->name}}</h2>
                <p>
                    {{ $qcms->questions->count() }} questions
                </p>



                <div class="card-actions justify-end">
                    <button class="btn btn-primary">Voir</button>
                </div>
            </div>
        </div>


        @endforeach
    </div>


</x-app-layout>
