<?php

namespace App\Views\Entities\Card;

class CardText
{
	/**
	 * Straight 1:1 replacements
	 *
	 * @var array
	 */
	private static $replace = [
		'{rest}' => '<img src="images/icons/blank.gif" class="fd-icon-rest">',
		'=>' => '&rArr;',
		'Errata:' => '<span class="ruling-errata">Errata:</span>',
		'<hr>' => '<span class="fd-spacer-vertical-050"></span>',
		'[(' => '【',
		')]' => '】',
		'<<' => '&#12296;',
		'>>' => '&#12297;'
	];

	/**
	 * Regex replacements
	 *
	 * @var array
	 */
	private static $regexReplace = [
		// Will symbols
		"/{([wrugbmvt])}/" => "<img src='images/icons/blank.gif' class='fd-icon-$1' alt='$1'/>",
		// Free will
		"/{([0-9x]+)}/" => "<div class='fd-icon-free'>&nbsp;$1&nbsp;</div>",
		// Symbol skills
		"/\[_(.+?)_\]/" => "<span class='fd-mark-ability'>$1</span>",
		// Break
		"/^\[Break\](.+?)(<br>|<hr>|$)/" => "<span class='fd-mark-break'>Break</span> <span class='fd-mark-break-text'>$1</span><br>",
		// Abilities (white text on black, legacy)
		"/\[(J-Activate|Continuous|Activate|Target Attack|Enter|Flying|Explode|Pierce|Trigger|First Strike|Imperishable|Swiftness|Awakening|Incarnation|Quickcast|Remnant|Stealth|Judgment|Evolution|Shift)\]/" => "<span class='fd-mark-old-ability'>$1</span>",
		// -ERRATA- on card text
		"~-errata-(.+?)-/errata-~" => "<span class='fd-mark-errata'>$1</span>"
	];

    /**
	 * Renders the HTML presentation of a card's text
	 *
	 * The rendered string has HTML <img> for will and rest symbols,
	 * CSS classes for abilities, skills, breaks and horizontal rules.
	 *
	 * @param string $text The string to be rendered
	 * @return string $text The rendered string
	 */
	public static function render(string $text): string
	{
		$text = preg_replace(
			array_keys(self::$regexReplace),
			self::$regexReplace,
			$text
		);

		$text = str_replace(
			array_keys(self::$replace),
			self::$replace,
			$text
		);

		$text = str_replace(
			'images/icons/blank.gif',
			fd_asset('images/icons/blank.gif'),
			$text
		);

		return $text;
	}
}
