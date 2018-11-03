<h2>Text conventions</h2>

<table class="table table-striped table-condensed table-responsive ruling-table">
  <!-- Headers -->
  <thead>
    <tr>
      <th><h4>Syntax</h4></th>
      <th><h4>Render</h4></th>
      <th><h4>Description</h4></th>
    </tr>
  </thead>
  
  <!-- Table body -->
  <tbody>
  
    <!-- Newline -->
    <tr>
      <td><kbd>&lt;br&gt;</kbd></td>
      <td></td>
      <td>New line</td>
    </tr>
    
    <!-- Separator -->
    <tr>
      <td>
        <kbd>&lt;hr&gt;</kbd>
      </td>
      <td></td>
      <td>Horizontal line separator</td>
    </tr>

    <!-- Fat arrow -->
    <tr>
      <td><kbd>=&gt;</kbd></td>
      <td><?=render("=>")?></td>
      <td>Right arrow (Used in automatic abilities, from Vingolf 2 onward)</td>
    </tr>

    <!-- List -->
    <tr>
      <td>
        <kbd>&lt;ul class="fdb-list"&gt;[...]&lt;/ul&gt;</kbd>
      </td>
      <td></td>
      <td><strong>List tags to produce list with small circles on each item (see below)</strong></td>
    </tr>

    <!-- List Item -->
    <tr>
      <td>
        <kbd>&lt;li&gt;[...]&lt;/li&gt;</kbd>
      </td>
      <td></td>
      <td><strong>List item to put into list tags (see above)</strong></td>
    </tr>
    
    <!-- Mark -->
    <tr>
      <td><kbd>&lt;mark&gt;</kbd></td>
      <td class="render"><mark>Example</mark></td>
      <td>Errata only: Text that was changed</td>
    </tr>
    
    <!-- Ins -->
    <tr>
      <td><kbd>&lt;ins&gt;</kbd></td>
      <td><ins>Example</ins></td>
      <td>Errata only: Text that was newly inserted</td>
    </tr>
    
    <!-- Del -->
    <tr>
      <td><kbd>&lt;del&gt;</kbd></td>
      <td><del>Example</del></td>
      <td>Errata only: Text that was removed</td>
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
    
    <!-- New tags -->
    <tr>
      <td><kbd>[_Precision_]</kbd></td>
      <td><?=render("[_Precision_]")?></td>
      <td>New ability tag</td>
    </tr>
    
    <!-- Old tags -->
    <tr>
      <td><kbd>[Activate]</kbd></td>
      <td><?=render("[Activate]")?></td>
      <td>Old ability tags</td>
    </tr>
    
    <!-- < and > -->
    <tr>
      <td>
        <kbd>&amp;lt;Tea-Party&amp;gt;</kbd>
      </td>
      <td><?=render("&lt;Tea-Party&gt;")?></td>
      <td>Less (&lt;) and more than (&gt;) signs must be entered like <kbd>&amp;lt;</kbd> and <kbd>&amp;gt;</kbd></td>
    </tr>
    
  </tbody><!-- /Table body -->
</table>
