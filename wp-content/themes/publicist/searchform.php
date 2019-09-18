<?php $searchpid = rand(0,999); ?>
<form method="get" id="<?php echo esc_attr($searchpid); ?>" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-group">
		<input type="text" name="s" id="s-<?php echo esc_attr($searchpid); ?>" placeholder="<?php esc_attr_e('Search...',"publicist"); ?>" class="form-control" required>
		<span class="input-group-btn">
			<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
		</span>
	</div><!-- /input-group -->
</form>
