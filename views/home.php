<?php

/**
 * home.php
 * nicely displays the data
 */
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-2">
			<h1><?=$settings['global']['name']?></h1>
			<p>
				<?=$settings['global']['network']?>
			</p>
			<hr />
			<span style="font-size: 2rem; float: left; ">ONLINE</span>
			<span style="font-size: 2rem; float: right; text-align: right; ">
				<?=$data['totals']['online']?> /
				<span style="color: #777; "><?=$data['totals']['hosts']?></span>
			</span>
		</div>
		<div class="col-sm-10">
			<div id="hosts"></div>
		</div>
	</div>
</div>
