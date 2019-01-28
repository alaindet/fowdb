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

// Application data ------------------------------------------------------
const data_breakpoint = 768; // Pixels
let data_itemsPerLine = 0;
const data_defaultFilters = [
  {
    name: "format[]",
    value: ["wandr"],
    type: "checkbox"
  }
];
