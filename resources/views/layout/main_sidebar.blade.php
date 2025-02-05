	<!--sidebar-->
	<aside class="sidebar">
		<div class="left-aside-container">
			<div class="user-profile-container">
				<div class="user-profile clearfix">
					<div class="admin-user-thumb">
						<img src="assets/images/faces/face1.png" alt="admin" class="img-circle">
					</div>
					<div class="admin-user-info">
						<ul class="user-info">
							<li><a href="index.htm#" class="text-semibold text-size-large">Jane Elliott</a></li>
							<li><a href="index.htm#"><small>Business Analyst</small></a></li>
						</ul>
						<div class="logout-icon"><a href="login_simple.htm"><i class="icon-exit2"></i></a></div>
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