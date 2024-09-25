@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Link Statistics</h1>


        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <a href="{{ url($link->generated_code) }}" class="text-decoration-none" target="_blank">
                        {{ url($link->generated_code) }}
                    </a>
                </h5>
            </div>

            <div class="card-body">
                <p class="card-text text-muted mb-3">

                        <i class="fas fa-link"></i> Original URL: {{ $link->original_url }}

                </p>
                <p class="card-text text-muted mb-3">

                        <i class="fas fa-calendar"></i> Created: {{ $link->created_at->format('Y-m-d h:i A') }}

                </p>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-primary mb-0">Total Visits</h6>
                        <p class="display-6 fw-bold mb-0">{{ $totalVisits }}</p>
                    </div>
                    <div class="col-6">
                        <h6 class="text-primary mb-0">Unique Visitors</h6>
                        <p class="display-6 fw-bold mb-0">{{ $uniqueVisitors }}</p>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Visitor IP</th>
                    <th>User Agent</th>
                    <th>Referrer</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($visitorDetails as $visitor)
                    <tr>
                        <td>{{ $visitor->visitor_ip }}</td>
                        <td>{{ $visitor->user_agent }}</td>
                        <td>{{ $visitor->referrer ?? 'N/A' }}</td>
                        <td>{{ $visitor->created_at->format('Y-m-d h:iA') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
