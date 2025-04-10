<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center text-sm font-normal">
            <a href="{{route('common-life.index')}}">
            <i class="ki-filled ki-left-square text-3xl mr-5"></i>
            </a>
            <span class="text-gray-700">
                {{ __('Historique') }}
            </span>
        </h1>

    </x-slot>



    <div class="card min-w-full">
        <div class="card-header">
            <h3 class="card-title">
                Historique des tâches communes
            </h3>
        </div>
        <div class="card-table">
            <div data-datatable="true" data-datatable-page-size="5">
                <div class="scrollable-x-auto">
                    <table class="table table-border text-sm" data-datatable-table="true">
                        <thead>

                        <tr>
                            <th>
                                Nom
                            </th>
                            <th>
                                Description
                            </th>
                            <th>
                                Commentaire
                            </th>
                            <th>
                                Date de validation
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($commonTasks as $commonTask)


                            <tr>
                            <td>
                                {{$commonTask->name}}
                            </td>
                            <td>
                                {{$commonTask->description}}
                            </td>
                            <td>
                                {{$commonTask->commentary}}
                            </td>
                            <td>
                                {{$commonTask->time}}
                            </td>
                        </tr>

                        @endforeach

                        </tbody>
                    </table>
                    <div class="card-footer justify-center md:justify-between flex-col md:flex-row gap-3 text-gray-600 text-2sm font-medium">
                        <div class="flex items-center gap-2">
                            Afficher
                            <select class="select select-sm w-16" data-datatable-size="true" name="perpage">
                            </select>
                            par page
                        </div>
                        <div class="flex items-center gap-4">
      <span data-datatable-info="true">
      </span>
                            <div class="pagination" data-datatable-pagination="true">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</x-app-layout>

