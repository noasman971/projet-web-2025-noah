<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Bilans de connaissances') }}
            </span>
        </h1>
    </x-slot>


    @can('viewAdmin', \App\Models\CohortsBilans::class)

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" id="create_qcm" action="{{route('knowledge.qcm')}}" class="card-body flex flex-col gap-5 p-10">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
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
            <x-forms.input label="{{ __('Nombre de réponse') }}" name="response" type="number" min="2" max="4" />

            <x-forms.primary-button id="bilan_submit">Créér le bilan</x-forms.primary-button>

            <span class="loading loading-dots loading-xl mx-auto hidden"></span>


        </form>
        <script>
            const form = document.getElementById("create_qcm");
            const loading = document.querySelector('.loading');
            const submitButton = document.getElementById("bilan_submit");

            form.addEventListener('submit', function() {
                loading.classList.remove('hidden');
                submitButton.setAttribute('disabled', true);
            });
        </script>
    @endcan





    <div class="flex flex-wrap gap-5">
        @foreach($qcm as $qcms)
            <div class="card bg-base-100 w-96 shadow-sm ">
                <figure>
                    <img class="object-cover max-h-44"
                        src="{{$qcms->link}}"
                       alt="{{$qcms->name}} logo" />
                </figure>
                <div class="card-body">
                    <h2 class="card-title">{{$qcms->name}}</h2>
                    @if($qcms->questions)
                        <p>
                            {{ $qcms->questions->count() }} questions
                        </p>
                    @endif

                    @can('viewAdmin', \App\Models\CohortsBilans::class)

                        <div class="card-actions justify-start">
                            <form method="post" action="{{route('knowledge.update', Crypt::encrypt($qcms->id))}}">
                            @csrf
                            @method('PUT')

                            <x-forms.dropdown label="Promotions" name="action" class="pr-10" onchange="this.form.submit()">
                                <option value="" {{ is_null($qcms->cohort_id) ? 'selected' : '' }}>Aucun</option>
                                @foreach($cohort as $cohorts)
                                    <option value="{{$cohorts->id}}" {{$qcms->cohort_id == $cohorts->id ? 'selected' : ''}}>{{$cohorts->name}}</option>
                                @endforeach
                            </x-forms.dropdown>
                            </form>

                        </div>

                        <a href="{{route('adminKnowledge.index', Crypt::encrypt($qcms->id))}}">
                            <div class="card-actions justify-end">
                                <button class="btn btn-primary">Voir</button>
                            </div>
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\CohortsBilans::class)
                        @if($user_bilan->bilan_id != $qcms->id)
                            <a href="{{route('studentKnowledge.index', Crypt::encrypt($qcms->id))}}">
                                <div class="card-actions justify-end">
                                    <button class="btn btn-primary">Répondre</button>
                                </div>
                            </a>
                        @endif




                    @endcan


                </div>
            </div>

        @endforeach

    </div>







</x-app-layout>

