const itemApi = {
  itemList: (id) => {
    return `item/list/${id}`;
  },
  itemCreate: () => { return `item/create`},
  itemEdit: () => { return `item/edit`},
  itemDelete: (id) => { return `item/delete/${id}`},
  itemGet: (id) => { return `item/get/${id}`},
  itemRestore: () => { return `item/restore`}
};

export default itemApi;
