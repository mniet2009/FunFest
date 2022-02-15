export function getActivityState(activity) {
  let ret = {
    availableTickets: 0,
    tickets: 0,
    limit: 0,
    completions: 0,
    state: "incomplete",
    states: [],
  };

  let combinedActivities = [activity].concat(activity.children);

  for (activity of combinedActivities) {
    if (activity.limit == 1) {
      ret.availableTickets += activity.tickets;
      ret.tickets += activity.tickets * activity.completions.length;
      ret.limit += 1;
      ret.completions += activity.completions.length;
    } else {
      ret.availableTickets += activity.tickets * activity.limit;
      ret.tickets += activity.tickets * activity.completions.length;
      ret.limit += activity.limit;
      ret.completions += activity.completions.length;
    }

    if (activity.completions.length == 0) {
      ret.states.push("incomplete");
    } else if (activity.completions.length < activity.limit) {
      ret.states.push("partial");
    } else {
      ret.states.push("complete");
    }
  }

  if (ret.completions == 0) {
    ret.state = "incomplete";
  } else if (ret.completions < ret.limit) {
    ret.state = "partial";
  } else {
    ret.state = "complete";
  }

  return ret;
}
