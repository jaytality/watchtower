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
			<div class="clearfix"></div>
			<div class="row">
				<!-- ingress (downloads) -->
				<div class="col-sm-6">
					<span style="color: #00ff00; ">
						<?=$data['totals']['ingress']?> <i class="icon-down-dir"></i>
					</span>
				</div>

				<!-- egress (uploads) -->
				<div class="col-sm-6 text-right">
					<span style="color: #ff0000; ">
						<?=$data['totals']['egress']?> <i class="icon-up-dir"></i>
					</span>
				</div>
			</div>
		</div>
		<div class="col-sm-10">
			<div id="hosts"></div>
		</div>
	</div>
</div>
