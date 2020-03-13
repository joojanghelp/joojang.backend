Hello {{ $Email->receiverName }},
This is a demo email for testing purposes! Also, it's the HTML version.

Demo object values:

Demo One: {{ $Email->demo_one }}
Demo Two: {{ $Email->demo_two }}

Values passed by With method:

testVarOne: {{ $testVarOne }}
testVarOne: {{ $testVarOne }}

Thank You,
{{ $Email->sender }}