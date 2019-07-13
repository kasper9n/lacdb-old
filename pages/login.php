<main class="login-page">

<? if($logged_out): ?>

	<div class="center-xy">
		<h1>Lac.db</h1>
		<form action="" method="post">
			<input class="center-x" type="password" name="password" placeholder="Password">
			<input type="submit" name="submit_login" value=""/>
		</form>
	</div>
	<p>Password: passie</p>

<? elseif($logged_in): redirect_to("/dashboard");?>

<? endif; ?>

</main>
