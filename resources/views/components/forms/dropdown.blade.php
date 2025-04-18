@props([
    'label'         => false,
    'name'          => '',
    'value'         => '',
    'disabled'      => false,
    'messages'      => false,
    'onchange'      =>"",
    'class'        => '',
    'multiple' => false
])

<div class="flex flex-col gap-1 min-w-[90px]">
    @if($label)
        <label class="form-label font-normal text-gray-900">{{ $label }}</label>
    @endif
    @if($multiple)
        <select class="select {{$class}}" name={{$name}} {{ $disabled ? 'disabled' : '' }} name="{{ $name }}" onchange="{{$onchange}}" multiple>
            {{ $slot }}
        </select>

        @else

            <select class="select {{$class}}" name={{$name}} {{ $disabled ? 'disabled' : '' }} name="{{ $name }}" onchange="{{$onchange}}">
                {{ $slot }}
            </select>
    @endif


    @if($messages)
        <x-forms.input-error :messages="$messages" class="mt-1" />
    @endif
</div>

