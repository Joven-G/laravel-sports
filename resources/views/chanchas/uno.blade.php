@extends('layouts.field')

@section('content')
	<div class="d-flex">
		<div class="">
			@include('partials.list_fields')
		</div>
		<v-app class="flex-grow-1">
			<field-one></field-one>
		</v-app>
	</div>
@endsection