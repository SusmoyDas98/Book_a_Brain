function toggleApplyForm(postId) {
  const formSection = document.getElementById('form-' + postId);
  const btn = document.getElementById('btn-' + postId);
  if (!formSection) return;
  const isOpen = formSection.classList.contains('open');
  if (isOpen) {
    formSection.classList.remove('open');
    if (btn) btn.style.display = 'inline-block';
  } else {
    formSection.classList.add('open');
    if (btn) btn.style.display = 'none';
  }
}
