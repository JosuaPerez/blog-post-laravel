@props(['name'])

<label class="block mb-2 uppercase font-bold text-xs text-gray-700 rounded"
       for="{{ $name }}"
>
    {{ ucwords($name) }}
</label>
