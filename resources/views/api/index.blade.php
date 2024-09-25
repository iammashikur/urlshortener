@extends('layouts.app')
@section('content')
    <div class="container my-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Generate Your API Key</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Use this API to shorten URLs. The API accepts both GET and POST requests.</p>


                <form action="{{ route('api.generate') }}" method="POST">
                    @csrf
                    <button class="btn btn-primary mb-3" type="submit">Generate API Key</button>
                </form>

                @if ($apiKey)
                    <div id="apiKeySection">
                        <p><strong>Your API Key: {{ $apiKey->key ?? '' }}</strong></p>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="api-key" readonly value="{{ $apiKey->key ?? '' }}">
                            <button class="btn btn-outline-secondary copy-btn">Copy</button>
                        </div>
                    </div>
                @endif


                <p><strong>API Endpoint:</strong> <code>{{ url('/api/short?url=https://xyz.com'.($apiKey->key ? '&api_key=' . $apiKey->key : 'XXXXXXXXXX')) }}</code></p>
                <p><strong>Note:</strong> Use the API key in your requests. You can shorten URLs by making GET or POST
                    requests to the above endpoint.</p>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>

    $(document).ready(function() {

        $('.copy-btn').on('click', function() {

            var copyText = $('#api-key').val();

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
