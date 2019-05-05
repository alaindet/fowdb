const model_setClusterAction = (action) => {
  data_clusterAction = window.APP.arg(action, '');
};

const model_setClusterId = (id) => {
  data_clusterId = window.APP.arg(id, '');
};

const model_setCluster = (cluster) => {

  // Reset
  if (typeof cluster === 'undefined') {
    data_cluster = {};
    return;
  }

  const id = cluster.id;
  const name = cluster.name;
  const code = cluster.code.toUpperCase();
  cluster.label = `#${id} ${name} (${code})`;
  data_cluster = cluster;
};

const model_buildCreateModal = () => {
  data_modalTitle = 'Create a new cluster';
  data_modalContent = view_buildModalForm();
  data_modalSubmit = '<i class="fa fa-plus"></i>&nbsp;Create';
};

const model_buildUpdateModal = () => {
  data_modalTitle = `Update cluster <strong>${data_cluster.label}</strong>`;
  data_modalContent = view_buildModalForm();
  data_modalSubmit = '<i class="fa fa-pencil"></i>&nbsp;Update';
};

const model_buildDeleteModal = () => {
  data_modalTitle = `Delete cluster <strong>${data_cluster.label}</strong>`;
  data_modalContent = view_buildModalForm();
  data_modalSubmit = '<i class="fa fa-trash"></i>&nbsp;Delete';
};
