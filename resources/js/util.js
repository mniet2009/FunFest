export function getCompletions(vue, activity_id) {
  return vue.$page.props.auth.user.completions.filter(
    (completion) => completion.activity_id === activity_id
  );
}
