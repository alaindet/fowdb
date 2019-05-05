// CSS Selectors --------------------------------------------------------------
const css_clusterAction = 'fd-action';
const css_clusterId = 'fd-id';
const css_clustersTable = '.js-clusters-table';
const css_modal = '#js-modal';
const css_modalClusterCode = '#fd-cluster-code';
const css_modalClusterId = '#fd-cluster-id';
const css_modalClusterName = '#fd-cluster-name';
const css_modalContent = '#js-modal-content';
const css_modalOpen = '.js-open-modal';
const css_modalSubmit = '#js-modal-submit';
const css_modalTitle = '#js-modal-title';

// Application data -----------------------------------------------------------
let data_cluster = {};
let data_clusterAction = '';
let data_clusterId = '';
let data_modalContent = '';
let data_modalSubmit = '';
let data_modalTitle = '';
const data_urlClusterCreate = window.BASE_URL + '/api/clusters/create';
const data_urlClusterDelete = window.BASE_URL + '/api/clusters/delete/{id}';
const data_urlClusterRead   = window.BASE_URL + '/api/clusters/{id}';
const data_urlClusterUpdate = window.BASE_URL + '/api/clusters/update/{id}';
const data_urlClustersRead  = window.BASE_URL + '/api/clusters/';
