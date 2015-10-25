{if $loggedIn}
<!-- NAVIGATION LOGGED USER-->
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" n:href="Homepage:default">DashBoard</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="main-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><a n:href="Article:default">Články</a></li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Zboží<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a n:href="Manufacturer:default">Výrobci</a></li>
						<li><a n:href="Category:default">Kategorie</a></li>
						<li><a n:href="Goods:default">Výrobky</a></li>
					</ul>
				</li>

				<li><a n:href="User:default">Uživatelé</a></li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ext. nástroje <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="http://www.google.com/analytics/">Google Analytics</a></li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#">{$nuserEmail}</a></li>
				<li><a n:href="Sign:out" class="link-danger">Odhlásit</a></li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
{else}

<!-- NAVIGATION UNLOGGED -->
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse-2" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" n:href="Homepage:default">DashBoard</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="main-navbar-collapse-2">
			<ul class="nav navbar-nav navbar-right">
				<li><a n:href="Sign:in">Přihlásit</a></li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
{/if}
