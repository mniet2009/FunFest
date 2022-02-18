export function getActivityState(activity) {
  let ret = {
    availableTickets: 0,
    tickets: 0,
    state: "incomplete",
    states: [],
  };

  let activities = [activity];

  if (activity.children.length > 0) {
    activities = activity.children;
  }

  for (activity of activities) {
    let state;
    let tickets = activity.completions[0]
      ? parseInt(activity.completions[0].tickets)
      : 0;
    ret.availableTickets += activity.tickets * activity.limit;
    ret.tickets += tickets;

    if (activity.activity_type_id == 1) {
      // For score/time attacks, any points is green, any other time is yellow, no time is red
      if (tickets > 0) {
        state = "complete";
      } else if (activity.completions[0]) {
        state = "partial";
      } else {
        state = "incomplete";
      }
    } else if ([5, 6, 7].includes(activity.activity_type_id)) {
      // For races/competitions/contests, any participation is green, anything else is red
      if (activity.completions[0]) {
        state = "complete";
      } else {
        state = "incomplete";
      }
    } else {
      if (tickets == 0) {
        state = "incomplete";
      } else if (tickets < activity.tickets * activity.limit) {
        state = "partial";
      } else {
        state = "complete";
      }
    }

    ret.states.push(state);
  }

  if (!ret.states.includes("incomplete") && !ret.states.includes("partial")) {
    ret.state = "complete";
  } else if (
    !ret.states.includes("partial") &&
    !ret.states.includes("complete")
  ) {
    ret.state = "incomplete";
  } else {
    ret.state = "partial";
  }

  return ret;
}

export function ordinal_number(i) {
  var j = i % 10,
    k = i % 100;
  if (j == 1 && k != 11) {
    return i + "st";
  }
  if (j == 2 && k != 12) {
    return i + "nd";
  }
  if (j == 3 && k != 13) {
    return i + "rd";
  }
  return i + "th";
}

export function formatNumber(number) {
  return new Intl.NumberFormat("en-US", {}).format(number);
}

export function formatTime(ms) {
  let milliseconds = ms % 1000;
  let seconds = Math.floor(ms / 1000) % 60;
  let minutes = Math.floor(ms / 60000) % 60;
  let hours = Math.floor(ms / 3600000);

  let ret = [];

  if (hours > 0) {
    ret.push({
      symbol: "h",
      number: hours,
    });
  }

  if (hours > 0 || minutes > 0) {
    let m = minutes;

    if (hours > 0) {
      m = m.toString().padStart(2, 0);
    }

    ret.push({
      symbol: "m",
      number: m,
    });
  }

  let s = seconds;

  if (hours > 0 || minutes > 0) {
    s = s.toString().padStart(2, 0);
  }

  ret.push({
    symbol: "s",
    number: s,
  });

  if (milliseconds > 0) {
    ret.push({
      symbol: "ms",
      number: milliseconds.toString().padStart(3, 0),
    });
  }

  return ret;
}
