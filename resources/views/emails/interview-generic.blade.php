<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? ('Interview Invitation - ' . ($interview->position_applied ?? '')) }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color:#333; line-height:1.6; }
        a { color:#2b6cb0; text-decoration: none; }
    </style>
    </head>
<body>
    @php
        $formatDate = function ($d) {
            if (!$d) return '';
            try { return \Carbon\Carbon::parse($d)->format('d M Y'); } catch (\Exception $e) { return (string)$d; }
        };
        $formatTime = function ($t) {
            if (!$t) return '';
            if ($t instanceof \DateTimeInterface) { try { return \Carbon\Carbon::instance($t)->format('H:i'); } catch (\Exception $e) {} }
            if (is_string($t)) {
                $raw = trim($t);
                foreach (['H:i', 'H:i:s', 'H:i:s.u'] as $fmt) {
                    try { return \Carbon\Carbon::createFromFormat($fmt, $raw)->format('H:i'); } catch (\Exception $e) {}
                }
                return $raw;
            }
            try { return \Carbon\Carbon::parse($t)->format('H:i'); } catch (\Exception $e) { return (string)$t; }
        };
    @endphp

    <p>Dear {{ $interview->name ?? 'Candidate' }},</p>

    @php $isSecond = isset($interview->round_type) && $interview->round_type === 'second'; @endphp

    @if($isSecond)
        <p>
            With reference to your first interview, we’re pleased to inform you that you’ve been shortlisted for the second round of interviews, scheduled on <strong>{{ $formatDate($interview->date_of_interview) }}</strong> at <strong>{{ $formatTime($interview->interview_time) }}</strong>.
        </p>
    @else
        <p>
            We appreciate your interest in the position of <strong>{{ $interview->position_applied }}</strong> at Eazisols. We would like to invite you for an <strong>{{ isset($interview->interview_type) ? ucfirst($interview->interview_type) : '' }}</strong> interview on <strong>{{ $formatDate($interview->date_of_interview) }}</strong> at <strong>{{ $formatTime($interview->interview_time) }}</strong>.
        </p>
    @endif

    <p>
        <strong>Address:</strong> 65-J1, Wapda Town Phase 1, Lahore, Pakistan.<br>
        <strong>Location:</strong> <a href="https://goo.gl/maps/Naxu32J2NkDmjkKR8" target="_blank" rel="noopener">Open in Google Maps</a>
    </p>

    @if($isSecond)
        <p>Please acknowledge this email and confirm your availability for the interview.</p>
        <p>Best regards,<br>HR Signature</p>
    @else
        <p>Please confirm your availability for the interview by replying to this email.</p>
        <p>Best regards,<br>Eazisols HR Team</p>
    @endif
</body>
</html>

