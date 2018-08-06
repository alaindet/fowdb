<?php
	// Import helpers
	$clusters = \App\Helpers::get("clusters");
	$attributes = \App\Helpers::get("attributes");
	$types = \App\Helpers::get("types");
	$backsides = \App\Helpers::get("backsides");
	$rarities = \App\Helpers::get("rarities");

	// Get card info from database
	require 'admin/manage-cards/card.sql.php';

	// Check if action was passed
	if (isset($action)):
?>
	<!-- Header -->
	<div class="page-header">
		<h1>
			<?php
				// Generate title of the form
				switch($action) {
					// Create
					case 'create':
						echo 'Create new card';
						break;
					// Edit
					case 'edit':
						echo "Edit card <small>{$card['name']}</small>";
						break;
					// Delete (disabled)
					case 'delete':
						echo "Delete card <strong>{$card['name']}</strong>?";
						break;
				}
			?>
		</h1>
	</div>

	<!-- Form -->
	<div class="row">
		<div class="col-xs-12 col-sm-9">
			<form
				action="admin/manage-cards/actions.sql.php"
				method="post"
				enctype="multipart/form-data"
				id="card-form"
				class="form-horizontal"
			>
				<?php if ($action == 'delete'): /*Emphasize deleting*/?>
					
					<!-- Action (hidden) -->
					<input type="hidden" name="action" value="delete">
					
					<!-- ID (hidden) -->
					<input type="hidden" name="id" value="<?=$card['id']?>">
					
					<!-- Name (hidden) -->
					<input type="hidden" name="name" value="<?=$card['name']?>">
					
					<!-- Image_path (hidden) -->
					<input type="hidden" name="imagepath" value="<?=$card['imagepath']?>">

					<!-- Thumb_path (hidden) -->
					<input type="hidden" name="thumbpath" value="<?=$card['thumbpath']?>">

					<!-- Submit -->
					<div class="form-group">
						<div class="col-sm-12">
							<button type="submit" class="btn btn-danger btn-lg">
								<span class="glyphicon glyphicon-remove"></span>
								Delete Card
							</button>
						</div>
					</div>
				<?php endif; ?>

				<!-- Image ======================================================== -->
				<div class="form-group form-section">
					<label for="cardimage" class="col-sm-2">Image</label>
					<div class="col-sm-10">
						<?php if ($action == 'create'): ?>
							<input type="file" name="cardimage">
						<?php else: ?>
							<img src="<?=$card['thumbpath']?>">
						<?php endif; ?>
					</div>
				</div>

				<?php if($action == 'edit' OR $action == 'create'): ?>

					<!-- NARP ======================================================= -->
					<?php
						$narp = [
							0 => 'Normal',
							1 => 'Alternate',
							2 => 'Reprint',
							3 => 'Promo'
						];
					?>
					<div class="form-group form-section">
						<label for="narp" class="col-sm-2">NARP</label>
						<div class="col-sm-10">
							<select name="narp" class="form-control">
								<?php foreach($narp as $key=>$label): ?>
									<?php // STICKY
										($card['narp'] != '' AND $card['narp'] == $key)
											? $checked = ' selected=true'
											: $checked = '';
									?>
									<option value="<?=$key?>"<?=$checked?>><?=$label?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<!-- Set ======================================================== -->
					<div class="form-group form-section">
						<label for="set" class="col-sm-2">Set</label>
						<div class="col-sm-10">
							<select name="set" class="form-control">
								<option value="0">Choose a set..</option>
								<?php foreach($clusters as $cid => $cluster): ?>
									<optgroup label="<?=$cluster['name']?>">
									<?php foreach($cluster['sets'] as $setcode => $setname): ?>
										<?php // STICKY
											$card['setcode'] != '' AND $card['setcode'] == $setcode
												? $checked = ' selected=true'
												: $checked = '';
										?>
										<option value="<?=$setcode?>"<?=$checked?>>
											<?=strtoupper($setcode).' - '.$setname?>
										</option>
									<?php endforeach; ?>
									</optgroup>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<!-- Card Number ================================================ -->
					<div class="form-group form-section">
						<label class="col-sm-2">Number</label>
						<div class="col-sm-10">
							<input
								type="number"
								name="cardnum"
								value="<?=$card['num']?>"
								placeholder="Card number.."
								class="form-control">
						</div>
					</div>

					<!-- Rarity ===================================================== -->
					<div class="form-group form-section">
						<label for="rarity" class="col-sm-2">Rarity</label>
						<div class="col-sm-10">
							<select name="rarity" class="form-control">
								<!-- Default -->
								<option value="no">
									(None)
								</option>
								<?php foreach($rarities as $code => $name): ?>
									<?php // STICKY
										($card['rarity'] != '' AND $card['rarity'] == $code)
											? $checked = ' selected=true'
											: $checked = '';
									?>
									<option value="<?=$code?>"<?=$checked?>>
										<?=strtoupper($code)?> - <?=$name?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<!-- Attribute ================================================== -->
	        <div class="form-group form-section">
            <label for="attribute" class="col-sm-2">Attribute(s)</label>
            <div class="col-sm-10">
              <div class="btn-group" data-toggle="buttons">
                <?php
                	// Explode attributes
                	$cardAttributes = explode("/", $card['attr']);

                	// Loop on attributes from helpers
                	foreach ($attributes as $code => &$label):
                		// STICKY
                    if (in_array($code, $cardAttributes)) {
                    	list($checked, $active) = [' checked=true', ' active'];
                    } else {
                    	list($checked, $active) = ['', ''];
                    }
                ?>
                  <label class="btn btn-default<?=$active?>">
										<input
											type="checkbox"
											name="attribute[]"
											value="<?=$code?>"<?=$checked?>
										><!--
										--><img
											src="_images/icons/1x1.gif"
											class="fdb-icon-<?=$code?>"
											alt="<?=$label?>"
										><!--
										--><?=$label?>
                  </label>
              	<?php endforeach; ?>
              </div>
            </div>
	        </div>

	        <!-- Backside =================================================== -->
	        <div class="form-group form-section">
            <label for="attribute" class="col-sm-2">Back Side</label>
            <div class="col-sm-10">
              <div class="btn-group" data-toggle="buttons">
                <?php
                	// Grab current backside's code
                	$current = array_values(array_filter(
                		$backsides,
                		function ($i) use ($card) {
                			return $i['value'] == $card['backside'];
                		}
                	))[0]['code'];

                	// Loop on backsides
                	foreach ($backsides as &$backside):
                		/*STICKY*/$backside['code'] == $current
                			? list($checked, $active) = [' checked=true', ' active']
                			: list($checked, $active) = ['', ''];
                	?>
                    <label class="btn btn-default<?=$active?>">
                      <input
                      	type="radio"
                      	name="backside"
                      	value="<?=$backside['code']?>"<?=$checked?>
                      >
                      <span class="pointer">
                      	<?=$backside['name']?>
                      </span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
	        </div>

					<!-- Type ======================================================= -->
					<div class="form-group form-section">
						<label for="type" class="col-sm-2">Type</label>
						<div class="col-sm-10">
							<select name="type" class="form-control">
								<option value="0">Choose type..</option>
								<?php foreach($types as $type): ?>
									<?php // STICKY
										$card['type'] != '' AND $card['type'] == $type
											? $checked = ' selected=true'
											: $checked = '';
									?>
									<option value="<?=$type?>"<?=$checked?>><?=$type?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>


					<!-- Cost ======================================================= -->
					<div class="form-group form-section">
						<label class="col-sm-2">Cost</label>
						<div class="col-sm-10">
							<div class="row">

								<!-- Attribute cost -->
								<div class="col-sm-4">
									<span class="form-label">Attribute</span>
									<input 
										type="text"
										name="attrcost"
										value="<?=$card['attrcost']?>"
										placeholder="Ex.: gg for Xeex"
										class="form-control">
								</div>

								<!-- Free cost -->
								<div class="col-sm-4">
									<span class="form-label">
										Free
										<span style="font-size: .95rem">(-1 for X, -2 for XX, etc.)</small>
									</span>
									<input
										type="number"
										name="freecost"
										value="<?=$card['freecost']?>"
										placeholder="Free cost.."
										class="form-control">
								</div>

								<!-- Total cost -->
								<div class="col-sm-4">
									<span class="form-label">Total</span>
									<input
										type="number"
										name="totalcost"
										value="<?=$card['totalcost']?>"
										placeholder="Tot cost.."
										class="form-control">
								</div>

							</div>
						</div>
					</div>

          <!-- Divinity =================================================== -->
					<div class="form-group form-section">
						<label for="divinity" class="col-sm-2">Divinity</label>
						<div class="col-sm-10">
							<input
                type="text"
                name="divinity"
                value="<?=$card['divinity']?>"
                placeholder="Divinity (-1 to delete existing value)..."
                class="form-control"
              >
						</div>
					</div>

					<!-- ATK/DEF ==================================================== -->
					<div class="form-group form-section">
						<label class="col-sm-2">ATK/DEF</label>
						<div class="col-sm-10">
							<div class="row">

								<!-- ATK -->
								<div class="col-sm-6">
									<span class="form-label">ATK</span>
									<input type="number" name="atk" value="<?=$card['atk']?>" placeholder="ATK.." class="form-control">
								</div>

								<!-- DEF -->
								<div class="col-sm-6">
									<span class="form-label">DEF</span>
									<input type="number" name="def" value="<?=$card['def']?>" placeholder="DEF.." class="form-control">
								</div>

							</div>
						</div>
					</div>


					<!-- Name ======================================================= -->
					<div class="form-group form-section">
						<label for="name" class="col-sm-2">Name</label>
						<div class="col-sm-10">
							<input type="text" name="name" value="<?=$card['name']?>" placeholder="Name.." class="form-control">
						</div>
					</div>


					<!-- Race/Trait ================================================= -->
					<div class="form-group form-section">
						<label for="race" class="col-sm-2">Race/Trait</label>
						<div class="col-sm-10">
							<input type="text" name="race" value="<?=$card['subtype_race']?>" class="form-control" placeholder="Race/Trait..">
						</div>
					</div>


					<!-- Text ======================================================= -->
					<div class="form-group form-section">
						<label for="text" class="col-sm-2">Text</label>
						<div class="col-sm-10">
							<textarea
								name="text" class="form-control"
								rows="8"
								placeholder="Text.."
								style="font-family: monospace;font-size:1.3em;"
							><?=$card['text']?></textarea>
						</div>
					</div>

					<!-- Flavor Text ================================================ -->
					<div class="form-group form-section">
						<label for="flavortext" class="col-sm-2">Flavor Text</label>
						<div class="col-sm-10">
							<textarea name="flavortext" class="form-control" rows="3" placeholder="Flavor text.."><?php
								echo $card['flavortext'];
							?></textarea>
						</div>
					</div>

					<!-- Action (hidden) -->
					<input type="hidden" name="action" value="<?=$_REQUEST['form_action']?>">

					<!-- ID (hidden) -->
					<input type="hidden" name="id" value="<?=$card['id']?>">

					<!-- Submit -->
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<button type="submit" class="btn btn-primary btn-lg"><?php
								echo strtoupper($action[0]).substr($action, 1)." Card";
							?></button>
						</div>
					</div>
				<?php endif; ?>
			</form>
		</div>
	</div>

	<hr>

	<!-- Admin menu link -->
	<p>
		<a href="/index.php?p=admin">
			<button type="button" class="btn btn-default">
				&larr; Admin menu
			</button>
		</a>
		<a href="/index.php?p=manage-cards">
			<button type="button" class="btn btn-default">
				Cards menu
			</button>
		</a>
	</p>

	<?php if ($action != 'delete') : /*Hide conventions when deleting*/ ?>
		<!-- Conventions -->
		<div class="row">
			<div class="col-xs-12">
				<?php include 'conventions.html.php'; ?>
			</div>
		</div>
	<?php endif; ?>

	<hr>
<?php else: ?>
	<!-- Header -->
	<div class="page-header">
		<h1>No action</h1>
	</div>

	<p>No action to perform on some card was passed.</p>

	<!-- Admin menu link -->
	<p>
		<a href="/index.php?p=admin">
			<button type="button" class="btn btn-default">
				&larr; Admin menu
			</button>
		</a>
		<a href="/index.php?p=manage-cards">
			<button type="button" class="btn btn-default">
				Cards menu
			</button>
		</a>
	</p>
<?php endif; ?>
