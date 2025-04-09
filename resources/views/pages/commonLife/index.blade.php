<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Vie Commune') }}
            </span>
        </h1>
    </x-slot>


    @if ($errors->any())
        <div class="alert alert-danger text-red-400">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" class="card-body flex flex-col gap-5 p-10" action="{{ route('common-life.create') }}">
        @csrf

        <x-forms.input label="{{ __('Nom de la tâche') }}" name="name"
                       type="text" placeholder="le ménage"
        />
        <x-forms.input label="{{ __('Description de la tâche') }}" name="description"
                       type="text" placeholder="dans la cuisine"
        />
        <x-forms.primary-button>Valider</x-forms.primary-button>
    </form>

    <div class="card min-w-full">
        <div class="card-header">
            <h3 class="card-title">
                Tâches communes
            </h3>
        </div>
        <div class="card-table">
            <table class="table table-border align-middle text-gray-700 font-medium text-sm">
                <thead>
                <tr>
                    <th>
                        Nom
                    </th>
                    <th>
                        Description
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Date de création
                    </th>
                    <th>
                        Date de validation
                    </th>
                    <th>
                        Supprimer
                    </th>
                </tr>
                </thead>
                <tbody>

                @foreach($commonTasks as $commonTask)
                    <tr>

                        <form method="POST" action="{{route('common-life.update', $commonTask->id)}}">
                            @csrf
                            @method('PUT')
                            <td>
                                <x-forms.input type="text" value="{{$commonTask->name}}" name="name"  onchange="this.form.submit()"/>
                            </td>





                            <td>
                                <x-forms.input type="text" value="{{$commonTask->description}}"  name="description" onchange="this.form.submit()"/>
                            </td>





                            <td>
                                <x-forms.dropdown onchange="this.form.submit()" name="validate">
                                    <option value="0" {{$commonTask->validate == 0 ? 'selected' : ''}}>en cours</option>
                                    <option value="1" {{$commonTask->validate == 1 ? 'selected' : ''}}>validé</option>
                                </x-forms.dropdown>

                            </td>


                            <td>
                                <x-forms.input type="datetime-local" value="{{$commonTask->created_at}}" name="created" onchange="this.form.submit()" />
                            </td>



                            <td>
                                <x-forms.input type="datetime-local" value="{{$commonTask->time}}"  name="time" onchange="this.form.submit()" />

                            </td>
                        </form>
                        <form method="POST" action="{{route('common-life.destroy', $commonTask->id)}}">
                            @csrf
                            @method('DELETE')
                            <td>
                                    <x-forms.primary-button>Supprimer</x-forms.primary-button>
                            </td>
                        </form>


                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
