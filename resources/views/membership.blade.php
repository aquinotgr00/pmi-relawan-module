@php
$index = 0;
@endphp
@foreach ($membership as $member)
@include('volunteer::includes.membership-item', [
	'member'=> $member, 
	'parent'=> '',
	'index' => $index,
	'level' => $level
])
@endforeach