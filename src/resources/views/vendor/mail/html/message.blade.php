<x-mail::layout>

<div class="my-10">
{{-- Body --}}
{{ $slot }}
</div>

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
</x-slot:footer>
</x-mail::layout>
