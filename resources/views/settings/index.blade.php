@extends('layouts.main')

@section('title', 'Email Settings')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Email Settings</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Email Settings</div>
        </div>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Email Configuration</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                @foreach($emailSettings as $setting)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="{{ $setting->key }}">{{ $setting->label }}</label>
                                            
                                            @if($setting->type === 'select' && $setting->options)
                                                <select class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}">
                                                    @php
                                                        $options = is_array($setting->options) ? $setting->options : json_decode($setting->options, true);
                                                    @endphp
                                                    
                                                    @foreach($options as $value => $label)
                                                        <option value="{{ $value }}" {{ $setting->value == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach

                                                        
                                                </select>
                                            @elseif($setting->type === 'password')
                                                <input type="password" class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}">
                                            @elseif($setting->type === 'textarea')
                                                <textarea class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}" rows="3">{{ $setting->value }}</textarea>
                                            @elseif($setting->type === 'boolean')
                                                <select class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}">
                                                    <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Yes</option>
                                                    <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>No</option>
                                                </select>
                                            @else
                                                <input type="text" class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}">
                                            @endif
                                            
                                            @if($setting->description)
                                                <small class="form-text text-muted">{{ $setting->description }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </form>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4>Test Email Configuration</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.test-email') }}" method="POST">
                            @csrf
                            
                            <div class="form-group">
                                <label for="test_email">Email Address</label>
                                <input type="email" class="form-control" id="test_email" name="test_email" required>
                                <small class="form-text text-muted">Enter an email address to send a test email to.</small>
                            </div>
                            
                            <button type="submit" class="btn btn-info">Send Test Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 