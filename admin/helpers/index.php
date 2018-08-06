<?php

if (admin_level() == 1):

    // Dynamic features
    $dynamics = ["all", "clusters", "formats", "spoiler"];

    // Print all if requested
    if (isset($_POST['review'])) {

        // Log current helpers.json
        echo \App\Debugger::log(\App\Helpers::getAll(), "/app/helpers/helpers.json");
    }

    // Check if feat is passed
    if (isset($_POST['feat']) AND in_array($_POST['feat'], $dynamics)) {

        // Alias feature
        $feat = $_POST['feat'];

        // Re-generate all
        if ($feat == 'all') {

            // Re-generate all dynamic helpers and assemble all helpers
            $saved = \App\Helpers::generateAll() & \App\Helpers::assembleAll();
            // Notify
            if ($saved) { \App\FoWDB::notify("All helpers JSON data regenerated."); }
            else { \App\FoWDB::notify("Could not generate and assemble all helpers."); }
            
            // Log generated file
            echo \App\Debugger::log(\App\Helpers::getAll(), $feat);

        // Re-generate specific
        } else if (in_array($feat, $dynamics)) {

            // Re-generate current and assemble all
            $saved = \App\Helpers::generate($feat) & \App\Helpers::assembleAll();

            // Notify
            if ($saved) { \App\FoWDB::notify($feat.".json regenerated."); }
            else { \App\FoWDB::notify("Could not generate and assemble helper for {$feat}."); }

            // Log generated file
            echo \App\Debugger::log(\App\Helpers::get($feat), $feat);
        }
    }
?>
    <!-- Title -->
    <div class="page-header"><h1>Helpers</h1></div>

    <!-- Description -->
    <div class="well">
        <p>
            Helpers are used throughout FoWDB for presentation of game-specific data. They include data about what features cards have, then attributes, clusters, costs, formats, keywords, rarities, sorting fields and types! This page lets you re-generate individual JSON files for semi-static data that rarely changes (Clusters, Formats, Keywords, Types) and saves them into <code>/app/helpers/data/</code> directory, then rebuilds final <code>/app/helpers/helpers.json</code>. You can also re-genearate all at once.
        </p>
    </div>

    <form class="form form-inline" action="" method="post">
        <!-- Review All -->
        <p>
            <button name="review" value="all" type="submit" class="btn btn-info btn-lg">
                Review <code>helpers.json</code>
            </button>
        </p>

        <?php foreach ($dynamics as $feat): ?>
            <!-- Regenerate <?=$feat?> -->
            <p>
                <button name="feat" value="<?=$feat?>" type="submit" class="btn btn-default btn-lg">
                    Regenerate <?=ucfirst($feat)?>
                </button>
            </p>
        <?php endforeach; ?>
    </form>
<?php else: ?>
    <div class="well">Please <a href="index.php?p=admin"><strong>login</strong></a></div>
<?php endif; ?>
