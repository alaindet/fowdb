const view_getClusterAction = (clicked) => {
  return clicked.data(css_clusterAction);
};

const view_getClusterId = (clicked) => {
  return clicked.data(css_clusterId);
};

const view_showModal = () => {
  $(css_modal).modal('show');
};

const view_hideModal = () => {
  $(css_modal).modal('hide');
};

const view_setModalTitle = (title) => {
  $(css_modalTitle).html(title);
};

const view_setModalContent = (content) => {
  $(css_modalContent).html(content);
};

const view_setModalSubmit = (submit) => {
  $(css_modalSubmit).html(submit);
};

const view_focusModalInput = (selector) => {
  $(selector).focus();
};

const view_getModalInput = () => {
  const id = $(css_modalClusterId);
  const name = $(css_modalClusterName);
  const code = $(css_modalClusterCode);
  return {
    id: id.prop('nodeName') === 'P' ? id.text() : id.val(),
    name: name.prop('nodeName') === 'P' ? name.text() : name.val(),
    code: code.prop('nodeName') === 'P' ? code.text() : code.val(),
  };
};

const view_alertSuccess = (message) => {
  window.APP.clearAlerts().alert(message, 'info');
};

const view_alertError = (message) => {
  window.APP.clearAlerts().alert(message, 'danger');
};

const view_buildModalForm = () => {

  const onCreate = (data_clusterAction === 'create');
  const onDelete = (data_clusterAction === 'delete');

  const idFormSection = view_buildFormSection({
    label: 'ID',
    type: 'number',
    id: css_modalClusterId.substr(1),
    placeholder: onCreate ? 'Cluster ID...' : false,
    value: !onCreate ? data_cluster.id : false,
    readonly: !onCreate
  });

  const nameFormSection = view_buildFormSection({
    label: 'Name',
    type: 'text',
    id: css_modalClusterName.substr(1),
    placeholder: onCreate ? 'Cluster Name Ex.: "Dope Cluster"...' : false,
    value: !onCreate ? data_cluster.name : false,
    readonly: onDelete
  });

  const codeFormSection = view_buildFormSection({
    label: 'Code',
    type: 'text',
    id: css_modalClusterCode.substr(1),
    placeholder: onCreate ? 'Cluster Code...' : false,
    value: !onCreate ? data_cluster.code : false,
    readonly: onDelete
  });

  return (
    '<div class="row">'+
      '<div class="col-xs-12">'+
        '<form class="form-horizontal">'+
          idFormSection+
          nameFormSection+
          codeFormSection+
        '</form>'+
      '</div>'+
    '</div>'
  );

};

const view_buildFormSection = (input) => {

  input.value = input.value || '';
  input.readonly = input.readonly || false;

  const inputElement = (input) => {
    return (
      '<input '+
        `type="${input.type}" `+
        `id="${input.id}" `+
        'class="form-control font-110"'+
        (input.placeholder ? ` placeholder="${input.placeholder}" ` : '')+
        (input.value ? `value="${input.value}"` : '')+
      '>'
    );
  };

  const paragraphElement = (input) => {
    const className = "fd-text-form-horizontal font-110";
    return `<p class="${className}" id="${input.id}">${input.value}</p>`;
  }

  return (
    '<div class="form-group">'+
      '<div class="col-sm-2 control-label">'+input.label+'</div>'+
        '<div class="col-sm-10">'+
          (input.readonly ? paragraphElement(input) : inputElement(input))+
        '</div>'+
    '</div>'
  );

}

const view_updateList = (clusters) => {
  $(css_clustersTable).empty().html(
    clusters.reduce(
      (html, cluster) => html += view_buildClusterTableRow(cluster), ''
    )
  );
}

const view_buildClusterTableRow = (cluster) => {

  let label = '';

  const updateClasses = [
    'btn',
    'btn-xs',
    'fd-btn-default',
    'fd-btn-warning-hover',
    'js-open-modal'
  ].join(' ');

  const deleteClasses = [
    'btn',
    'btn-xs',
    'fd-btn-default',
    'fd-btn-danger-hover',
    'js-open-modal'
  ].join(' ');

  if (cluster.id === data_clusterId) {
    switch (data_clusterAction) {
      case 'create':
        label = '<span class="label label-success">NEW</span>';
        break;
      case 'update':
        label = '<span class="label label-warning">UPD</span>';
        break;
    }
  }

  return (
    '<tr>'+
      `<td>${cluster.id}</td>`+
      '<td>' +// Action buttons

        '<button ' +// Update button
          'type="button" '+
          `class="${updateClasses}" `+
          'data-fd-action="update"'+
          `data-fd-id="${cluster.id}"`+
        '>'+
          '<i class="fa fa-pencil"></i>&nbsp;Update'+
        '</button>&nbsp;'+

        '<button ' +// Delete button
          'type="button" '+
          `class="${deleteClasses}" `+
          'data-fd-action="delete"'+
          `data-fd-id="${cluster.id}"`+
        '>'+
          '<i class="fa fa-trash"></i>&nbsp;Delete'+
        '</button>'+

      '</td>'+
      `<td>${label}&nbsp;${cluster.name}</td>`+
      `<td>${cluster.code}</td>`+
    '</tr>'
  );
  
}
