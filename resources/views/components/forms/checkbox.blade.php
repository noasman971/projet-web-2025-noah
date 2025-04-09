@props([
    'label'         => '',
    'disabled'      => false,
    'name'          => 'input_name',
    'checked'       => false,
])

<label class="checkbox-group">
    @if($checked == true)
        <input class="checkbox checkbox-sm" {{ $disabled ? 'disabled' : '' }} name="{{ $name }}"
               type="checkbox" value="1" checked/>
    @endif
    @if($checked == false)
        <input class="checkbox checkbox-sm" {{ $disabled ? 'disabled' : '' }} name="{{ $name }}"
               type="checkbox" value="1"/>
    @endif

    <span class="checkbox-label">
       {{ $label }}
      </span>
</label>
