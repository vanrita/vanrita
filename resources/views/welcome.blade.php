@extends('app')
@section('content')
		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 90%;
				color: #576064;
				font-weight: 100;
				font-family: 'Lato';
			}

			html {
				width: 100%;
				height: 100%;
			}

			.container {
				width: 100%;
				height: 100%;
				text-align: center;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				position: relative;
				top: 40%;
				margin-top: -95px;
			}

			.title {
				font-size: 96px;
				margin-bottom: 40px;
			}

			.quote {
				font-size: 24px;
			}
		</style>
		<div class="container">
			<div class="content">
				<div class="title">Love is...</div>
				<!--<div class="quote">{{ Inspiring::quote() }}</div>-->
				<div class="quote">Appreciate each other&nbsp;&nbsp;&&nbsp;&nbsp;Need each other</div>
			</div>
		</div>
@endsection