<div class="menu-contianer">
	<div class="container">
		<nav class="admin-menu">
			<ul>
				<!-- <li><a href="/admin/">Home</a></li> -->
				<li><a href="/admin/bookings/">Bookings</a></li>
				<li><a href="/admin/accommodations/">Accommodations</a></li>
				<li><a href="/admin/packages/">Packages and Addons</a></li>
			</ul>
		</nav>
		<ul class="navbar-right admin-user">
			<li class="dropdown">
	            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
	                {{ Auth::user()->name }} <span class="caret"></span>
	            </a>
	            <ul class="dropdown-menu" role="menu">
	                <li>
	                    <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
	                        Logout
	                    </a>
	                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
	                        {{ csrf_field() }}
	                    </form>
	                </li>
	            </ul>
	        </li>
        </ul>
	</div>
</div>