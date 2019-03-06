// CSS Selectors ---------------------------------------------------------
const css_cardsContainer = ".js-fd-card-items";
const css_cardItem = ".fd-grid";
const css_optionsPanelToggle= ".js-options-toggle";
const css_optionsPanel = "#the-options";
const css_resultsPanel = "#the-results";
const css_searchForm = "#the-form";
const css_searchReset = ".js-search-reset";
const css_itemsPerLineInput = "#js-items-per-line-input";
const css_itemsPerLineLess = "#js-items-per-line-less";
const css_itemsPerLineMore = "#js-items-per-line-more";

const css_optionsHider = '.js-hider[data-target="#hide-options"]';
const css_showMissing = '#opt_i_missing';
const css_spoiler = '.spoiler';
const css_spoilerBody = '.spoiler-body';
const css_card = '.fdb-card';
const css_missingCard = '.fdb-card-missing';

// Application data ------------------------------------------------------
const data_breakpoint = 768; // Pixels
const data_enterKey = 13;
let data_itemsPerLine = 0;
const data_defaultFilters = [
  {
    name: "format[]",
    value: ["wandr"],
    type: "checkbox"
  }
];
