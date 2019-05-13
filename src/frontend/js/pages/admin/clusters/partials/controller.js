const handleShowModalEvent = (event, clicked) => {

  model_setClusterAction(view_getClusterAction(clicked));
  model_setClusterId(view_getClusterId(clicked));

  const handler = {
    create: handleShowCreateModal,
    update: handleShowUpdateModal,
    delete: handleShowDeleteModal
  }[data_clusterAction];

  handler();
};

const handleShowCreateModal = () => {
  model_buildCreateModal();
  view_showModal();
};

const handleShowUpdateModal = () => {
  window.APP.ajax({
    url: data_urlClusterRead.replace('{id}', data_clusterId),
    onSuccess: response => {
      model_setCluster(response);
      model_buildUpdateModal();
      view_showModal();
    }
  });
};

const handleShowDeleteModal = () => {
  window.APP.ajax({
    url: data_urlClusterRead.replace('{id}', data_clusterId),
    onSuccess: response => {
      model_setCluster(response);
      model_buildDeleteModal();
      view_showModal();
    }
  });
}

const handleBootstrapModalShowEvent = (event) => {
  view_setModalTitle(data_modalTitle);
  view_setModalContent(data_modalContent);
  view_setModalSubmit(data_modalSubmit);
};

const handleBootstrapModalShownEvent = (event) => {
  if (data_clusterAction === 'create') {
    view_focusModalInput(css_modalClusterId);
  }
};

const handleSubmitModalEvent = (event) => {
  const eventName = {
    create: 'fd:create-cluster',
    update: 'fd:update-cluster',
    delete: 'fd:delete-cluster',
  }[data_clusterAction];
  $(document).trigger(eventName, [view_getModalInput()]);
};

const handleCreateClusterEvent = (event, input) => {
  model_setCluster(input);
  model_setClusterId(input.id);
  handleApiAction(data_urlClusterCreate, input);
};

const handleUpdateClusterEvent = (event, input) => {
  const url = data_urlClusterUpdate.replace('{id}', data_clusterId);
  handleApiAction(url, input);
};

const handleDeleteClusterEvent = (event, input) => {
  const url = data_urlClusterDelete.replace('{id}', data_clusterId);
  handleApiAction(url, input);
};

const handleApiAction = (url, input) => {
  window.APP.ajax({
    url: url,
    type: 'post',
    data: input,
    onSuccess: response => {
      view_hideModal();
      view_alertSuccess(response.message);
      $(document).trigger('fd:update-list');
    },
    onError: response => {
      view_hideModal();
      view_alertError(response.message);
    }
  });
};

const handleUpdateListEvent = (event) => {
  window.APP.ajax({
    url: data_urlClustersRead,
    type: 'get',
    onSuccess: response => {
      view_updateList(response);
    }
  });
};
