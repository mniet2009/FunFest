export function getActivityState(activity) {
  let ret = {};

  if (activity.limit == 1) {
    ret.type = "single";
    ret.tickets = activity.tickets;

    if (activity.completions.length == 0) {
      ret.state = "incomplete";
    } else {
      ret.state = "complete";
    }
  } else {
    ret.type = "multiple";
    ret.tickets = activity.tickets * activity.limit;

    if (activity.completions.length == 0) {
      ret.state = "incomplete";
    } else if (activity.completions.length < activity.limit) {
      ret.state = "partial";
    } else {
      ret.state = "complete";
    }
  }

  return ret;
}
