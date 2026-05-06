@extends('layouts.default')
@section('title', 'Meeting Feedback')
@section('content')

<style>
.sm-form-card { background:#fff; border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,.08); overflow:hidden; }
.sm-form-header { background:linear-gradient(135deg,#11998e,#38ef7d); padding:28px 32px; color:#fff; }
.sm-form-body { padding:28px 32px; }
.sm-input { border:2px solid #e8edf5; border-radius:10px; padding:10px 14px; transition:border-color .2s; width:100%; font-size:14px; }
.sm-input:focus { border-color:#11998e; outline:none; box-shadow:0 0 0 4px rgba(17,153,142,.08); }
.sm-label { font-weight:600; font-size:13px; color:#555; margin-bottom:6px; display:block; }
.sm-btn-green { background:linear-gradient(135deg,#11998e,#38ef7d); color:#fff; border:none; border-radius:10px; padding:12px 24px; font-weight:700; font-size:15px; width:100%; cursor:pointer; box-shadow:0 4px 14px rgba(17,153,142,.3); transition:transform .15s; }
.sm-btn-green:hover { transform:translateY(-1px); color:#fff; }
.meeting-ref { background:#f4fff8;border-radius:10px;padding:12px 16px;font-size:13px;margin-bottom:20px;border-left:3px solid #38ef7d; }
</style>

<div class="row justify-content-center py-3">
    <div class="col-md-6 col-lg-5">
        <div class="sm-form-card">
            <div class="sm-form-header">
                <h5 class="mb-1 fw-bold"><i class="fa fa-comment me-2"></i>Meeting Feedback</h5>
                <small style="opacity:.85;">How did the meeting go? Share the outcome.</small>
            </div>
            <form method="POST" action="{{ route('new-movement.submit-feedback') }}" class="sm-form-body">
                @csrf
                @if($meeting)
                <div class="meeting-ref">
                    <strong><i class="fa fa-users me-1"></i>{{ $meeting->client_name ?? '—' }}</strong><br>
                    <small class="text-muted">{{ $meeting->location ?? '' }} &nbsp;·&nbsp; {{ $meeting->contact_person ?? '' }}</small>
                </div>
                @endif
                <div class="mb-4">
                    <label class="sm-label">Feedback / Meeting Outcome <span class="text-danger">*</span></label>
                    <textarea name="feedback" class="sm-input" rows="5" required placeholder="Describe what happened in this meeting, any follow-ups, results…" style="resize:vertical;"></textarea>
                </div>
                <button type="submit" class="sm-btn-green"><i class="fa fa-check me-2"></i>Submit Feedback</button>
            </form>
        </div>
    </div>
</div>
@endsection
