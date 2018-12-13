<?php
// VARIABLES (External ones)
// $filters
// $thereWereResults

// INPUTS
// do
// q
// exact
// infields[]
// exclude[]
// sort
// sortdir
// format[]
// type[]
// backside[]
// divinity[]
// set[]?
// set_default
// attributes[]
// no_attributes_multi
// attribute_multi
// attribute_selected
// total_cost[]
// xcost
// rarity[]
// race
// artist
// atk-operator
// atk
// def-operator
// def

// New lookup data
$lookup = (App\Services\Lookup\Lookup::getInstance())->getAll();
$formats = &$lookup['formats']['code2name'];
$formatDefault = &$lookup['formats']['default'];
$sortables = &$lookup['sortables']['cards'];
$clusters = &$lookup['clusters']['list'];
$attributes = &$lookup['attributes']['display'];
$costs = &$lookup['costs'];
$types = &$lookup['types']['display'];
$backsides = &$lookup['backsides']['code2name'];
$divinities = &$lookup['divinities'];
$rarities = &$lookup['rarities']['code2name'];

$emptyGif = asset('images/icons/blank.gif');

?>
<form
	method="GET"
	action="<?=url('/')?>"
	class="form"
	id="form_search"
>
	<input
		type="hidden"
		name="do"
		value="search"
	>
	
	<!-- Searchbar ========================================================== -->
	<div class="col-xs-12">
		<div id="searchbar">
			<div class="input-group">
				
				<!-- Syntax help button -->
				<span class="input-group-btn">
					<a
						href="<?=url('cards/search/help')?>"
						class="btn fd-btn-default btn-lg"
					>
						<i class="fa fa-info"></i>
						<span class="hidden-xs">Help</span>
					</a>
				</span>
				
				<!-- Searchbar input -->
				<input
					type="text"
					class="form-control input-lg"
					placeholder="Search for cards..."
					id="q"
					name="q"
					<?=isset($filters['q']) ? "value=\"{$filters['q']}\" " : ""?>
					<?=$thereWereResults ? '' : 'autofocus'?>
				>
				
				<!-- Search button -->
				<span class="input-group-btn">
					<button type="submit" class="btn btn-lg fd-btn-primary">
						<i class="fa fa-search"></i>
						<span class="hidden-xs">Search</span>
					</button>
				</span>

			</div>
		</div>
	</div>

	<?php if ($thereWereResults): // Filters and Options panels ?>
		<div class="col-xs-12">
			<div
				class="btn-group btn-group-justified fd-btn-group --border"
				role="group"
			>

				<!-- Filters -->
				<div class="btn-group" role="group">
					<button
						type="button"
						class="btn font-105 fd-btn-default js-hider js-panel-toggle"
						data-target="#hide-filters"
						data-open-icon="fa-times"
						data-closed-icon="fa-plus"
					>
						<i class="fa fa-plus text-muted"></i>
						Filters
					</button>
				</div>
				
				<!-- Options -->
				<div class="btn-group" role="group">
					<button
						type="button"
						class="btn font-105 fd-btn-default js-hider js-panel-toggle js-panel-toggle-options"
						data-target="#hide-options"
						data-open-icon="fa-times"
						data-closed-icon="fa-plus"
					>
						<i class="fa fa-plus text-muted"></i>
						Options
					</button>
				</div>
				
			</div>

			<!-- HACK: get some vertical space -->
			<p></p>

		</div>
  <?php endif; ?>
	
	<!-- Filters panel ====================================================== -->
	<div
		id="hide-filters"
		class="col-xs-12<?=$thereWereResults?' hidden':''?>"
	>
  	<div class="panel panel-default">
  		
      <!-- Filters Header -->
  		<div class="panel-heading">
  			<h3>
          <i class="fa fa-filter"></i>
					Filters

					<!-- Close button -->
					<?php if ($thereWereResults): ?>
						<button
							type="button"
							class="btn btn-xs fd-btn-default js-hider js-panel-toggle"
							data-target="#hide-filters"
							data-open-icon="fa-times"
							data-closed-icon="fa-plus"
						>
							<i class="fa fa-plus"></i>
							Close
						</button>
					<?php endif ;?>

					<!-- Reset -->
					<button type="button" class="btn btn-link btn-xs form-reset">
						Reset
					</button>

  			</h3>
  		</div>
  		
  		<!-- Filters Body -->
  		<div class="panel-body">
  			<div class="row">
  				
          <!-- Filters body: left -->
  				<div class="col-sm-12 col-md-6">
  					
  					<!-- SEARCHBAR OPTIONS ======================================== -->
            <div class="row filter">
              <div class="col-xs-12 filter-header">Search options</div>

                <!-- EXACT MATCH ========================================== -->
                <div class="col-xs-12 filter-subcontrol">

                  <?php // Sticky values
                    (isset($filters) AND !isset($filters['exact']))
                      ? [$active, $checked] = ['', '']
                      : [$active, $checked] = [' active', ' checked'];
         					?>

                  <!-- Description -->
                  <span class="filter-desc">Must contain every term..</span>

                  <!-- Button -->
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn fd-btn-default btn-xs<?=$active?>">
											<input
												type="checkbox"
												name="exact"
												value="1"<?=$checked?>
											>
                      <span class="pointer">Exact Match</span>
                    </label>
                  </div>
                </div>

                <!-- SEARCH ONLY IN.. ===================================== -->
                <div class="col-xs-12 filter-subcontrol">

                  <!-- Description -->
                  <span class="filter-desc">Search only in.. </span>
                  
                  <!-- Buttons -->
                  <div
										class="btn-group btn-group-xs fd-btn-group --separate"
										data-toggle="buttons"
									>
                      
                    <?php foreach ([
                      'name' => 'Names',
                      'code' => 'Codes',
                      'text' => 'Texts',
                      'race' => 'Races',
											'flavor_text' => 'Flavor',
                    ] as $field => $label):
                      // Sticky values
                      (isset($filters['infields']) AND in_array($field, $filters['infields']))
                        ? [$active, $checked] = [' active', 'checked']
                        : [$active, $checked] = ['', ''];
                    ?>
                      <!-- Button -->
                      <label class="btn fd-btn-default<?=$active?>">
												<input
													type="checkbox"
													name="infields[]"
													value="<?=$field?>"
													<?=$checked?>
												>
                        <span class="pointer"><?=$label?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- EXCLUDE ============================================== -->
                <div class="col-xs-12 filter-subcontrol">

                  <!-- Description -->
                  <span class="filter-desc">Exclude..</span>
                  
                  <!-- Buttons -->
                  <div
										class="btn-group btn-group-xs fd-btn-group --separate"
										data-toggle="buttons"
									>
                    <?php foreach ([
											'basics',
											'spoilers',
											'alternates',
											'reprints'
										] as $field):
                      // Sticky values
                      (isset($filters['exclude']) AND in_array($field, $filters['exclude']))
                        ? [$active, $checked] = [' active', 'checked']
                        : [$active, $checked] = ['', ''];
                    ?>
											<label class="btn fd-btn-default<?=$active?>">
												<input
													type="checkbox"
													name="exclude[]"
													value="<?=$field?>"
													<?=$checked?>
												>
                        <span class="pointer"><?=ucfirst($field)?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- SORT BY ============================================== -->
                <div class="col-xs-12 filter-subcontrol">
                    
									<?php // Sticky values
										$sort = $filters['sort'] ?? 'default';
										$sortDir = $filters['sortdir'] ?? 'asc';
										$active = $sortDir === 'desc' ? ' active' : '';
                  ?>
                  
                  <!-- Hidden inputs -->
									<input
										type="hidden"
										name="sort"
										id="sort"
										value="<?=$sort?>"
									>
									<input
										type="hidden"
										name="sortdir"
										id="sortdir"
										value="<?=$sortDir?>"
									>
                  
                  <!-- Description -->
                  <span class="filter-desc">Sort results by..</span>

                  <!-- Button group -->
                  <div
										class="btn-group btn-group-xs fd-btn-group --separate"
										data-toggle="buttons"
										id="sort-group"
									>
                    <div class="btn-group">

                      <!-- Selected field (click to open dropdown menu) -->
											<button
												type="button"
												class="btn fd-btn-default btn-xs dropdown-toggle"
												data-toggle="dropdown"
											>
                        <span class="dropdown-face">
													Select field...
												</span>
                        <span class="caret"></span>
                      </button>

                      <!-- Other fields -->
                      <ul class="dropdown-menu">

                        <li>
                          <a
                            class="seldrop_sort pointer"
                            data-field="default"
                            data-field-name="Select a field..."
                          >
                            Select a field...
                          </a>
                        </li>

                        <?php foreach ($sortables as $field => $label): ?>
                          <li>
														<a
															class="seldrop_sort pointer"
															data-field="<?=$field?>"
															data-field-name="<?=$label?>"
														>
															<?=$label?>
														</a>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </div>

                    <!-- Order direction button -->
										<button
											type="button"
											class="btn fd-btn-default btn-sm<?=$active?>" id="sortdir_handle"
										>
                      Descending
                    </button>
                  </div>
              </div>
            </div>
  					
            <!-- FORMAT =================================================== -->
  		      <div class="row filter">
              <div class="col-xs-12 filter-header">Format</div>
  						<div class="col-xs-12">
  							<div class="btg-group" data-toggle="buttons">
  								<?php foreach ($formats as $code => $name):
                    // Sticky values
  									if (isset($filters['format'])) {
  										($code === $filters['format'])
                        ? [$active, $checked] = [' active', 'checked=true']
                        : [$active, $checked] = ['', ''];
  									} else {
  										($code === $formatDefault)
                        ? [$active, $checked] = [' active', 'checked=true']
                        : [$active, $checked] = ['', ''];
  									}
  								?>
										<label
											class="btn btn-xs font-105 mv-10 fd-btn-default<?=$active?>"
										>
											<input
												type="checkbox"
												name="format[]"
												value="<?=$code?>"
												<?=$checked?>
											>
  										<span class="pointer">
												<?=str_replace(' Cluster', '', $name)?>
											</span>
  									</label>
  								<?php endforeach; ?>
  							</div>
  						</div>
  					</div>
  					
  					<!-- TYPE ===================================================== -->
  					<div class="row filter">
  						<div class="col-xs-12 filter-header">Type</div>
  						<div class="col-xs-12">
  							<div class="btg-group" data-toggle="buttons">
  								<?php foreach ($types as $value):
										// Sticky values
										$filter =& $filters['type'];
  									(isset($filter) && in_array($value, $filter))
                      ? [$active, $checked] = [' active', 'checked=true']
  										: [$active, $checked] = ['', ''];
  								?>
										<label
											class="btn btn-xs font-105 mv-10 fd-btn-default<?=$active?>"
										>
											<input
												type="checkbox"
												name="type[]"
												value="<?=$value?>"
												<?=$checked?>
											>
  										<span class="pointer"><?=$value?></span>
										</label>
  								<?php endforeach; ?>
  							</div>
  						</div>

  					</div>

            <!-- BACK SIDE ================================================ -->
            <div class="row filter">
              <div class="col-xs-12 filter-header">Back Side</div>
              <div class="col-xs-12">
                <div class="btg-group" data-toggle="buttons">
                  <?php foreach ($backsides as $code => $name):
                    (
											isset($filters['backside']) &&
											$filters['backside'] == $code
                    )
                      ? [$active, $checked] = [' active', 'checked']
                      : [$active, $checked] = ['', ''];
                  ?>
                    <label
											class="btn btn-xs font-105 mv-10 fd-btn-default<?=$active?>"
										>
                      <input
                        type="checkbox"
                        name="backside[]"
                        value="<?=$code?>"<?=$checked?>
                      >
                      <span class="pointer"><?=$name?></span>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

						<!-- DIVINITY ================================================= -->
  					<div class="row filter">
							<div class="col-xs-12 filter-header">Divinity</div>
  						<div class="col-xs-12">
  							<div
									class="btn-group fd-btn-group --separate"
									data-toggle="buttons"
								>
  								<?php foreach ($divinities as $value):
										// Sticky values
										$filter =& $filters['divinity'];
  									(isset($filter) && in_array($value, $filter))
                      ? [$active, $checked] = [' active', 'checked']
                      : [$active, $checked] = ['', ''];
  								?>
  									<label class="btn fd-btn-default font-110<?=$active?>">
											<input
												type="checkbox"
												name="divinity[]"
												value="<?=$value?>"
												<?=$checked?>
											>
  										<?=$value?>
  									</label>
  								<?php endforeach; ?>
  							</div>
  						</div>
  					</div>

  				</div><!-- /Left panel -->
  				
          <!-- Filters body: right -->
  				<div class="col-sm-12 col-md-6">
  				
  					<!-- SET ====================================================== -->
  					<div class="row filter">

  						<!-- Label -->
  						<div class="col-xs-12 filter-header">
  							Set
								<button
									type="button"
									class="btn btn-link btn-xs"
									id="setcode-multiple"
								>
									Multiple
								</button>
  						</div>

  						<!-- Control -->
  						<div class="col-xs-12">
  							<?php
									// Sticky values
									if (isset($filters['set'])) {
										if (is_array($filters['set'])) {
											[$multiple, $brackets, $size] = ['multiple', '[]', 'size10'];
										} else {
											[$multiple, $brackets, $size] = ['', '', ''];
											$filters['set'] = [ $filters['set'] ];
										}
									} else {
										[$multiple, $brackets, $size] = ['', '', ''];
									}
  							?>
								<select
									id="setcode"
									class="form-control"
									name="set<?=$brackets?>"
									<?=$multiple?>
									<?=$size?>
								>
  								<option name="set_default" value=0>Choose a set..</option>
  								<?php foreach ($clusters as $clusterCode => $cluster): ?>
  									<optgroup label="<?=$cluster['name']?>">
  									  <?php foreach ($cluster['sets'] as $setCode => $setName): ?>
											<?php // Sticky values
												(isset($filters['set']) && in_array($setCode, $filters['set']))
													? $checked = ' selected'
													: $checked = '';
											?>
											<option value="<?=$setCode?>"<?=$checked?>>
                        <?=$setName?> - (<?=strtoupper($setCode)?>)
                      </option>
  									 <?php endforeach; ?>
  									</optgroup>
  								<?php endforeach; ?>
  							</select>
  						</div>
  					</div>

  					<!-- ATTRIBUTES =============================================== -->
  					<div class="row filter">
  						<div class="col-xs-12 filter-header">Attributes</div>
  						<div class="col-xs-12">
  							<div
									class="btn-group fd-btn-group --separate"
									data-toggle="buttons"
								>
  								<?php foreach ($attributes as $code => $name):
										// Sticky values
										(
											isset($filters['attributes']) &&
											in_array($code, $filters['attributes'])
										)
											? [$active, $checked] = [' active', 'checked']
                      : [$active, $checked] = ['', ''];
  								?>
  									<label class="btn fd-btn-default<?=$active?>">
  										<input
												type="checkbox"
												name="attributes[]"
												value="<?=$code?>"
												<?=$checked?>												
											>
                      <img
												src="<?=$emptyGif?>"
												class="fd-icon-<?=$code?> --bigger"
											>
  									</label>
  								<?php endforeach; ?>
  							</div>

                <!-- Vertical separator -->
                <p></p>

                <!-- Multi-attribute and must contain selected switches -->
                <div
									class="btg-group btn-group-xs fd-btn-group --separate"
									data-toggle="buttons"
								>

                  <!-- No multi-attribute -->
                  <?php
                    isset($filters['no_attribute_multi'])
                      ? [$active, $checked] = [' active', 'checked']
                      : [$active, $checked] = ['', ''];
                  ?>
                  <label class="btn fd-btn-default<?=$active?>">
										<input
											type="checkbox"
											name="no_attribute_multi"
											value="1"
											<?=$checked?>
										>
                    <span class="pointer">No Multi-Attribute</span>
                  </label>

                  <!-- Only multi-attribute -->
                  <?php
										(
											isset($filters['attrmulti']) ||
											isset($filters['attribute_multi'])
										)
                      ? [$active, $checked] = [' active', 'checked']
                      : [$active, $checked] = ['', ''];
                  ?>
                  <label class="btn fd-btn-default<?=$active?>">
										<input
											type="checkbox"
											name="attribute_multi"
											value="1"
											<?=$checked?>
										>
                    <span class="pointer">Only Multi-Attribute</span>
                  </label>

                  <!-- Must contain just selected -->
                  <?php
                      (
												isset($filters['attrselected']) ||
												isset($filters['attribute_selected'])
											)
                        ? [$active, $checked] = [' active', 'checked']
                        : [$active, $checked] = ['', ''];
                  ?>
                  <label class="btn fd-btn-default<?=$active?>">
										<input
											type="checkbox"
											name="attribute_selected"
											value="1"
											<?=$checked?>
										>
                    <span class="pointer">Must contain just selected</span>
                  </label>
                </div>
  						</div>
  					</div>
  					
  					
  					<!-- TOTAL COST =============================================== -->
  					<div class="row filter">
  						<div class="col-xs-12 filter-header">Total Cost</div>
  						<div class="col-xs-12">
  							<div
									class="btn-group fd-btn-group --separate"
									data-toggle="buttons"
								>
  								<?php foreach ($costs as $cost):
  									// Sticky values
  									(
											isset($filters['total_cost']) &&
											in_array($cost, $filters['total_cost'])
										)
                      ? [$active, $checked] = [' active', 'checked']
                      : [$active, $checked] = ['', ''];
  								?>
  									<label class="btn fd-btn-default font-110<?=$active?>">
											<input
												type="checkbox"
												name="total_cost[]" value="<?=$cost?>"
												<?=$checked?>
											>
  										<?=$cost?>
  									</label>
									<?php endforeach; ?>
									
                  <!-- X cost -->
									<?php
										isset($filters['xcost'])
											? [$active, $checked] = [' active', 'checked']
											: [$active, $checked] = ['', ''];
									?>
                  <label class="btn fd-btn-default font-110<?=$active?>">
										<input
											type="checkbox"
											name="xcost"
											value="1"
											<?=$checked?>
										>X
                  </label>
  							</div>
  						</div>
  					</div>
  					
  					<!-- RARITY =================================================== -->
  					<div class="row filter">
  						<div class="col-xs-12 filter-header">Rarity</div>
  						<div class="col-xs-12">
  							<div
									class="btn-group fd-btn-group --separate"
									data-toggle="buttons"
								>
  								<?php foreach ($rarities as $code => $name):
  										// Sticky values
  										(
												isset($filters['rarity']) &&
												in_array($code, $filters['rarity'])
											)
                        ? [$active, $checked] = [' active', 'checked']
                        : [$active, $checked] = ['', ''];
  									?>
  									<label class="btn fd-btn-default font-110<?=$active?>">
											<input
												type="checkbox"
												name="rarity[]"
												value="<?=$code?>"
												<?=$checked?>
											>
  										<?=strtoupper($code)?>
  									</label>
  								<?php endforeach; ?>
  							</div>
  						</div>
  					</div>
  					
  					
  					<!-- RACE/TRAIT =============================================== -->
  					<div class="row filter">
  						<div class="col-xs-12 filter-header">Race/Trait</div>
  						<div class="col-xs-12">
  							<input
									type="text"
									class="form-control"
									name="race"
									placeholder="Race or Trait (exact)..."
									value="<?=$filters['race'] ?? ''?>"
								>
  						</div>
  					</div>


						<!-- ARTIST =================================================== -->
  					<div class="row filter">
  						<div class="col-xs-12 filter-header">Artist / Illustrator</div>
  						<div class="col-xs-12">
  							<input
									type="text"
									class="form-control"
									name="artist"
									placeholder="Artist name (exact)..."
									value="<?=$filters['artist'] ?? ''?>"
								>
  						</div>
  					</div>
  					
  					
  					<!-- ATK/DEF -->
  					<div class="row filter">

							<?php
								// Label => symbol
								$operators = [
									'lessthan' => '&lt;',
									'equals' => '=',
									'greaterthan' => '&gt;',
								];
								$atk_operator = $filters['atk-operator'] ?? 'equals';
								$def_operator = $filters['def-operator'] ?? 'equals';
							?>

							<!-- Attack ================================================= -->
							<div class="col-xs-12 col-sm-6">
								<div class="col-xs-12 filter-header">Attack</div>

  							<!-- Hidden input -->
								<input
									type="hidden"
									name="atk-operator"
									id="atk-operator"
									value="<?=$atk_operator?>"
								>
  							
  							<!-- Controls -->
  							<div class="input-group" id="atk-group">
  								<div class="input-group-btn">

										<!-- Face -->
										<button
											type="button"
											class="btn fd-btn-default dropdown-toggle"
											data-toggle="dropdown"
										>
											<span class="dropdown-face">
												<?=$operators[$atk_operator]?>
											</span>
  										<span class="caret"></span>
										</button>
										
										<!-- Dropdown -->
  									<ul class="dropdown-menu">
											<?php foreach($operators as $value => $label): ?>
												<li>
													<a
														class="seldrop_atk-operator pointer"
														data-words="<?=$value?>"
														data-symbol="<?=$label?>"
													>
														<?=$label?>
													</a>
												</li>
											<?php endforeach; ?>
  									</ul>
									</div>
									
									<!-- Numerical input -->
									<input
										type="number"
										name="atk"
										id="atk"
										class="form-control"
										placeholder="Attack.."
										value="<?=$filters['atk'] ?? ''?>"
									>
  							</div>
							</div>
  						
  						<!-- Defense ================================================ -->
							<div class="col-xs-12 col-sm-6">
								<div class="col-xs-12 filter-header">Defense</div>

  							<!-- Hidden input -->
								<input
									type="hidden"
									name="def-operator"
									id="def-operator"
									value="<?=$def_operator?>"
								>
  							
  							<!-- Controls -->
  							<div class="input-group" id="def-group">
  								<div class="input-group-btn">

										<!-- Face -->
										<button
											type="button"
											class="btn fd-btn-default dropdown-toggle"
											data-toggle="dropdown"
										>
											<span class="dropdown-face">
												<?=$operators[$def_operator]?>
											</span>
  										<span class="caret"></span>
										</button>
										
										<!-- Dropdown -->
  									<ul class="dropdown-menu">
											<?php foreach($operators as $value => $label): ?>
												<li>
													<a
														class="seldrop_def-operator pointer"
														data-words="<?=$value?>"
														data-symbol="<?=$label?>"
													>
														<?=$label?>
													</a>
												</li>
											<?php endforeach; ?>
  									</ul>
									</div>
									
									<!-- Numerical input -->
									<input
										type="number"
										name="def"
										id="def"
										class="form-control"
										placeholder="Defense.."
										value="<?=$filters['def'] ?? ''?>"
									>
  							</div>
							</div>

  					</div>
  				</div>	
  			</div>
  		</div>

  		<!-- Filters Footer -->
  		<div class="panel-footer text-right">

				<button
					type="button"
					class="btn btn-lg btn-link form-reset"
				>
					Reset
  			</button>

				<button
					type="submit"
					class="btn btn-lg fd-btn-primary"
					id="form_submit_bottom"
				>
					<i class="fa fa-search"></i>
					Search
  			</button>

  		</div>

  	</div>
  </div>
</form>
