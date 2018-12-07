<?php

// VARIABLES
// $isSpoiler (Optional)
// $spoilers (Optional)

?>
<aside class="col-xs-12 hidden" id="hide-options">
	<div class="panel panel-default">

		<!-- OPTIONS header -->
		<div class="panel-heading">
			<h3>
        <i class="fa fa-sliders"></i>
        Options
      </h3>
		</div>

		<!-- OPTIONS content -->
		<div class="panel-body">

			<!-- OPTION: CARDS PER ROW -->
			<div class="option" id="option-cards-per-row">
				<h4>Cards per row</h4>
				<div class="controls">
					<div class="input-group">
						
            <!-- Minus -->
						<span class="input-group-btn text-center">
							<button
                type="button"
                class="btn fdb-btn opt_b"
                id="opt_b_numxrow_minus"
              >
								<i class="fa fa-minus"></i>
							</button>
						</span>

						<!-- Input -->
						<input
              type="text"
              class="form-control opt_i text-center"
              id="opt_i_numxrow"
              name="opt_i_numxrow"
              size="2"
              maxlength="2"
              style="font-size:1.5em;"
              value="3"
            >

						<!-- Plus -->
						<span class="input-group-btn text-center">
							<button
                type="button"
                class="btn fdb-btn opt_b"
                id="opt_b_numxrow_plus"
              >
								<i class="fa fa-plus"></i>
							</button>
						</span>

					</div>
				</div>
			</div>

			<?php if (isset($isSpoiler) && $isSpoiler): ?>
				
        <hr>

				<!-- OPTION: SHOW MISSING -->
				<div class="option" id="option-missing">
					<h4>Missing cards</h4>
					<div class="controls">
						<div class="btn-group" data-toggle="buttons">
							<label
                for="opt_i_missing"
                class="btn btn-default option_btn"
                id="opt_i_missing"
              >
								<input
                  type="checkbox"
                  class="form-control opt_i"
                  name="opt_i_missing"
                >
								<i class="fa fa-th-large"></i>
								Show missing
							</label>
						</div>
					</div>
				</div>

				<hr>

				<!-- OPTION: SPOILER LIST -->
				<div class="option" id="option-spoiler-list">
					<h4>Spoiler sets</h4>
					<ul class="fd-list">
						<?php foreach ($spoilers as $set): ?>
							<li>
								<a href='#<?=$set['code']?>'>
									<?=$set['name']?> (<?=$set['code']?>)
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

			<?php endif; ?>
		</div>
	</div>
</aside>
