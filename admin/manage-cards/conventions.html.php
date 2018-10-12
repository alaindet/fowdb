<div class="well">
	<h3>Conventions used</h3>
	<ul class="fdb-list">
		<li>
			Free costs equal to X are stored into the db as -1, so put -1 as a convention for X into the free cost input
		</li>
	</ul>
	<table class="table table-striped table-condensed table-responsive ruling-table">
		<!-- Headers -->
		<thead>
			<tr>
				<th><h4>Syntax</h4></th>
				<th><h4>Render</h4></th>
				<th><h4>Description</h4></th>
			</tr>
		</thead><!-- /Headers -->
		
		<!-- Table body -->
		<tbody>
		
			<!-- Newline -->
			<tr>
				<td><kbd>&lt;br&gt;</kbd></td>
				<td></td>
				<td>New line (br: break)</td>
			</tr>
			
			<!-- Vertical separator -->
			<tr>
				<td>
					<kbd>&lt;hr&gt;</kbd>
				</td>
				<td></td>
				<td>Leaves a bit of vertical space (hr: horizontal rule, used in some cards' text to separate abilities)</td>
			</tr>
			
			<!-- Right arrow -->
			<tr>
				<td>
					<kbd>=></kbd>
				</td>
				<td>&rArr;</td>
				<td>Right arrow (For new automatic abilities, from Vingolf 2 on)</td>
			</tr>

			<!-- Errata -->
			<tr>
				<td><kbd>&ndash;errata&ndash;Text&ndash;/errata&ndash;</kbd></td>
				<td><?=render("-errata-Text-/errata-")?></td>
				<td>
					Highlights errata text in blue in card text. Mind absence of any white space near -ERRATA-. Note: errata'd cards should also have a separate errata ruling as well. Read Conventions table on admin/rulings page as a reference.
				</td>
			</tr>
			
			<!-- Rest -->
			<tr>
				<td><kbd>{rest}</kbd></td>
				<td><?=render("{rest}")?></td>
				<td>Rest symbol</td>
			</tr>
			
			<!-- Attribute symbols -->
			<tr>
				<td><kbd>{w}, {r}</kbd></td>
				<td><?=render("{w}, {r}")?></td>
				<td>Attribute symbols (w&rarr;Light, r&rarr;Fire, u&rarr;Water, g&rarr;Wind, b&rarr;Dark, v&rarr;Void, m&rarr;Moon, t&rarr;Will of Time)</td>
			</tr>
			
			<!-- Free will symbols -->
			<tr>
				<td><kbd>{0}, {1}, {x}</kbd></td>
				<td><?=render("{0}, {1}, {x}")?></td>
				<td>Free will symbols</td>
			</tr>
			
			<!-- Symbol skills -->
			<tr>
				<td><kbd>[_Target&nbsp;Attack_]</kbd></td>
				<td><?=render("[_Target&nbsp;Attack_]")?></td>
				<td>Abilities (new)</td>
			</tr>
			
			<!-- Ability labels -->
			<tr>
				<td><kbd>[Activate]</kbd></td>
				<td><?=render("[Activate]")?></td>
				<td>Abilities (legacy)</td>
			</tr>
			
			<!-- < and > -->
			<tr>
				<td><kbd>&amp;lt;Tea-Party&amp;gt;</kbd></td>
				<td><?=render("&lt;Tea-Party&gt;")?></td>
				<td>Less (&lt;) and more than (&gt;) signs must be entered like <kbd>&amp;lt;</kbd> and <kbd>&amp;gt;</kbd></td>
			</tr>
		</tbody><!-- /Table body -->
	</table>
</div>
