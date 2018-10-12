<?php

namespace App\Views\Card;

class CardText
{
	/**
	 * Straight 1:1 replacements
	 *
	 * @var array
	 */
	private static $replace = [
		'{rest}' => '<img src="_images/icons/1x1.gif" class="fdb-icon-rest" />',
		'=>' => '&rArr;',
		'Errata:' => '<span class="ruling-errata">Errata:</span>',
		'<hr>' => "<div class='fdb-separator-v-05'></div>",
		'[(' => '【',
		')]' => '】'
	];

	/**
	 * Regex replacements
	 *
	 * @var array
	 */
	private static $regexReplace = [
		// Will symbols
		"/{([wrugbmvt])}/" => "<img src='_images/icons/1x1.gif' class='fdb-icon-$1' alt='$1'/>",
		// Free will
		"/{([0-9x]+)}/" => "<div class='fdb-icon-free'>&nbsp;$1&nbsp;</div>",
		// Symbol skills
		"/\[_(.+?)_\]/" => "<span class='mark_skills'>$1</span>",
		// Break
		"/^\[Break\](.+?)(<br>|$)/" => "<span class='mark_break'>Break</span> <span class='mark_breaktext'>$1</span><br>",
		// Abilities (white text on black, legacy)
		"/\[(J-Activate|Continuous|Activate|Target Attack|Enter|Flying|Explode|Pierce|Trigger|First Strike|Imperishable|Swiftness|Awakening|Incarnation|Quickcast|Remnant|Stealth|Judgment|Evolution|Shift)\]/" => "<span class='mark_abilities'>$1</span>",
		// -ERRATA- on card text
		"~-errata-(.+?)-/errata-~" => "<span class='mark_errata'>$1</span>"
	];

    /**
	 * Renders the HTML presentation of a card's text
	 *
	 * The rendered string has HTML <img> for will and rest symbols,
	 * CSS classes for abilities, skills, breaks and horizontal rules.
	 *
	 * @param string $txt The string to be rendered
	 * @return string $txt The rendered string
	 */
	public static function render(string $txt = ''): string
	{
		$replace =& self::$replace;
		$regexReplace =& self::$regexReplace;

		$txt = preg_replace(array_keys($regexReplace), $regexReplace, $txt);
		$txt = str_replace(array_keys($replace), $replace, $txt);

		return $txt;
	}
}
