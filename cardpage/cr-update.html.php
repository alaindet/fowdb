<?php

// Spells
$spellTypes = [
    "Spell:Chant",
    "Spell:Chant-Instant",
    "Spell:Chant-Standby"
];

// Additions
$additionTypes = [
    "Addition:Field",
    "Addition:Resonator",
    "Addition:J/Resonator",
    "Addition:Ruler/J-Ruler"
];

// Define assembled pattern
$oldTypesPattern = implode("|", $spellTypes)."|".implode("|", $additionTypes);

// Will hold matches
$match_type = [];

// Check if card is old
if (preg_match_all("~({$oldTypesPattern})~", $card['cardtype'].$card['cardtext'], $match_type)) {
    
    // Alias match
    $m = $match_type[0][0];

    // Spells
    if (in_array($m, $spellTypes)) {
        $oldRulings = [
            "1405.1" => "Treat the type \"spell\" on old cards as \"chant\". It doesn't have any subtypes.",
            "1405.2" => "Ignore the \"chant\" subtypes on all cards.",
            "1405.2a" => "If an old card refers to \"spell: chant\", it refers to \"chant\"",
            "1405.3" => "Treat old cards with the \"chant-instant\" subtype as cards with [Quickcast].",
            "1405.3a" => "If an old card refers to \"spell: chant-instant\", it refers to \"chant with [Quickcast]\"",
            "1405.4" => "Treat old cards with the \"chant-standby\" subtype as cards with [Trigger].",
            "1405.4a" => "If an old card refers to \"spell: chant-standby\", it refers to \"chant with [Trigger]\".",
            "1407.1" => "If an old card refers to a \"summon spell\", it refers to a \"resonator spell\".",
            "1407.2" => "If an old card refers to a \"normal spell\", it refers to a \"non-resonator spell\""
        ];
    }

    // Additions
    if (in_array($m, $additionTypes)) {
        $oldRulings = [
            "1406.1" => "All of the old addition cards have subtypes.",
            "1406.1a" => "Old additions have the subtypes \"field\", \"resonator\", \"ruler\", \"J-ruler\", \"J/resonator\" or \"J/ruler\". [...]"

        ];
    }

    echo "<div class=\"col-xs-12 alert alert-info\"><div class=\"col-xs-4 col-sm-2\"><strong>Reference</strong></div><div class=\"col-xs-8 col-sm-10\"><strong>Text</strong></div>";

    foreach($oldRulings as $ref => $text) {
        echo "<div class=\"col-xs-4 col-sm-2\">{$ref}</div><div class=\"col-xs-8 col-sm-10\">{$text}</div>";
    }

    echo "</div>";
}