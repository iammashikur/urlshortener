@extends('layouts.app')
@section('content')
<div class="container">

    <table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Short URL</th>
            <th scope="col">Original URL</th>
            <th scope="col">Created</th>
            <th scope="col">Visits</th>
            <th scope="col" class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($links as $link)

            @php
                $shortLink = url($link->generated_code);
            @endphp

            <tr>
                <th scope="row">{{ $link->id }}</th>
                <td>

                    <a href="{{ $shortLink }}" id="short-url" target="_blank">{{ $shortLink }}</a>
                    <button class="copy-btn btn btn-primary btn-sm"><i class="fa-solid fa-copy"></i></button>

                </td>
                <td>
                    <a href="{{ $link->original_url }}" target="_blank">{{ $link->original_url }}</a>
                </td>
                <td>{{ $link->created_at }}</td>
                <td>
                    {{ $link->visitors()->count() }}
                    {{-- TODO: add format number to views (1k, 1M, 1B) --}}
                </td>
                <td class="d-flex justify-content-end">
                    <a class="btn btn-primary btn-sm me-2" href="{{ route('links.show', $link->id) }}"><i class="fa-solid fa-eye"></i> View</a>

                    <form action="{{ route('links.destroy', $link->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-trash"></i> Delete</button>
                    </form>

                </td>

            </tr>
            @endforeach
        </tbody>


      </table>
</div>
@endsection
@push('scripts')
<script>

    $(document).ready(function() {

        $('.copy-btn').on('click', function() {

            var copyText = $('#short-url').text();

            var tempInput = $('<textarea>');

            $('body').append(tempInput);

            tempInput.val(copyText).select();

            document.execCommand("copy");

            tempInput.remove();

            notyf.success({
                message: 'Copied to clipboard',
                duration: 1000
            });
        });

    });
</script>
@endpush
