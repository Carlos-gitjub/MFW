document.addEventListener('DOMContentLoaded', () => {
  const dropdownItems = document.querySelectorAll('.dropdown-item[data-region]');
  const selectedFlag = document.getElementById('selected-flag');
  const selectedEmoji = document.getElementById('selected-emoji');
  const hiddenInput = document.getElementById('regionInput');

  function updateRegion(region, emoji) {
    selectedFlag.src = `https://flagcdn.com/h20/${region.toLowerCase()}.png`;
    selectedFlag.alt = region;
    selectedEmoji.textContent = emoji;
    hiddenInput.value = region;
  }

  const selectedRegion = hiddenInput.value;
  const selectedItem = document.querySelector(`.dropdown-item[data-region="${selectedRegion}"]`);
  if (selectedItem) {
    updateRegion(selectedItem.dataset.region, selectedItem.dataset.emoji);
  }

  dropdownItems.forEach(item => {
    item.addEventListener('click', e => {
      e.preventDefault();
      updateRegion(item.dataset.region, item.dataset.emoji);
    });
  });
});
