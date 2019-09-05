@if(in_array($index,$level))

{{ $member['id'] }}
^
{{ $parent }}{{ $member['name'] }}
@endif
@php
$index++;

@endphp
<br/>
@foreach($member['subMember']->all() as $submember)
@include('volunteer::includes.membership-item', [
	'member'=>$submember, 
	'parent'=>$parent.$member['name'].' » ',
	'index' => $index,
	'level' => $level
])
@endforeach