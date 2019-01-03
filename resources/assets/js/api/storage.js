const storageApi = {
  storageList: () => "/storage/list",
  storageCreate: () => "/storage/create",
  storageEdit: () => "storage/edit",
  storageGet: (id) => `storage/get/${id}`,
  storageDelete: (id) => `storage/delete/${id}`
};

export default storageApi;
