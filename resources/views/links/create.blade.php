@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="url-shortener-form">

                    <h1>URL Shortener</h1>
                    <p>Enter a Long URL to shorten</p>


                    <form action="{{ route('links.store') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Enter a URL to shorten"
                                aria-label="URL Input" name="original_url">
                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-link"></i>
                                Shorten</button>
                        </div>
                    </form>
                </div>

                <div class="url-shortener-results mt-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="card text-center shadow-lg">
                            <div class="card-body">
                                <h4 class="card-title text-success">Link Shortened Successfully!</h4>

                                <p class="card-text">
                                    <strong>Main URL:</strong>
                                    <span id="main-url">{{ session('original_url') }}</span>
                                </p>

                                <p class="card-text">

                                    <strong>Shortened URL:</strong>

                                    <span id="short-url" class="text-primary">{{ session('short_url') }}</span>

                                    <button class="btn btn-outline-primary btn-sm copy-btn">
                                        <i class="fa-solid fa-copy"></i> Copy
                                    </button>

                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

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
