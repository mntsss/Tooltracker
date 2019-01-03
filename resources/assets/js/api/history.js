const historyApi = {
  filterHistory: (from = '', til = '') => {
    if(til && !from){ from = '/1990-01-01'}
    if(from){ from = '/' + from;}
    if(til){ til = '/' + til;}
    return `history/item/all${from}${til}`},

  filterUserHistory: (userID = '', from = '', til = '') => {
    if(userID) userID = '/'+userID;
    if(!userID && (from || til)){ userID = "all";}
    if(til && !from){ from = '/1990-01-01'}
    if(from){ from = '/' + from;}
    if(til){ til = '/' + til;}
    return `history/user${userID}${from}${til}`;
  }
};

export default historyApi;
