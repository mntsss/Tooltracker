const reservationsApi = {
  filterClosedReservations: (userID = '', from = '', til = '') => {
    if(userID) userID = '/'+userID;
    if(!userID && (from || til)){ userID = "all";}
    if(til && !from){ from = '/1990-01-01'}
    if(from){ from = '/' + from;}
    if(til){ til = '/' + til;}
    return `reservation/closed${userID}${from}${til}`}
};

export default reservationsApi;
