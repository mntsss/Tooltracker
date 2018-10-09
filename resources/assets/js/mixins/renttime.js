export default{
  methods: {
    calcDaysWithWeekends: function(date){
        var dateRented = new Date(date)
        var currentDate = new Date();
        if(dateRented > currentDate)
            return 0
        var timeDiff = Math.abs(currentDate.getTime() - dateRented.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        return diffDays
    },
    calcBusinessDays: function(date) {
        var iWeeks, iDateDiff, iAdjust = 0;
        var dDate1 = new Date(date)
        var dDate2 = new Date()
        if (dDate2 < dDate1) return 0; // error code if dates transposed
        var iWeekday1 = dDate1.getDay(); // day of week
        var iWeekday2 = dDate2.getDay();
        iWeekday1 = (iWeekday1 == 0) ? 7 : iWeekday1; // change Sunday from 0 to 7
        iWeekday2 = (iWeekday2 == 0) ? 7 : iWeekday2;
        if ((iWeekday1 > 5) && (iWeekday2 > 5)) iAdjust = 1; // adjustment if both days on weekend
        iWeekday1 = (iWeekday1 > 5) ? 5 : iWeekday1; // only count weekdays
        iWeekday2 = (iWeekday2 > 5) ? 5 : iWeekday2;

        // calculate differnece in weeks (1000mS * 60sec * 60min * 24hrs * 7 days = 604800000)
        iWeeks = Math.floor((dDate2.getTime() - dDate1.getTime()) / 604800000)

        if (iWeekday1 <= iWeekday2) {
          iDateDiff = (iWeeks * 5) + (iWeekday2 - iWeekday1)
        } else {
          iDateDiff = ((iWeeks + 1) * 5) - (iWeekday1 - iWeekday2)
        }

        iDateDiff -= iAdjust // take into account both days on weekend
        return (iDateDiff + 1); // add 1 because dates are inclusive
      },
      calculatePrice: function(days, dailyPrice){
        return Math.ceil(days*dailyPrice)
      }
  }
}
