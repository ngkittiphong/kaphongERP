	<!--sidebar-->
	<aside class="sidebar">
		<div class="left-aside-container">
			<div class="user-profile-container">
				<div class="user-profile clearfix">
					<div class="admin-user-thumb">
						<img src="{{ Auth::check() && Auth::user()->profile && Auth::user()->profile->avatar ? Auth::user()->profile->avatar : asset('assets/images/faces/face_default.png') }}" alt="admin" class="img-circle">
					</div>
					<div class="admin-user-info">
						<ul class="user-info">
							<li><a href="#" class="text-semibold text-size-large">{{ Auth::check() && Auth::user()->profile ? (Auth::user()->profile->fullname_th ?? Auth::user()->username) : (Auth::check() ? Auth::user()->username : 'Guest') }}</a></li>
							<li><a href="#"><small>{{ Auth::check() && Auth::user()->type ? Auth::user()->type->name : (Auth::check() ? 'User' : 'Guest') }}</small></a></li>
						</ul>
						@if(Auth::check())
						<div class="logout-icon"><a href="{{ url('/user/signOut') }}"><i class="icon-exit2"></i></a></div>
						@endif
					</div>
					
				</div>				
			</div>			
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active" id="tab-menu"><a href="index.htm#menu" aria-controls="menu" role="tab" data-toggle="tab"><i class="icon-home2"></i></a></li>
				<li role="presentation" class="" id="tab-email"><a href="index.htm#email" aria-controls="email" role="tab" data-toggle="tab"><i class="icon-envelope"></i></a></li>
				<li role="presentation" class="" id="tab-profile"><a href="index.htm#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="icon-users2"></i></a></li>
				<li role="presentation" class="" id="tab-settings"><a href="index.htm#settings" aria-controls="settings" role="tab" data-toggle="tab"><i class="icon-cog3"></i></a></li>
			</ul>
		  
			<div class="tab-content">
				@include('sidebar.sidebar01')
			</div>		
		</div>
	</aside>
	<!--/sidebar-->