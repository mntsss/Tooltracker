const statisticsApi = {
  statisticsTiles: () => "statistics/get",
  statisticsUnconfirmedItemReturns: () => 'suspention/get/unconfirmedreturn',
  statisticsFixItems: () => "suspention/get/fixing",
  statisticsLongestRentedItems: () => "rented/get/longest"
};

export default statisticsApi;
