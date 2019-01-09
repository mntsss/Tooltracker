const groupApi = {
  groupList: () => "group/list",
  groupCreate: () => "group/create",
  groupEdit: () => "group/edit",
  groupGet: (id) => `group/get/${id}`,
  groupDelete: (id) => `group/delete/${id}`
};

export default groupApi;
