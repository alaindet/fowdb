(function () {

  // CSS Selectors ------------------------------------------------------------
  var css_clusterAction = 'fd-action';
  var css_clusterId = 'fd-id';
  var css_clustersTable = '.js-clusters-table';
  var css_modal = '#js-modal';
  var css_modalClusterCode = '#fd-cluster-code';
  var css_modalClusterId = '#fd-cluster-id';
  var css_modalClusterName = '#fd-cluster-name';
  var css_modalContent = '#js-modal-content';
  var css_modalOpen = '.js-open-modal';
  var css_modalSubmit = '#js-modal-submit';
  var css_modalTitle = '#js-modal-title';


  // Application data ---------------------------------------------------------
  var data_cluster = {};
  var data_clusterAction = '';
  var data_clusterId = '';
  var data_modalContent = '';
  var data_modalSubmit = '';
  var data_modalTitle = '';
  var data_urlClusterCreate = window.BASE_URL + '/api/clusters/create';
  var data_urlClusterDelete = window.BASE_URL + '/api/clusters/delete/{id}';
  var data_urlClusterRead   = window.BASE_URL + '/api/clusters/{id}';
  var data_urlClusterUpdate = window.BASE_URL + '/api/clusters/update/{id}';
  var data_urlClustersRead  = window.BASE_URL + '/api/clusters/';

  // Bootstrap ----------------------------------------------------------------
  function bootstrap() {
    $(document)

      // Original events
      .on('click', css_modalOpen, function () {
        $(document).trigger('fd:show-modal', [$(this)]);
      })
      .on('click', css_modalSubmit, function () {
        $(document).trigger('fd:submit-modal', [$(this)]);
      })

      // Custom events
      .on('fd:show-modal', handleShowModalEvent)
      .on('fd:submit-modal', handleSubmitModalEvent)
      .on('fd:create-cluster', handleCreateClusterEvent)
      .on('fd:update-cluster', handleUpdateClusterEvent)
      .on('fd:delete-cluster', handleDeleteClusterEvent)
      .on('fd:update-list', handleUpdateListEvent)

      // Bootstrap events
      .on('show.bs.modal', handleBootstrapModalShowEvent)
      .on('shown.bs.modal', handleBootstrapModalShownEvent)
  }


  // Controller functions -----------------------------------------------------
  function handleShowModalEvent(event, clicked) {
    model_setClusterAction(view_getClusterAction(clicked));
    model_setClusterId(view_getClusterId(clicked));
    var handlers = {
      create: handleShowCreateModal,
      update: handleShowUpdateModal,
      delete: handleShowDeleteModal
    };
    var handler = handlers[data_clusterAction];
    handler();
  }

  function handleShowCreateModal() {
    model_buildCreateModal();
    view_showModal();
  }

  function handleShowUpdateModal() {
    util_ajax({
      url: data_urlClusterRead.replace('{id}', data_clusterId),
      onSuccess: function (response) {
        model_setCluster(response);
        model_buildUpdateModal();
        view_showModal();
      }
    });
  }

  function handleShowDeleteModal() {
    util_ajax({
      url: data_urlClusterRead.replace('{id}', data_clusterId),
      onSuccess: function (response) {
        model_setCluster(response);
        model_buildDeleteModal();
        view_showModal();
      }
    });
  }

  function handleBootstrapModalShowEvent(event) {
    view_setModalTitle(data_modalTitle);
    view_setModalContent(data_modalContent);
    view_setModalSubmit(data_modalSubmit);
  }

  function handleBootstrapModalShownEvent(event) {
    if (data_clusterAction === 'create') {
      view_focusModalInput(css_modalClusterId);
    }
  }

  function handleSubmitModalEvent(event) {
    var eventNames = {
      create: 'fd:create-cluster',
      update: 'fd:update-cluster',
      delete: 'fd:delete-cluster',
    };
    var eventName = eventNames[data_clusterAction];
    $(document).trigger(eventName, [view_getModalInput()]);
  }

  function handleCreateClusterEvent(event, input) {
    model_setCluster(input);
    model_setClusterId(input.id);
    handleApiAction(data_urlClusterCreate, input);
  }

  function handleUpdateClusterEvent(event, input) {
    var url = data_urlClusterUpdate.replace('{id}', data_clusterId);
    handleApiAction(url, input);
  }

  function handleDeleteClusterEvent(event, input) {
    var url = data_urlClusterDelete.replace('{id}', data_clusterId);
    handleApiAction(url, input);
  }

  function handleApiAction(url, input) {
    util_ajax({
      url: url,
      type: 'post',
      data: input,
      onSuccess: function (response) {
        view_hideModal();
        view_alertSuccess(response.message);
        $(document).trigger('fd:update-list');
      },
      onError: function (response) {
        view_hideModal();
        view_alertError(response.message);
      }
    });
  }

  function handleUpdateListEvent(event) {
    util_ajax({
      url: data_urlClustersRead,
      type: 'get',
      onSuccess: function (response) { view_updateList(response); }
    });
  }


  // Model functions ----------------------------------------------------------
  function model_setClusterAction(action) {
    if (typeof action === 'undefined') action = ''; // Reset
    data_clusterAction = action;
  }

  function model_setClusterId(id) {
    if (typeof id === 'undefined') id = ''; // Reset
    data_clusterId = id;
  }

  function model_setCluster(cluster) {

    // Reset
    if (typeof cluster === 'undefined') {
      cluster = {};
    }

    // Add the label
    else {
      cluster.label = [
        '#' + cluster.id,
        cluster.name,
        '(' + cluster.code.toUpperCase() + ')'
      ].join(' ');
    }

    data_cluster = cluster;
  }

  function model_buildCreateModal() {
    data_modalTitle = 'Create a new cluster';
    data_modalContent = view_buildModalForm();
    data_modalSubmit = '<i class="fa fa-plus"></i>&nbsp;Create';
  }

  function model_buildUpdateModal() {
    data_modalTitle = 'Update cluster <strong>' + data_cluster.label + '</strong>';
    data_modalContent = view_buildModalForm();
    data_modalSubmit = '<i class="fa fa-pencil"></i>&nbsp;Update';
  }

  function model_buildDeleteModal() {
    data_modalTitle = 'Delete cluster <strong>' + data_cluster.label + '</strong>';
    data_modalContent = view_buildModalForm();
    data_modalSubmit = '<i class="fa fa-trash"></i>&nbsp;Delete';
  }


  // View functions -----------------------------------------------------------
  function view_getClusterAction(clicked) {
    return clicked.data(css_clusterAction);
  }

  function view_getClusterId(clicked) {
    return clicked.data(css_clusterId);
  }

  function view_showModal() {
    $(css_modal).modal('show');
  }

  function view_hideModal() {
    $(css_modal).modal('hide');
  }

  function view_setModalTitle(title) {
    $(css_modalTitle).html(title);
  }

  function view_setModalContent(content) {
    $(css_modalContent).html(content);
  }

  function view_setModalSubmit(submit) {
    $(css_modalSubmit).html(submit);
  }

  function view_focusModalInput(selector) {
    $(selector).focus();
  }

  function view_getModalInput() {

    var id = $(css_modalClusterId);
    var name = $(css_modalClusterName);
    var code = $(css_modalClusterCode);

    return {
      id: id.prop('nodeName') === 'P' ? id.text() : id.val(),
      name: name.prop('nodeName') === 'P' ? name.text() : name.val(),
      code: code.prop('nodeName') === 'P' ? code.text() : code.val(),
    };
  }

  function view_alertSuccess(message) {
    FoWDB.clearNotifications().notify(message, 'info');
  }

  function view_alertError(message) {
    FoWDB.clearNotifications().notify(message, 'danger');
  }

  function view_buildModalForm() {

    var onCreate = data_clusterAction === 'create';
    var onDelete = data_clusterAction === 'delete';

    var idFormSection = view_buildFormSection({
      label: 'ID',
      type: 'number',
      id: css_modalClusterId.substr(1),
      placeholder: onCreate ? 'Cluster ID...' : false,
      value: !onCreate ? data_cluster.id : false,
      readonly: !onCreate
    });

    var nameFormSection = view_buildFormSection({
      label: 'Name',
      type: 'text',
      id: css_modalClusterName.substr(1),
      placeholder: onCreate ? 'Cluster Name Ex.: "Dope Cluster"...' : false,
      value: !onCreate ? data_cluster.name : false,
      readonly: onDelete
    });

    var codeFormSection = view_buildFormSection({
      label: 'Code',
      type: 'text',
      id: css_modalClusterCode.substr(1),
      placeholder: onCreate ? 'Cluster Code...' : false,
      value: !onCreate ? data_cluster.code : false,
      readonly: onDelete
    });

    return [
      '<div class="row">',
      '<div class="col-xs-12">',
      '<form class="form-horizontal">',
      idFormSection,
      nameFormSection,
      codeFormSection,
      '</form>',
      '</div>',
      '</div>'
    ].join('');
  }

  function view_buildFormSection(input) {

    input.value = input.value || '';
    input.readonly = input.readonly || false;

    var inputElement = function (input) {
      return [
        '<input ',
        'type="', input.type, '" ',
        'id="', input.id, '" ',
        'class="form-control font-110"',
        input.placeholder ? 'placeholder="' + input.placeholder + '" ' : '',
        input.value ? 'value="' + input.value + '"' : '',
        '>'

      ].join('');
    };

    var paragraphElement = function (input) {
      return [
        '<p ',
        'class="fd-text-form-horizontal font-110" ',
        'id="', input.id, '"',
        '>',
        input.value,
        '</p>'
      ].join('');
    }

    return [
      '<div class="form-group">',
      '<div class="col-sm-2 control-label">', input.label, '</div>',
      '<div class="col-sm-10">',
      input.readonly ? paragraphElement(input) : inputElement(input),
      '</div>',
      '</div>'
    ].join('');
  }

  function view_updateList(clusters) {

    html = clusters.reduce(function (html, cluster) {
      return html += view_buildClusterTableRow(cluster);
    }, '');

    $(css_clustersTable).empty().html(html);
  }

  function view_buildClusterTableRow(cluster) {

    var updateClasses = [
      'btn',
      'btn-xs',
      'fd-btn-default',
      'fd-btn-warning-hover',
      'js-open-modal'
    ].join(' ');

    var deleteClasses = [
      'btn',
      'btn-xs',
      'fd-btn-default',
      'fd-btn-danger-hover',
      'js-open-modal'
    ].join(' ');

    var label = '';
    if (cluster.id == data_clusterId) {
      switch (data_clusterAction) {
        case 'create':
          label = '<span class="label label-success">NEW</span>';
          break;
        case 'update':
          label = '<span class="label label-warning">UPD</span>';
          break;
      }
    }

    return [
      '<tr>',
      '<td>', cluster.id, '</td>',
      '<td>',

      // Update button
      '<button ',
      'type="button" ',
      'class="', updateClasses, '" ',
      'data-fd-action="update"',
      'data-fd-id="', cluster.id, '"',
      '>',
      '<i class="fa fa-pencil"></i>&nbsp;',
      'Update',
      '</button>&nbsp;',

      // Delete button
      '<button ',
      'type="button" ',
      'class="', deleteClasses, '" ',
      'data-fd-action="delete"',
      'data-fd-id="', cluster.id, '"',
      '>',
      '<i class="fa fa-trash"></i>&nbsp;',
      'Delete',
      '</button>',

      '</td>',
      '<td>', label, '&nbsp;', cluster.name, '</td>',
      '<td>', cluster.code, '</td>',
      '</tr>'
    ].join('');
  }


  // Utility functions --------------------------------------------------------
  /**
   * Performs an AJAX request to an endpoint.
   * Integrates the anti-CSRF token on POST requests, read from its <meta> tag
   * 
   * Please note the onSuccess, onError and onComplete callbacks are called
   * inside the 'success' jQuery option anyway and thus imply the server
   * responded with a proper JSON anyway. This allows the UI to show soft errors
   * like validation errors, existing resources etc.
   * 
   * "Real" AJAX errors like invalid JSON or server not responding are simply
   * output into the console via the 'error' jQuery option
   * 
   * @param object args Props: url, data, type, onSuccess, onError, onComplete
   */
  function util_ajax(args) {

    // Initialize ajax object
    var ajax = {};

    // Default data
    if (typeof args.data === 'undefined') args.data = {};

    if (typeof args.type === 'undefined') args.type = 'get';

    // Set Anti-CSRF token for any non-GET request
    if (args.type !== 'get') {
      var token = util_readAntiCsrfToken();
      args.data._token = token;
      ajax.headers = { 'X-CSRF-TOKEN': token };
    }

    // Default error handler
    if (typeof args.onError === 'undefined') {
      args.onError = function (response) {
        console.log(response.message);
      };
    }

    // Default complete handler (executes anyway, after onSuccess/onError)
    if (typeof args.onComplete === 'undefined') {
      args.onComplete = function () { };
    }

    // Define the props of the ajax object
    ajax.url = args.url;
    ajax.type = args.type.toUpperCase();
    ajax.dataType = 'json';
    ajax.data = args.data;
    ajax.success = function (response) {
      if (!response.error) args.onSuccess(response);
      else args.onError(response);
      args.onComplete();
    };
    ajax.error = function (xhr, message, error) {
      console.log(xhr.responseText, message, error)
    };

    // Call the API
    jQuery.ajax(ajax);
  }

  /**
   * Reads the anti-CSRF token from a <meta> tag, if set
   */
  function util_readAntiCsrfToken() {
    return $('meta[name="_token"]').attr('content');
  }


  // Go! ----------------------------------------------------------------------
  $(document).ready(bootstrap);

})();
