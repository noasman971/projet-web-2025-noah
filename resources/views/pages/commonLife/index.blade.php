<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Vie Commune') }}
            </span>
        </h1>

    </x-slot>





    @can('viewAnyAdmin', App\Models\CommonTask::class)
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
                            Date / Auteur de validation
                        </th>
                        <th>
                            Supprimer
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($commonTasks as $commonTask)
                        <tr>

                            <form method="POST" action="{{route('common-life.update', Crypt::encrypt($commonTask->id))}}">
                                @csrf
                                @method('PUT')
                                <td>
                                    <textarea rows="3" style="resize: none" name="name" onchange="this.form.submit()" class="resize-none outline-none items-center">{{$commonTask->name}}</textarea>
                                </td>





                                <td>
                                    <textarea rows="9" style="resize: none" name="description" onchange="this.form.submit()" class="resize-none outline-none">{{$commonTask->description}}</textarea>
                                </td>





                                <td>
                                    <x-forms.dropdown onchange="this.form.submit()" name="validate">
                                        <option value="0" {{$commonTask->validate == 0 ? 'selected' : ''}}>en cours</option>
                                        <option value="1" {{$commonTask->validate == 1 ? 'selected' : ''}}>validé</option>
                                    </x-forms.dropdown>

                                </td>


                                <td>
                                    {{$commonTask->created_at}}
                                </td>



                                <td>
                                    <x-forms.input type="datetime-local" value="{{$commonTask->time}}"  name="time" onchange="this.form.submit()" />
                                    @if($commonTask->user_id != null)

                                        <p class="mt-2 font-bold">{{$commonTask->user->first_name}} {{$commonTask->user->last_name}}</p>
                                        <p class="mt-2">{{$commonTask->commentary}}</p>

                                    @endif

                                </td>
                            </form>
                            <form method="POST" action="{{route('common-life.destroy', Crypt::encrypt($commonTask->id))}}">
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
    @endcan


    @can('viewAnyStudent', \App\Models\CommonTask::class)





        <div class="px-4 py-2 flex ">

            <h1 class="text-gray-800 font-bold text-2xl uppercase">Tâches communes</h1>
            <a href="{{route('history.index')}}"  class="absolute top-0 right-0 text-5xl mt-2.5">
                <i class="ki-filled ki-time"></i>

            </a>

        </div>

        <div class="flex flex-wrap lg:w-4/5 sm:mx-auto sm:mb-2 -mx-2 p-4">
            @foreach($commonTasks as $commonTask)

                @if($commonTask->validate == 1)

                    <div class="p-2 sm:w-1/2 w-full">
                        <div class="bg-gray-100 rounded flex p-4 h-full items-center">
                            <x-forms.checkbox name="todo" id="checkbox1" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded" checked disabled="true"/>
                            <span class="font-medium"><s>{{$commonTask->name}}</s></span>
                            <span class="text-sm m-5">{{$commonTask->description}}</span>
                            <span>{{$commonTask->user->first_name}} {{$commonTask->user->last_name}} </span>

                        </div>
                    </div>


                @endif

                @if($commonTask->validate == 0)
                <form method="POST" action="{{route('common-life.pointer', Crypt::encrypt($commonTask->id))}}">
                    @csrf
                    @method('PUT')


                    <div class="p-2 sm:w-1/2 w-full">
                        <div class="bg-gray-100 rounded flex p-4 h-full items-center">
                            <x-forms.checkbox name="todo" id="checkbox1" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded" />
                            <span class="font-medium">{{$commonTask->name}}</span>
                            <span class="text-sm m-5">{{$commonTask->description}}</span>
                            <span>{{$commonTask->user_id}}</span>

                        </div>

                        <div id="divRemove" class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50  dark:border-gray-600 ">
                            <div class="px-4 py-2 bg-white rounded-t-lg dark:bg-gray-800">
                                <textarea name="comment" rows="2" class="w-full px-0 text-sm text-gray-900 bg-white border-0 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 outline-none" placeholder="Ecrivez un commentaire..." ></textarea>
                            </div>
                            <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600 border-gray-200" >
                                <x-forms.primary-button>Valider la tâche</x-forms.primary-button>

                            </div>
                        </div>
                    </div>

                </form>
                @endif
            @endforeach
        </div>















    @endcan




</x-app-layout>
