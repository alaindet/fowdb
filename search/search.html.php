<?php
	global $filters; // Passed filters
	global $thereWereResults; // Flags if there were results;

	$helpers = [
		"formats",
		"clusters",
		"attributes",
		"costs",
		"types",
		"backsides",
		"rarities",
		"sortfields"
	];

	foreach ($helpers as &$helper) {
		$$helper = \App\Legacy\Helpers::get($helper);
	}
?>
<form method="GET" action="/" class="form" id="form_search">
	<input type="hidden" name="do" value="search">
	
	<!-- Searchbar ========================================================== -->
	<div id="searchbar" class="col-xs-12">
		<div class="input-group">
			
      <!-- Syntax help button -->
			<span class="input-group-btn">
				<button type="button" class="btn btn-default js-hider" data-target="#hide-syntax-help">
					<!-- Hack --><span class="visible-xs-inline" style="visibility:hidden;">i</span>
					<i class="fa fa-chevron-right"></i>
					<span class="visible-xs-inline"></span>
					<span class="hidden-xs">Help</span>
				</button>
			</span>
			
      <!-- Searchbar input -->
			<input type="text" class="form-control" placeholder="Name, code, text, race, flavor.." id="q" name="q"<?php
				echo isset($filters['q']) ? " value=\"{$filters['q']}\" " : '';
				echo $thereWereResults ? "": " autofocus";
			?>>

			<!-- Search button -->
			<span class="input-group-btn">
				<button type="submit" class="btn fdb-btn">
					<span class="glyphicon glyphicon-search"></span>
					<!-- Hack --><span class="visible-xs-inline" style="visibility:hidden;">i</span>
					<span class="hidden-xs">Search</span>
				</button>
			</span>

		</div>
	</div>
	
	<!-- Syntax help panel ================================================== -->
	<div class="col-xs-12 hidden" id="hide-syntax-help">
		
    <!-- Description -->
    <div class="well well-sm" id="syntax-help-box">
			<p>
				The above searchbar performs a search into every card's <em>name</em>, <em>code</em>, <em>text</em>, <em>subtype</em> and <em>race</em>. Use the Filters section below to restrict your search. If you click on <em>Multiple</em>, next to the <em>Set</em> filter, you can select multiple sets while holding CTRL. You can add special syntax to the query too, like this:
			</p>	
			<div class="row">
				<div class="col-xs-4 col-sm-3 col-md-1">
					<ul class="list-unstyled">
						<li>{w} = <img src="images/icons/1x1.gif" class="fdb-icon-w" /></li>
						<li>{r} = <img src="images/icons/1x1.gif" class="fdb-icon-r" /></li>
						<li>{u} = <img src="images/icons/1x1.gif" class="fdb-icon-u" /></li>
						<li>{g} = <img src="images/icons/1x1.gif" class="fdb-icon-g" /></li>
						<li>{b} = <img src="images/icons/1x1.gif" class="fdb-icon-b" /></li>
						<li>{m} = <img src="images/icons/1x1.gif" class="fdb-icon-m" /></li>
						<li>{t} = <img src="images/icons/1x1.gif" class="fdb-icon-t" /></li>
					</ul>
				</div>
				<div class="col-xs-8 col-sm-9 col-md-11">
					<ul class="list-unstyled">
						<li>{1} = <span class="fdb-icon-free">1</span></li>
						<li>{x} = <span class="fdb-icon-free">x</span></li>
						<li>{rest} = <img src="images/icons/1x1.gif" class="fdb-icon-rest" /></li>
						<li>[Enter] = <span class="mark_abilities">Enter</span> (old abilities)</li>
						<li>[_Flying_] = <span class="mark_skills">Flying</span> (new abilities)</li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<ul class="fdb-list">
            <li>
              <span class="btn btn-xs btn-default">Exact Match</span> is default. Results MUST contain ALL search terms you type. Uncheck it to have results containing AT LEAST one of your search terms (usually more results).
            </li>
						<li>
							Type <code>foo bar</code>: if Exact Match is on, you get cards with "foo" AND "bar" in them, if Exact Match is off, you get cards with "foo" OR "bar" in them (usually more results)
						</li>
						<li>
							Type <code>`foo bar`</code> (mind the backticks) to get cards with exactly "foo bar" in them
						</li>
            <li>
              The button <span class="btn btn-xs btn-default">Only Multi-Attribute</span> will get only cards with more than one attribute, thus excluding single attribute cards
            </li>
            <li>
              The button <span class="btn btn-xs btn-default">Must contain just selected</span> will get cards with just selected attributes. Best paired with <span class="btn btn-xs btn-default">Only Multi-Attribute</span> enabled, since when multi-attribute is disabled this button is ignored.
            </li>
					</ul>
				</div>
			</div>
		</div>
	</div>

  <?php if ($thereWereResults): ?>
    <!-- Panels buttons -->
    <div class="col-xs-12">
      <div class="btn-group btn-group-justified" role="group">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-default js-hider js-panel-toggle" data-target="#hide-filters" data-open-icon="fa-times" data-closed-icon="fa-plus">
            <i class="fa fa-plus text-muted"></i>&nbsp;Filters
          </button>
        </div>
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-default js-hider js-panel-toggle" data-target="#hide-options" data-open-icon="fa-times" data-closed-icon="fa-plus" id="js-panel-toggle-options">
            <i class="fa fa-plus text-muted"></i>&nbsp;Options
          </button>
        </div>
      </div>
    </div>
    <br><br><br><br><br>
  <?php endif; ?>
	
	<!-- Filters panel ====================================================== -->
  <div id="hide-filters" class="col-xs-12<?=$thereWereResults?' hidden':''?>">
  	<div class="panel panel-default">
  		
      <!-- Filters Header -->
  		<div class="panel-heading">
  			<h4>
          <i class="fa fa-filter"></i>
					Filters
  				<button type="button" class="btn btn-link btn-xs form-reset">Reset</button>
  			</h4>
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
                      ? list($active, $checked) = ['', '']
                      : list($active, $checked) = [' active', ' checked=true'];
                  ?>

                  <!-- Description -->
                  <span class="filter-desc">Must contain every term..</span>

                  <!-- Button -->
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default btn-xs <?=$active?>">
                      <input type="checkbox" name="exact" value="1"<?=$checked?>>
                      <span class="pointer">Exact Match</span>
                    </label>
                  </div>
                </div>

                <!-- SEARCH ONLY IN.. ===================================== -->
                <div class="col-xs-12 filter-subcontrol">

                  <!-- Description -->
                  <span class="filter-desc">Search only in.. </span>
                  
                  <!-- Buttons -->
                  <div class="btn-group" data-toggle="buttons">
                      
                    <?php foreach ([
                      "cardname" => "Names",
                      "code" => "Codes",
                      "cardtext" => "Texts",
                      "subtype_race" => "Races",
											"flavortext" => "Flavor",
                    ] as $field => $label):
                      // Sticky values
                      (isset($filters['infields']) AND in_array($field, $filters['infields']))
                        ? list($active, $checked) = [' active', ' checked=true']
                        : list($active, $checked) = ['', ''];
                    ?>
                      <!-- Button -->
                      <label class="btn btn-default btn-xs separate<?=$active?>">
                        <input type="checkbox" name="infields[]" value="<?=$field?>"<?=$checked?>>
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
                  <div class="btn-group" data-toggle="buttons">
                    <?php foreach (['basics', 'spoilers', 'alternates', 'reprints'] as $field):
                      // Sticky values
                      (isset($filters['exclude']) AND in_array($field, $filters['exclude']))
                        ? list($active, $checked) = [' active', ' checked=true']
                        : list($active, $checked) = ['', ''];
                    ?>
                      <label class="btn btn-default btn-xs separate<?=$active?>">
                        <input type="checkbox" name="exclude[]" value="<?=$field?>"<?=$checked?>>
                        <span class="pointer"><?=ucfirst($field)?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- SORT BY ============================================== -->
                <div class="col-xs-12 filter-subcontrol">
                    
                  <?php // Sticky values
                    $sort = isset($filters['sort']) ? $filters['sort'] : 'default';
                    $sortDir = (isset($filters['sortdir']) AND $filters['sortdir'] == "desc") ? "desc" : "asc";
                    $active = $sortDir == "desc" ? " active" : "";
                  ?>
                  
                  <!-- Hidden inputs -->
                  <input type="hidden" name="sort" id="sort" value="<?=$sort?>">
                  <input type="hidden" name="sortdir" id="sortdir" value="<?=$sortDir?>">
                  
                  <!-- Description -->
                  <span class="filter-desc">Sort results by..</span>

                  <!-- Button group -->
                  <div class="btn-group btn-group-xs inline" id="sort-group">
                    <div class="btn-group">

                      <!-- Selected field (click to open dropdown menu) -->
                      <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                        <span class="dropdown-face"><?=$sortfields[$sort]?></span>
                        <span class="caret"></span>
                      </button>

                      <!-- Other fields -->
                      <ul class="dropdown-menu">
                        <?php foreach ($sortfields as $field => &$label): ?>
                          <li>
                            <a class="seldrop_sort pointer" data-field="<?=$field?>" data-field-name="<?=$label?>"><?=$label?></a>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </div>

                    <!-- Order direction button -->
                    <button type="button" class="btn btn-default btn-sm<?=$active?>" id="sortdir_handle">
                      Descending
                    </button>
                  </div>
              </div>
            </div>
  					
            <!-- FORMAT -->
  		      <div id="filter-format" class="row filter">
              
              <!-- Label -->
              <div class="col-xs-12 filter-header">Format</div>

  						<!-- Controls -->
  						<div class="col-xs-12 filter-controls">

  							<!-- Button group -->
  							<div class="btg-group" data-toggle="buttons">
  								<?php foreach ($formats['list'] as $fcode => &$format):
                    // Sticky values
  									if (isset($filters['format'])) {
  										($fcode == $filters['format'])
                        ? list($active, $checked) = [' active', ' checked=true']
                        : list($active, $checked) = ['', ''];
  									} else {
  										($fcode == $formats['default'])
                        ? list($active, $checked) = [' active', ' checked=true']
                        : list($active, $checked) = ['', ''];
  									}
  								?>
  								  <label class="btn btn-default btn-sm separate<?=$active?>">
  										<input type="radio" name="format" value="<?=$fcode?>"<?=$checked?>>
  										<span class="pointer"><?=str_replace(" Cluster", "", $format['name'])?></span>
  									</label>
  								<?php endforeach; ?>
  							</div>
  						</div>
  					</div>
  					
  					<!-- TYPE ===================================================== -->
  					<div class="row filter">

  						<!-- Label -->
  						<div class="col-xs-12 filter-header">Type</div>

  						<!-- Controls -->
  						<div class="col-xs-12 filter-controls">
  							<div class="btg-group" data-toggle="buttons">
  								<?php foreach ($types as &$value):
										// Sticky values
										$filter =& $filters['type'];
  									(isset($filter) && in_array($value, $filter))
                      ? [$active, $checked] = [' active', ' checked=true']
  										: [$active, $checked] = ['', ''];
  								?>
  									<label class="btn btn-default btn-sm separate<?=$active?>">
  										<input type="checkbox" name="type[]" value="<?=$value?>"<?=$checked?>>
  										<span class="pointer"><?=$value?></span>
  									</label>
                    <?=$value==="Special Magic Stone"?"<div class='fdb-separator-v-05'></div>":""?>
  								<?php endforeach; ?>
  							</div>
  						</div>

  					</div>

            <!-- BACKSIDE ================================================= -->
            <div class="row filter">
              
              <!-- Label -->
              <div class="col-xs-12 filter-header">
                Back Side
                <button id="backside-clear" type="button" class="btn btn-link btn-xs">
                  Clear
                </button>
              </div>
              
              <!-- Controls -->
              <div class="col-xs-12 filter-controls">
                <div class="btg-group" data-toggle="buttons">
                  <?php foreach ($backsides as &$backside):
                    (
                      isset($filters['backside']) &&
                      $filters['backside'] == $backside['code']
                    )
                      ? list($active, $checked) = [' active', ' checked=true']
                      : list($active, $checked) = ['', ''];
                  ?>
                    <label class="btn btn-default btn-sm separate<?=$active?>">
                      <input
                        type="checkbox"
                        name="backside[]"
                        value="<?=$backside['code']?>"<?=$checked?>
                      >
                      <span class="pointer"><?=$backside['name']?></span>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div><!-- /Controls -->
            </div><!-- /Backside -->

						<!-- DIVINITY ================================================= -->
  					<div class="row filter">
							<!-- Label -->
							<div class="col-xs-12 filter-header">Divinity</div>
							<!-- Control -->
  						<div class="col-xs-12 filter-controls">
  							<div class="btn-group" data-toggle="buttons">
  								<?php foreach ([1,2,3,4,5,6,7,8,9] as $value):
										// Sticky values
										$filter =& $filters['divinity'];
  									(isset($filter) && in_array($value, $filter))
                      ? list($active, $checked) = [' active', ' checked=true']
                      : list($active, $checked) = ['', ''];
  								?>
  									<label class="btn btn-default<?=$active?>">
  										<input type="checkbox" name="divinity[]" value="<?=$value?>"<?=$checked?>>
  										<?=$value?>
  									</label>
  								<?php endforeach; ?>
  							</div>
  						</div><!-- /Controls -->
  					</div><!-- /Divinity -->

  				</div><!-- /Left panel -->
  				
          <!-- Filters body: right -->
  				<div class="col-sm-12 col-md-6">
  				
  					<!-- SET ====================================================== -->
  					<div class="row filter">

  						<!-- Label -->
  						<div class="col-xs-12 filter-header">
  							Set
  							<button type="button" class="btn btn-link btn-xs" id="setcode-multiple">Multiple</button>
  						</div>

  						<!-- Control -->
  						<div class="col-xs-12 filter-controls">
  							<?php
  								// Sticky values
                  (!isset($filters['setcode']) OR !is_array($filters['setcode']))
                    ? list($multiple, $brackets, $size) = ['', '', ''] // Single (default)
                    : list($multiple, $brackets, $size) = [' multiple="multiple"', '[]', ' size=10']; // Multiple
  							?>
  							<select id="setcode" class="form-control input-sm" name="setcode<?=$brackets?>"<?=$multiple?><?=$size?>>
  								
                  <!-- Default option -->
  								<option name="set_default" value=0>Choose a set..</option>

  								<?php foreach ($clusters as $cid => &$cluster): ?>
  									<optgroup label="<?=$cluster['name']?>">
  									  <?php foreach ($cluster['sets'] as $scode => &$sname):
  											// Sticky values
                        $s =& $filters['setcode'];
  											(isset($s) AND ((is_array($s) AND in_array($scode, $s)) OR $scode == $s))
                          ? $checked = ' selected=true'
                          : $checked = '';
  										?>
  										<option value="<?=$scode?>"<?=$checked?>>
                        <?=$sname?> - (<?=strtoupper($scode)?>)
                      </option>
  									 <?php endforeach; ?>
  									</optgroup>
  								<?php endforeach; ?>

  							</select>
  						</div>
  					</div>

  					<!-- ATTRIBUTES =============================================== -->
  					<div class="row filter">

  						<!-- Label -->
  						<div class="col-xs-12 filter-header">Attributes</div>
  						
              <!-- Controls -->
  						<div class="col-xs-12 filter-controls">

                <!-- Buttons with icons -->
  							<div class="btn-group" data-toggle="buttons">
  								<?php foreach ($attributes as $index => &$attr):
  								  // Sticky values
                    isset($filters['attributes']) AND in_array($index, $filters['attributes'])
                      ? list($active, $checked) = [' active', ' checked=true']
                      : list($active, $checked) = ['', ''];
  								?>
  									<label class="btn btn-default<?=$active?>">
  										<input type="checkbox" name="attributes[]" value="<?=$index?>"<?=$checked?>>
                      <img src="images/icons/1x1.gif" class="fdb-icon-<?=$index?>">
  									</label>
  								<?php endforeach; ?>
  							</div>

                <!-- Vertical separator -->
                <div class='fdb-separator-v-05'></div>

                <!-- Multi-attribute and must contain selected switches -->
                <div class="btg-group" data-toggle="buttons">

                  <!-- No multi-attribute -->
                  <?php
                    isset($filters['no_attribute_multi'])
                      ? list($active, $checked) = [' active', ' checked=true']
                      : list($active, $checked) = ['', ''];
                  ?>
                  <label class="btn btn-xs btn-default<?=$active?>">
                    <input type="checkbox" name="no_attribute_multi" value="1"<?=$checked?>>
                    <span class="pointer">No Multi-Attribute</span>
                  </label>

                  <!-- Only multi-attribute -->
                  <?php
                    (isset($filters['attrmulti']) || isset($filters['attribute_multi']))
                      ? list($active, $checked) = [' active', ' checked=true']
                      : list($active, $checked) = ['', ''];
                  ?>
                  <label class="btn btn-xs btn-default<?=$active?>">
                    <input type="checkbox" name="attribute_multi" value="1"<?=$checked?>>
                    <span class="pointer">Only Multi-Attribute</span>
                  </label>

                  <!-- Must contain just selected -->
                  <?php
                      (isset($filters['attrselected']) || isset($filters['attribute_selected']))
                        ? list($active, $checked) = [' active', ' checked=true']
                        : list($active, $checked) = ['', ''];
                  ?>
                  <label class="btn btn-xs btn-default<?=$active?>">
                    <input type="checkbox" name="attribute_selected" value="1"<?=$checked?>>
                    <span class="pointer">Must contain just selected</span>
                  </label>
                </div>
  						</div>
  					</div>
  					
  					
  					<!-- TOTAL COST =============================================== -->
  					<div class="row filter">

  						<!-- Label -->
  						<div class="col-xs-12 filter-header">Total Cost</div>
  						
              <!-- Control -->
  						<div class="col-xs-12 filter-controls">
  							<div class="btn-group" data-toggle="buttons">
  								<?php foreach ($costs as &$cost):
  									// Sticky values
  									(isset($filters['total_cost']) AND in_array($cost, $filters['total_cost']))
                      ? list($active, $checked) = [' active', ' checked=true']
                      : list($active, $checked) = ['', ''];
  								?>
  									<label class="btn btn-default<?=$active?>">
  										<input type="checkbox" name="total_cost[]" value="<?=$cost?>"<?=$checked?>>
  										<?=$cost?>
  									</label>
  								<?php endforeach; ?>
                  <!-- X cost -->
                  <?php list($active, $checked) = isset($filters['xcost']) ? [' active', ' checked=true'] : ['', '']; ?>
                  <label class="btn btn-default<?=$active?>">
                    <input type="checkbox" name="xcost" value="1"<?=$checked?>>X
                  </label>
  							</div><!-- /Buttons -->
  						</div><!-- /Controls -->
  					</div><!-- /Cost -->
  					
  					<!-- RARITY =================================================== -->
  					<div class="row filter">

  						<!-- Label -->
  						<div class="col-xs-12 filter-header">Rarity</div>

  						<!-- Controls -->
  						<div class="col-xs-12 filter-controls">
  							<div class="btn-group" data-toggle="buttons">
  								<?php foreach ($rarities as $code => &$name):
  										// Sticky values
  										(isset($filters['rarity']) AND in_array($code, $filters['rarity']))
                        ? list($active, $checked) = [' active', ' checked=true']
                        : list($active, $checked) = ['', ''];
  									?>
  									<label class="btn btn-default<?=$active?>">
  										<input type="checkbox" name="rarity[]" value="<?=$code?>"<?=$checked?>>
  										<?=strtoupper($code)?>
  									</label>
  								<?php endforeach; ?>
  							</div>
  						</div>
  					</div>
  					
  					
  					<!-- RACE/TRAIT =============================================== -->
  					<div class="row filter">
  						<div class="col-xs-12 filter-header">Race/Trait</div>
  						<div class="col-xs-12 filter-controls">
  							<input
									type="text"
									class="form-control input-sm"
									name="race"
									placeholder="Race or Trait (exact)..."
									value="<?=$filters['race'] ?? ''?>"
								>
  						</div>
  					</div>


						<!-- ARTIST =================================================== -->
  					<div class="row filter">
  						<div class="col-xs-12 filter-header">Artist / Illustrator</div>
  						<div class="col-xs-12 filter-controls">
  							<input
									type="text"
									class="form-control input-sm"
									name="artist"
									placeholder="Artist name (exact)..."
									value="<?=$filters['artist'] ?? ''?>"
								>
  						</div>
  					</div>
  					
  					
  					<!-- ATK/DEF -->
  					<div class="row filter">

  						<!-- Labels -->
  						<div class="col-xs-6 filter-header">Attack</div>
  						<div class="col-xs-6 filter-header">Defense</div>

  						<!-- ATK ==================================================== -->
  						<div class="col-xs-6 filter-controls">

  							<?php
                  // Label => symbol
                  $operators = ['equals' => '=', 'greaterthan' => '>', 'lessthan' => '<'];
  								
                  // Sticky - ATK operator
  								$atk_operator = isset($filters['atk-operator']) ? $filters['atk-operator'] : 'equals';

  								// Sticky - ATK
  								$atk = isset($filters['atk']) ? $filters['atk'] : '';
  							?>

  							<!-- Hidden input -->
  							<input type="hidden" name="atk-operator" id="atk-operator" value="<?=$atk_operator?>">
  							
  							<!-- Controls -->
  							<div class="input-group" id="atk-group">
  								<div class="input-group-btn">
  									<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
  										<span class="dropdown-face"><?=$operators[$atk_operator]?></span>
  										<span class="caret"></span>
  									</button>
  									<ul class="dropdown-menu">
  										<li><a class="seldrop_atk-operator pointer" data-words="equals" data-symbol="=">=</a></li>
  										<li><a class="seldrop_atk-operator pointer" data-words="greaterthan" data-symbol=">">&gt;</a></li>
  										<li><a class="seldrop_atk-operator pointer" data-words="lessthan" data-symbol="<">&lt;</a></li>
  									</ul>
  								</div>
  								<input id="atk" class="form-control input-sm" type="text" name="atk" placeholder="ATK.." value="<?=$atk?>">
  							</div>
  						</div>
  						
  						<!-- DEF ==================================================== -->
  						<div class="col-xs-6 filter-controls">
  						
  							<?php
  								// Sticky - DEF operator
  								$def_operator = isset($filters['def-operator']) ? $filters['def-operator'] : 'equals';
  									
  								// Sticky - ATK
  								$def = isset($filters['def']) ? $filters['def'] : '';
  							?>
  							
  							<!-- Hidden input -->
  							<input type="hidden" name="def-operator" id="def-operator" value="<?=$def_operator?>" />
  							
  							<!-- Controls -->
  							<div class="input-group" id="def-group">
  								<div class="input-group-btn">
  									<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
  										<span class="dropdown-face"><?=$operators[$def_operator]?></span>
  										<span class="caret"></span>
  									</button>
  									<ul class="dropdown-menu">
  										<li><a class="seldrop_def-operator pointer" data-words="equals" data-symbol="=">=</a></li>
  										<li><a class="seldrop_def-operator pointer" data-words="greaterthan" data-symbol=">">&gt;</a></li>
  										<li><a class="seldrop_def-operator pointer" data-words="lessthan" data-symbol="<">&lt;</a></li>
  									</ul>
  								</div>
  								<input id="def" class="form-control input-sm" type="text" name="def" placeholder="DEF.." value="<?=$def?>">
  							</div>
  						</div><!-- /DEF -->

  					</div>
  				</div>	
  			</div>
  		</div>

  		<!-- Filters Footer -->
  		<div class="panel-footer">
  			<button type="button" class="btn btn-default btn-action form-reset">
  				<span class="glyphicon glyphicon-remove"></span> Reset
  			</button>
  			<button type="submit" class="btn fdb-btn btn-action" id="form_submit_bottom">
  				<span class="glyphicon glyphicon-search"></span> Search
  			</button>
  		</div>

  	</div>
  </div>
</form>
